@extends('layout.customer.app')
@section('title', 'Profile')
@section('content')
<form action="{{ route('customer.profile.store') }}" method="POST">
  @csrf
  <div class="form-group">
    <label for="name">Name</label>
    <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" value="{{ (isset($user['name']))?$user['name']:old('name') }}">
  </div>
  <div class="form-group">
    <label for="email">Email address</label>
    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="{{ (isset($user['email']))?$user['email']:old('email') }}">
  </div>
  <div class="form-group">
    <label for="invite_code">Invite Code</label>
    <input type="text" class="form-control" id="invite_code" name="invite_code" value="{{ (isset($user['invite_code']))?$user['invite_code']:'' }}" readonly>
  </div>

  <div class="form-group">
    <label for="invite_code">Phone number</label>
    <div class="row">
      <div class="col-md-2">
        <select id="country_code" name="country_code" class="form-control">
            @foreach($countries as $country)
                <option value="{{ $country->phone_code }}"{{ (isset($user['country_code']) && $user['country_code'] == $country->phone_code)?' selected':old('country_code') }}>{{ $country->phone_code}}</option>
            @endforeach
        </select>
      </div>
      <div class="col-md-10">
        <input type="text" class="form-control" id="contact_no" name="contact_no" value="{{ (isset($user['contact_no']))?$user['contact_no']:old('contact_no') }}">
      </div>

    </div>
  </div>

  <div class="custom-control custom-checkbox mb-3">
    <input type="checkbox" class="custom-control-input" id="change_password" name="change_password">
    <label class="custom-control-label" for="change_password">Change Password</label>
  </div>
  <div class="" style="display:none;" id="change_password_wrapper">


  <div class="form-group">
    <label for="current_password">Current Password</label>
    <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Enter current password">
  </div>
  <div class="form-group">
    <label for="new_password">New Password</label>
    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter new password">
  </div>
  <div class="form-group">
    <label for="new_password_confirm">Confirm Password</label>
    <input type="password" class="form-control" id="new_password_confirm" name="new_password_confirmation" placeholder="Enter password again">
  </div>
  </div>
  <button type="submit" class="btn btn-primary">Save changes</button>
</form>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    if ($('#change_password').prop('checked') == true) {
      $('#change_password_wrapper').show();
    } else if($('#change_password').prop('checked') == false) {
      $('#change_password_wrapper').hide();
    }

    $('#change_password').click(function() {
      if ($(this).prop('checked') == true) {
        $('#change_password_wrapper').show();
      } else if($(this).prop('checked') == false) {
        $('#change_password_wrapper').hide();
      }
    });

  });
</script>
@endsection
