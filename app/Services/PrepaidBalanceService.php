<?php

namespace App\Services;

use App\Http\Requests\StorePrepaidBalanceRequest;
use App\Interfaces\OrderInterface;
use App\Interfaces\PrepaidBalanceInterface;
use App\Models\PrepaidBalance;

class PrepaidBalanceService {
    /**
     * @var $prepaidBalanceInterface
     */
    protected $prepaidBalanceInterface;
    /**
     * @var $orderInterface
     */
    protected $orderInterface;

    /**
     * PrepaidBalanceService constructor.
     *
     * @param PrepaidBalanceInterface $prepaidBalanceInterface
     * @param OrderInterface $orderInterface
     */
    public function __construct(
        PrepaidBalanceInterface $prepaidBalanceInterface,
        OrderInterface $orderInterface
    ) {
        $this->prepaidBalanceInterface = $prepaidBalanceInterface;
        $this->orderInterface = $orderInterface;
    }

    public function storing(StorePrepaidBalanceRequest $request)
    {
        $prepaidStored = $this->prepaidBalanceInterface->save($request->all());
        if (is_null($prepaidStored)) abort(400, 'Store prepaid failed.');

        $mappingOrder = app('order.helper')->mappingOrder(PrepaidBalance::class, $prepaidStored->id, $request->amount);
        $orderStored = $this->orderInterface->save($mappingOrder);
        if (is_null($orderStored)) abort(400, 'Store order failed.');

        return [
            'prepaid_balance' => $prepaidStored,
            'order' => $orderStored
        ];
    }
}
