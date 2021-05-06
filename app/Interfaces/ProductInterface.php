<?php

namespace App\Interfaces;

interface ProductInterface {

    public function findById($id);

    public function findByShippingCode($shippingCode);

    public function save(array $request);

    public function update(array $request);

    public function delete($id);
}
