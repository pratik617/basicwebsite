<div class="form-group{{ $errors->has('name') ? ' has-error' : ''}}">
  <label class="col-md-12 label-control" for="name">Name</label>
  <div class="col-md-12">
    <input type="text" id="name" name="name" class="form-control" placeholder="Enter name" value="{{ (isset($member->name))?$member->name:old('name') }}">
    {!! $errors->first('name', '<span class="help-block"><small>:message</small></span>') !!}
  </div>
</div>

<div class="form-group{{ $errors->has('email') ? ' has-error' : ''}}">
  <label class="col-md-12 label-control" for="email">Email Address</label>
  <div class="col-md-12">
    <input type="email" id="email" name="email" class="form-control" placeholder="Enter email address"{{ (old('email')) ? ' value='.old('email') :'' }}{{ (isset($member->email)) ? ' value='.$member->email : '' }}>
    {!! $errors->first('email', '<span class="help-block"><small>:message</small></span>') !!}
  </div>
</div>

@if($formMode == 'create')
  <div class="form-group{{ $errors->has('password') ? ' has-error' : ''}}">
    <label class="col-md-12 label-control" for="password">Password</label>
    <div class="col-md-12">
      <input type="password" id="password" name="password" class="form-control" placeholder="Enter password">
      {!! $errors->first('password', '<span class="help-block"><small>:message</small></span>') !!}
    </div>
  </div>

  <div class="form-group">
    <label class="col-md-12 label-control" for="confirmation">Confirm Password</label>
    <div class="col-md-12">
      <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
    </div>
  </div>
@elseif($formMode == 'edit')
  <div class="form-group">
    <div class="col-md-12">
    <div class="checkbox checkbox-purple">
      <input type="checkbox" id="change_password" name="change_password">
      <label for="change_password"> Change Password </label>
    </div>
    </div>
  </div>

  <div class="" style="display:none;" id="change_password_wrapper">

    <div class="form-group">
      <label class="col-md-12 label-control" for="new_password{{ $errors->has('current_password') ? ' has-error' : ''}}">New Password</label>
      <div class="col-md-12">
        <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter new password">
        {!! $errors->first('new_password', '<span class="help-block"><small>:message</small></span>') !!}
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-12 label-control" for="new_password_confirmation{{ $errors->has('new_password_confirmation') ? ' has-error' : ''}}">Confirm Password</label>
      <div class="col-md-12">
        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" placeholder="Confirm password">
        {!! $errors->first('new_password_confirmation', '<span class="help-block"><small>:message</small></span>') !!}
      </div>
    </div>

  </div>
@endif

<div class="form-group">
    <label class="col-md-12">Status</label>
    <div class="col-md-12">
      <div class="radio-list">
          <label class="radio-inline p-0">
              <div class="radio radio-info">
                  <input type="radio" class="minimal" name="status" id="enable" value="1"{{ ($formMode === 'create') ? 'checked' : (isset($member) && $member->status == 1)?'checked':'' }}>
                  <label for="enable">Enable</label>
              </div>
          </label>
          <label class="radio-inline">
              <div class="radio radio-info">
                  <input type="radio" class="minimal" name="status" id="disable" value="0"{{ (isset($member) && $member->status == 0)?'checked':'' }}>
                  <label for="disable">Disable</label>
              </div>
          </label>
      </div>
    </div>
</div>

<button type="submit" class="btn btn-success waves-effect waves-light m-r-10">{{ $formMode === 'edit' ? 'Update' : 'Create' }}</button>
<a href="{{ route('admin.members.index') }}" class="btn btn-inverse waves-effect waves-light">Cancel</a>

@push('footer_scripts')
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
@endpush
