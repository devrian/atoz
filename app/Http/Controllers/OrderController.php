<?php

namespace App\Http\Controllers;

use App\Http\Requests\CancelOrderRequest;
use App\Http\Requests\PaymentOrderRequest;
use App\Models\Order;
use App\Models\PrepaidBalance;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * @var orderService
     */
    protected $orderService;

    /**
     * OrderController Constructor
     *
     * @param OrderService $orderService
     *
     */
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $request->request->add([
            'created_by' => Auth::user()->id,
            'paginate' => Order::PAGINATE
        ]);

        $model = $this->orderService->getAll($request);
        if (is_null($model)) return redirect()->back()->withInput()->withErrors('List Order Not Found');

        if ($request->ajax()) return response()->json(View::make('pages.order.list', ['model' => $model])->render());

        return view('pages.order.index', compact('model'));
    }

    public function success($id)
    {
        $model = $this->orderService->getByIdAndUserId($id, Auth::user()->id);
        if (is_null($model)) return redirect()->back()->withInput()->withErrors('Order Not Found');

        $message = $model->model_type == PrepaidBalance::class
            ? 'Prepaid balance that costs '.number_format($model->order_amount).' will be receive to : '.$model->phone_number. ' only after you pay'
            : $model->name .' that costs '.number_format($model->order_amount).' will be shipped to : '.$model->shipping_address. ' only after you pay';

        return view('pages.order.success', compact('model', 'message'));
    }

    public function payment($id)
    {
        $model = $this->orderService->getUnpaidByIdAndUserId($id, Auth::user()->id);
        if (is_null($model)) return redirect()->back()->withInput()->withErrors('Order Unpaid Not Found');

        return view('pages.order.payment', compact('model'));
    }

    public function paymentOrder(PaymentOrderRequest $request)
    {
        DB::beginTransaction();

        try {
            $store = $this->orderService->storingPaymentOrder($request);
            if (is_null($store)) $message = 'Transaction Failed';
            $message = $store->model_type == Product::class
                ? 'Product is successfully paid'
                : 'Balance is successfully paid';

            DB::commit();
            return redirect(route('order.index'))->with('info', $message);
        } catch (ValidationException $e) {
            DB::rollBack();
            if ($e instanceof ValidationException) {
                $errors = $e->validator->errors()->toArray();
                return redirect()->back()->withInput()->withErrors($errors);
            }
            return redirect()->back()->withInput()->with('info', $e->getMessage());
        }
    }

    public function cancelOrder(CancelOrderRequest $request)
    {
        DB::beginTransaction();

        try {
            $store = $this->orderService->storingCancelOrder($request);
            if (is_null($store)) redirect()->back()->withInput()->withErrors('Something when wrong!');

            DB::commit();
            return redirect(route('order.index'))->with('info', 'Order has been canceled');
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
