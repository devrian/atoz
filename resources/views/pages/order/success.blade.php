@php
    $title = 'Success!';
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

            <div class="card">
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-space">
                                    <p class="font-weight-bold">
                                        Order no.
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-space">
                                    <p class="font-weight-bold">
                                        {{ $model->order_no }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-space">
                                    <p class="font-weight-bold">
                                        Total
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-space">
                                    <p class="font-weight-bold">
                                        Rp {{ number_format($model->order_amount) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <p class="font-weight-normal">{{ $message }}</p>
                            <a class="btn btn-primary btn-block" href="{{ route('order.payment', ['id' => $model->order_id]) }}" role="button">
                                Pay now
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
