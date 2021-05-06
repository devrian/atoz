@php
    $title = 'Order History';
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
                <div class="card-box">
                    <div class="m-3">
                        <input type="hidden" id="routeFetch" value="{{route('order.index')}}">
                        <input class="form-control" type="text" id="search" placeholder="Search by Order no.">
                    </div>
                    <div class="col-12">
                        <div class="lists">
                            @include('pages.order.list')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="{{ URL::asset('js/order.js') }}"></script>
@endsection
