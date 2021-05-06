@php
    $title = 'Product Page';
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

            <form method="POST" action="{{ route('product.store') }}">

                {{ csrf_field() }}
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

                <div class="form-group">
                    <textarea
                        rows="2"
                        required
                        maxlength="150"
                        class="form-control"
                        name="name"
                        placeholder="Product"
                    >{{ old('name') }}</textarea>
                </div>
                <div class="form-group">
                    <textarea
                        rows="2"
                        required
                        maxlength="150"
                        class="form-control"
                        name="shipping_address"
                        placeholder="Shipping Address"
                    >{{ old('shipping_address') }}</textarea>
                </div>
                <div class="form-group">
                    <input type="text"
                        required
                        class="form-control"
                        placeholder="Price"
                        name="amount"
                        value="{{ old('amount') }}"
                        onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                    >
                </div>
                <button type="submit" class="btn btn-primary btn-block">
                    Submit
                </button>
            </form>

        </div>
    </div>
</div>

@endsection
