@extends('layouts.master')

@section("title", "User Create")
@section("bread_crum", "User Create")
@section("content")
<user></user>
@endsection

@push('js')
    <script>
        @if(Session::has('success'))
            toastr.success("{{Session::get('success')}}")
        @endif
        @if(Session::has('error'))
            toastr.error("{{Session::get('error')}}")
        @endif
    </script>
@endpush