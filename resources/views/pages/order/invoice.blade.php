@extends('layouts.master')

@section("title", "Order Invoice")
@section("bread_crum", "Order Invoice")
@section("content")
<order-invoice id="{{ $id }}"></order-invoice>
@endsection