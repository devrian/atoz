<?php

namespace App\Repositories;

use App\Interfaces\OrderInterface;
use App\Models\Order;
use App\Models\PrepaidBalance;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderInterface
{
    private function getQuery()
    {
        $query = $query = Order::select(DB::raw('
            orders.id AS order_id,
            orders.order_no,
            orders.model_type,
            orders.transaction_id,
            orders.amount AS order_amount,
            order_status,
            products.name,
            products.amount AS product_amount,
            products.shipping_address,
            products.shipping_code,
            prepaid_balances.phone_number,
            prepaid_balances.amount AS prepaid_amount,
            orders.created_by AS order_by,
            orders.created_at AS order_created_at,
            orders.updated_at AS order_updated_at
        '))

        ->leftJoin('products', 'products.id', '=', 'orders.transaction_id')
        ->leftJoin('prepaid_balances', 'prepaid_balances.id', '=', 'orders.transaction_id');

        return $query;
    }

    public function findAll(Request $request)
    {
        $query = $this->getQuery()->withTrashed();

        if (!is_null($request->created_by)) $query->where('orders.created_by', $request->created_by);

        if (!is_null($request->order_status)) $query->where('orders.order_status', $request->order_status);

        if (!is_null($request->order_no)) $query->where('orders.order_no', 'LIKE', '%' . $request->order_no . '%');

        $query->orderBy('orders.created_at', 'desc');

        if (!is_null($request->paginate)) $result = $query->paginate($request->paginate);
        else $result = $query->get();

        if (is_null($result)) return;

        return $result;
    }

    public function findAllUnpaid()
    {
        $result = Order::where('order_status', Order::STATUS_NEW)->get();
        if (is_null($result)) return;

        return $result;
    }

    public function findAllUnpaidByUserId($userId)
    {
        $query = $this->getQuery();

        $result = $query->where('orders.created_by', $userId)
            ->where('orders.order_status', Order::STATUS_NEW)
            ->get();

        if (is_null($result)) return;

        return $result;
    }

    public function findByIdAndUserId($id, $userId)
    {
        $query = $this->getQuery();

        $result = $query->where('orders.id', $id)
            ->where('orders.created_by', $userId)
            ->first();

        if (is_null($result)) return;

        return $result;
    }

    public function findUnpaidByIdAndUserId($id, $userId)
    {
        $query = $this->getQuery();

        $result = $query->where('orders.id', $id)
            ->where('orders.created_by', $userId)
            ->where('orders.order_status', Order::STATUS_NEW)
            ->first();

        if (is_null($result)) return;

        return $result;
    }

    public function findByOrderNumber($orderNo)
    {
        $query = $this->getQuery();

        $result = $query->where('orders.order_no', $orderNo)->first();

        if (is_null($result)) return;

        return $result;
    }

    public function findByOrderAndUserId($orderNo, $userId)
    {
        $query = $this->getQuery();

        $result = $query->where('orders.order_no', $orderNo)
            ->where('orders.created_by', $userId)
            ->first();

        if (is_null($result)) return;

        return $result;
    }

    public function findUnpaidByOrderAndUserId($orderNo, $userId)
    {
        $query = $this->getQuery();

        $result = $query->where('orders.order_no', $orderNo)
            ->where('orders.created_by', $userId)
            ->where('orders.order_status', Order::STATUS_NEW)
            ->first();

        if (is_null($result)) return;

        return $result;
    }

    public function save(array $request)
    {
        $result = Order::create($request);
        return $result;
    }

    public function update(array $request)
    {
        $result = Order::where('id', $request['id'])->where('created_by', $request['user_id'])->first();
        if (is_null($result)) return;

        $result->update($request);

        return $result;
    }

    public function cancelOrder($orderId, $userId)
    {
        $orderUpdated = $this->findUnpaidByIdAndUserId($orderId, $userId);
        if (is_null($orderUpdated)) abort(400, 'Order not found.');

        $transactionDeleted = $orderUpdated->model_type == Product::class
            ? Product::find($orderUpdated->transaction_id)
            : PrepaidBalance::find($orderUpdated->transaction_id);

        if (is_null($transactionDeleted)) abort(400, 'Delete transaction failed.');
        $transactionDeleted->delete();

        $requestPayment = [
            'id' => $orderUpdated->order_id,
            'user_id' => $userId,
            'order_status' => Order::STATUS_CANCEL
        ];

        $cancelOrder = $this->update($requestPayment);
        if (is_null($cancelOrder)) abort(400, 'Payment Order failed.');
        $cancelOrder->delete();

        return $cancelOrder;
    }
}
