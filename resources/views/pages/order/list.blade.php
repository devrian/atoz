<div class="table-responsive">
    <table class="table table-hover" style="width: 100%;">
        <tbody>
            @foreach ($model as $order)
                <tr>
                    <td>
                        {{$order->order_no}} <br/>
                        <p class="font-weight-bold">
                            @if ($order->model_type == \App\Models\Product::class)
                                {{ $order->name }}
                                that costs
                                Rp {{ number_format($order->product_amount) }}
                            @else
                                Rp {{ number_format($order->prepaid_amount) }} for {{ $order->phone_number }}
                            @endif
                        </p>
                    </td>
                    <td width="110">
                        Rp {{ number_format($order->order_amount) }}
                    </td>
                    <td width="150">
                        @if ($order->order_status == \App\Models\Order::STATUS_SUCCESS)
                            @if (!empty($order->product))
                                <p class="font-weight-bold">
                                    Shipping Code
                                    @if (!is_null($order->shipping_code))
                                        {{ $order->shipping_code }}
                                    @else
                                        <a class="btn btn-primary" href="#" role="button">
                                            Pay now
                                        </a>
                                    @endif
                                </p>
                            @else
                                @if ($order->model_type == \App\Models\Product::class)
                                    <p class="font-weight-bold text-center">
                                        Shipping Code <br/>
                                        {{ $order->shipping_code }}
                                    </p>
                                @else
                                    <p class="text-success font-weight-bold">Success</p>
                                @endif
                            @endif
                        @else
                            @if ($order->order_status == \App\Models\Order::STATUS_CANCEL)
                                <p class="text-danger font-weight-bold">Canceled</p>
                            @elseif ($order->order_status == \App\Models\Order::STATUS_FAIL)
                                <p style="color: orange;" font-weight-bold>Failed</p>
                            @else
                                <a class="btn btn-sm btn-primary mr-2" href="{{ route('order.payment', ['id' => $order->order_id]) }}" role="button">
                                    Pay now
                                </a>
                                <form style="display: inline;" method="POST" action="{{ route('order.cancel.order', ['id' => $order->order_id]) }}">

                                    {{ csrf_field() }}
                                    {{ method_field('PUT') }}

                                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                    <input type="hidden" name="order_id" value="{{ $order->order_id }}">

                                    <button type="submit" class="btn btn-sm btn-danger">
                                        X
                                    </button>
                                </form>
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $model->links() }}
</div>
