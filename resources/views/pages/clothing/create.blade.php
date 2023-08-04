@extends('layouts.master')

@section("title")
{{ $id == null ? 'Clothing Create' : 'Clothing Update' }}
@endsection
@section("bread_crum")
{{ $id == null ? 'Clothing Create' : 'Clothing Update' }}
@endsection
@section("content")
<clothing id="{{ $id }}"></clothing>
@endsection