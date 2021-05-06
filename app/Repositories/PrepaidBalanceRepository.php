<?php

namespace App\Repositories;

use App\Interfaces\PrepaidBalanceInterface;
use App\Models\PrepaidBalance;

class PrepaidBalanceRepository implements PrepaidBalanceInterface
{
    public function save(array $request)
    {
        $result = PrepaidBalance::create($request);
        return $result;
    }

    public function delete($id)
    {
        $result = PrepaidBalance::find($id);
        if (is_null($result)) return;

        $result->delete();

        return $result;
    }
}
