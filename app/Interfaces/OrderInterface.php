<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface OrderInterface {

    public function findAll(Request $request);

    public function findAllUnpaid();

    public function findAllUnpaidByUserId($userId);

    public function findByIdAndUserId($id, $userId);

    public function findUnpaidByIdAndUserId($id, $userId);

    public function findByOrderNumber($orderNo);

    public function findByOrderAndUserId($orderNo, $userId);

    public function findUnpaidByOrderAndUserId($orderNo, $userId);

    public function save(array $request);

    public function update(array $request);

    public function cancelOrder($orderId, $userId);
}
