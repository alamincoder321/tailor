@extends('layouts.master')

@section("title")
{{ $id == null ? 'Product Sales' : 'Product Sales Update' }}
@endsection
@section("bread_crum")
{{ $id == null ? 'Product Sales' : 'Product Sales Update' }}
@endsection
@section("content")
<order id="{{$id}}"></order>
@endsection