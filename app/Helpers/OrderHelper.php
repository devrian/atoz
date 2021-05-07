<?php

namespace App\Helpers;

use App\Models\Order;
use App\Models\PrepaidBalance;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use Carbon\Carbon;

class OrderHelper
{
    public function getCountingUnpaidOrderByUserId($userId)
    {
        $repo = new OrderRepository;
        $result = $repo->findAllUnpaidByUserId($userId);
        if (is_null($result)) return 0;

        return $result->count();
    }

    public function generateOrderNumber($result = null)
    {
        $repo = new OrderRepository;
        $result = !is_null($result) ? $result : substr(time(), 0, 10);
        $checkExist = $repo->findByOrderNumber($result);
        if (!is_null($checkExist)) return $this->generateOrderNumber($result + 1);

        return $result;
    }

    public function mappingOrder($className, $transactionId, $amount)
    {
        $orderNo = $this->generateOrderNumber();
        $expiredAt = Carbon::now()->addMinute(5);
        $amountOrder = $className == PrepaidBalance::class
            ? ($amount * 0.05) + $amount
            : $amount + 10000;

        return [
            'order_no' => $orderNo,
            'model_type' => $className,
            'transaction_id' => $transactionId,
            'amount' => $amountOrder,
            'order_status' => Order::STATUS_NEW,
            'expired_at' => Carbon::parse($expiredAt)
        ];
    }

    public function generateShippingCode($unique = null)
    {
        $repo = new ProductRepository;
        $unique = !is_null($unique) ? $unique : substr(time(), 0, 5);
        $result = 'SHP' . $unique;
        $checkExist = $repo->findByShippingCode($result);
        if (!is_null($checkExist)) return $this->generateShippingCode($unique + 1);

        return $result;
    }
}
