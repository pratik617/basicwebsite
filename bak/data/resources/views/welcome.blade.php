@extends('layout.customer.layout')

@section('title', 'Customer Registration - RideApp')
@section('content')

            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    <center><h1>Ride App Welcome</h1></center>
                </div>
            </div>

@endsection
@push('css')
<style type="text/css">
</style>
@endpush
@push('script')
<script type="text/javascript">
    $(document).ready(function(){

    });
</script>
@endpush
