<?php

namespace App\Services;

use App\Http\Requests\StoreProductRequest;
use App\Interfaces\OrderInterface;
use App\Interfaces\ProductInterface;
use App\Models\Product;

class ProductService {
    /**
     * @var $productInterface
     */
    protected $productInterface;
    /**
     * @var $orderInterface
     */
    protected $orderInterface;

    /**
     * ProductService constructor.
     *
     * @param ProductInterface $productInterface
     * @param OrderInterface $orderInterface
     */
    public function __construct(
        ProductInterface $productInterface,
        OrderInterface $orderInterface
    ) {
        $this->productInterface = $productInterface;
        $this->orderInterface = $orderInterface;
    }

    public function storing(StoreProductRequest $request)
    {
        $productStored = $this->productInterface->save($request->all());
        if (is_null($productStored)) abort(400, 'Store product failed.');

        $mappingOrder = app('order.helper')->mappingOrder(Product::class, $productStored->id, $request->amount);
        $orderStored = $this->orderInterface->save($mappingOrder);
        if (is_null($orderStored)) abort(400, 'Store order failed.');

        return [
            'product' => $productStored,
            'order' => $orderStored
        ];
    }
}
