@php
    $title = 'Pay your order';
@endphp

@extends('layouts.app')

@section('title', $title)

@section('content')

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-6">

            @include('components.title', ['title' => $title])

            @include('components.alert-info')

            @include('components.alert-error')

            <form method="POST" action="{{ route('order.payment.order', ['id' => $model->order_id]) }}">

                {{ csrf_field() }}
                {{ method_field('PUT') }}

                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

                <div class="form-group">
                    <input type="text"
                        required
                        class="form-control"
                        placeholder="Order no."
                        name="order_no"
                        value="{{ !is_null($model->order_no) ? $model->order_no : old('order_no') }}"
                        onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                    >
                </div>
                <button type="submit" class="btn btn-primary btn-block">
                    Pay now
                </button>
            </form>

        </div>
    </div>
</div>

@endsection
