<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePrepaidBalanceRequest;
use App\Services\PrepaidBalanceService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PrepaidBalanceController extends Controller
{
    /**
     * @var prepaidBalanceService
     */
    protected $prepaidBalanceService;

    /**
     * PrepaidBalanceController Constructor
     *
     * @param PrepaidBalanceService $prepaidBalanceService
     *
     */
    public function __construct(PrepaidBalanceService $prepaidBalanceService)
    {
        $this->prepaidBalanceService = $prepaidBalanceService;
    }

    public function index()
    {
        return view('pages.prepaid-balance.index');
    }

    public function store(StorePrepaidBalanceRequest $request)
    {
        DB::beginTransaction();

        try {
            if (substr($request->phone_number, 0, 3) !== '081') {
                return redirect()->back()->withInput()->withErrors(
                    'Phone number must be prefixed with "081" start from 3 digit in the beginning of number'
                );
            }

            $store = $this->prepaidBalanceService->storing($request);
            if (is_null($store)) redirect()->back()->withInput()->withErrors('Something when wrong!');
            $order = $store['order'];

            DB::commit();
            return redirect(route('order.success', ['id' => $order->id]))->with('info', 'Balance is successfully created');
        } catch (ValidationException $e) {
            DB::rollBack();
            if ($e instanceof ValidationException) {
                $errors = $e->validator->errors()->toArray();
                return redirect()->back()->withInput()->withErrors($errors);
            }
            return redirect()->back()->withInput()->with('info', $e->getMessage());
        }
    }
}
