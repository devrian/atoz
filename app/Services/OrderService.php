<?php

namespace App\Services;

use App\Http\Requests\CancelOrderRequest;
use App\Http\Requests\PaymentOrderRequest;
use App\Interfaces\OrderInterface;
use App\Interfaces\PrepaidBalanceInterface;
use App\Interfaces\ProductInterface;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderService {
    /**
     * @var $orderInterface
     */
    protected $orderInterface;
    /**
     * @var $productInterface
     */
    protected $productInterface;
    /**
     * @var $prepaidBalanceInterface
     */
    protected $prepaidBalanceInterface;

    /**
     * OrderService constructor.
     *
     * @param OrderInterface $companyInterface
     * @param ProductInterface $productInterface
     * @param PrepaidBalanceInterface $prepaidBalanceInterface
     */
    public function __construct(
        OrderInterface $orderInterface,
        ProductInterface $productInterface,
        PrepaidBalanceInterface $prepaidBalanceInterface
    ) {
        $this->orderInterface = $orderInterface;
        $this->productInterface = $productInterface;
        $this->prepaidBalanceInterface = $prepaidBalanceInterface;
    }

    public function getAll(Request $request)
    {
        return $this->orderInterface->findAll($request);
    }

    public function getByIdAndUserId($id, $userId)
    {
        return $this->orderInterface->findByIdAndUserId($id, $userId);
    }

    public function getUnpaidByIdAndUserId($id, $userId)
    {
        return $this->orderInterface->findUnpaidByIdAndUserId($id, $userId);
    }

    public function storingPaymentOrder(PaymentOrderRequest $request)
    {
        $orderUpdated = $this->orderInterface->findUnpaidByOrderAndUserId($request->order_no, $request->user_id);
        if (is_null($orderUpdated)) abort(400, 'Order not found.');

        if ($orderUpdated->model_type == Product::class) {
            $shippingCode = app('order.helper')->generateShippingCode();
            $requestProduct = [
                'id' => $orderUpdated->transaction_id,
                'shipping_code' => $shippingCode
            ];
            $productUpdated = $this->productInterface->update($requestProduct);
            if (is_null($productUpdated)) abort(400, 'Update product failed.');
        }

        $requestPayment = [
            'id' => $orderUpdated->order_id,
            'user_id' => $request->user_id,
            'order_status' => Order::STATUS_SUCCESS
        ];

        $paymentOrder = $this->orderInterface->update($requestPayment);
        if (is_null($paymentOrder)) abort(400, 'Payment Order failed.');

        return $paymentOrder;
    }

    public function storingCancelOrder($orderId, $userId)
    {
        return $this->orderInterface->cancelOrder($orderId, $userId);
    }
}
