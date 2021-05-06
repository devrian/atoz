<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Services\ProductService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * @var productService
     */
    protected $prepaidBalanceService;

    /**
     * ProductController Constructor
     *
     * @param ProductService $productService
     *
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        return view('pages.product.index');
    }

    public function store(StoreProductRequest $request)
    {
        DB::beginTransaction();

        try {
            $store = $this->productService->storing($request);
            if (is_null($store)) redirect()->back()->withInput()->withErrors('Something when wrong!');
            $order = $store['order'];

            DB::commit();
            return redirect(route('order.success', ['id' => $order->id]))->with('info', 'Product is successfully created');
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
