<?php

namespace App\Repositories;

use App\Interfaces\ProductInterface;
use App\Models\Product;

class ProductRepository implements ProductInterface
{
    public function findById($id)
    {
        $result = Product::withTrashed()->where('id', $id)->first();
        return $result;
    }

    public function findByShippingCode($shippingCode)
    {
        $result = Product::withTrashed()->where('shipping_code', $shippingCode)->first();
        return $result;
    }

    public function save(array $request)
    {
        $result = Product::create($request);
        return $result;
    }

    public function update(array $request)
    {
        $result = $this->findById($request['id']);
        if (is_null($result)) return;

        $result->update($request);

        return $result;
    }

    public function delete($id)
    {
        $result = $this->findById($id);
        if (is_null($result)) return;

        $result->delete();

        return $result;
    }
}
