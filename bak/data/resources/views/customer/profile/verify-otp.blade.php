@extends('layout.customer.app')
@section('title', 'Verify OTP')
@section('content')
<form action="{{ route('customer.profile.verification') }}" method="POST">
  @csrf
  <div class="form-group">
    <label for="otp">Name</label>
    <input type="text" class="form-control" id="otp" name="otp" placeholder="Enter otp">
  </div>
  <button type="submit" class="btn btn-primary">Send OTP</button>
</form>
@endsection
