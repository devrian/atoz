<?php

namespace App\Interfaces;

interface PrepaidBalanceInterface {

    public function save(array $request);

    public function delete($id);
}
