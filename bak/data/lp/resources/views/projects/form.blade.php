<div class="form-group{{ $errors->has('code') ? ' has-error' : ''}}">
  <label class="col-md-12 label-control" for="code">Project Code</label>
  <div class="col-md-12">
    <input type="text" id="code" name="code" class="form-control " placeholder="Enter code" value="{{ (isset($project->code))?$project->code:old('code') }}" >
    {!! $errors->first('code', '<span class="help-block"><small>:message</small></span>') !!}
  </div>
</div>

<div class="form-group{{ $errors->has('name') ? ' has-error' : ''}}">
  <label class="col-md-12 label-control" for="name">Project Name</label>
  <div class="col-md-12">
    <input type="text" id="name" name="name" class="form-control" placeholder="Enter name" value="{{ (isset($project->name))?$project->name:old('name') }}">
    {!! $errors->first('name', '<span class="help-block"><small>:message</small></span>') !!}
  </div>
</div>

<div class="form-group{{ $errors->has('title') ? ' has-error' : ''}}">
  <label class="col-md-12 label-control" for="title">Project Title</label>
  <div class="col-md-12">
    <input type="text" id="title" name="title" class="form-control" placeholder="Enter Title" value="{{ (isset($project->title))?$project->title:old('title') }}">
    {!! $errors->first('title', '<span class="help-block"><small>:message</small></span>') !!}
  </div>
</div>

<div class="form-group{{ $errors->has('address') ? ' has-error' : ''}}">
  <label class="col-md-12 label-control" for="address">Address</label>
  <div class="col-md-12">
    <input type="text" id="address" name="address" class="form-control" placeholder="Enter Address" value="{{ (isset($project->address))?$project->address:old('address') }}">
    {!! $errors->first('address', '<span class="help-block"><small>:message</small></span>') !!}
  </div>
</div>

<div class="form-group">
  <label class="col-md-12 label-control" for="address2">Address2</label>
  <div class="col-md-12">
    <input type="text" id="address2" name="address2" class="form-control" placeholder="Enter Address2" value="{{ (isset($project->address2))?$project->address2:old('address2') }}">
  </div>
</div>

<div class="form-group{{ $errors->has('city') ? ' has-error' : ''}}">
  <label class="col-md-12 label-control" for="city">City</label>
  <div class="col-md-12">
    <input type="text" id="city" name="city" class="form-control" placeholder="Enter city" value="{{ (isset($project->city))?$project->city:old('city') }}">
    {!! $errors->first('city', '<span class="help-block"><small>:message</small></span>') !!}
  </div>
</div>

<div class="form-group{{ $errors->has('state') ? ' has-error' : ''}}">
  <label class="col-md-12 label-control" for="state">State</label>
  <div class="col-md-12">
    <input type="text" id="state" name="state" class="form-control" placeholder="Enter state" value="{{ (isset($project->state))?$project->state:old('state') }}">
    {!! $errors->first('state', '<span class="help-block"><small>:message</small></span>') !!}
  </div>
</div>

<div class="form-group{{ $errors->has('country') ? ' has-error' : ''}}">
  <label class="col-md-12 label-control" for="country">Country</label>
  <div class="col-md-12">
    <input type="text" id="country" name="country" class="form-control" placeholder="Enter country" value="{{ (isset($project->country))?$project->country:old('country') }}">
    {!! $errors->first('country', '<span class="help-block"><small>:message</small></span>') !!}
  </div>
</div>

<div class="form-group{{ $errors->has('postal_code') ? ' has-error' : ''}}">
  <label class="col-md-12 label-control" for="postal_code">Postal Code</label>
  <div class="col-md-12">
    <input type="text" id="postal_code" name="postal_code" class="form-control" placeholder="Enter postal code" value="{{ (isset($project->postal_code))?$project->postal_code:old('postal_code') }}">
    {!! $errors->first('postal_code', '<span class="help-block"><small>:message</small></span>') !!}
  </div>
</div>

<div class="form-group">
    <label class="col-md-12">Status</label>
    <div class="col-md-12">
      <div class="radio-list">
          <label class="radio-inline p-0">
              <div class="radio radio-info">
                  <input type="radio" class="minimal" name="status" id="enable" value="1"{{ ($formMode === 'create') ? 'checked' : (isset($project) && $project->status == 1)?'checked':'' }}>
                  <label for="enable">Enable</label>
              </div>
          </label>
          <label class="radio-inline">
              <div class="radio radio-info">
                  <input type="radio" class="minimal" name="status" id="disable" value="0"{{ (isset($project) && $project->status == 0)?'checked':'' }}>
                  <label for="disable">Disable</label>
              </div>
          </label>
      </div>
    </div>
</div>

<button type="submit" class="btn btn-success waves-effect waves-light m-r-10">{{ $formMode === 'edit' ? 'Update' : 'Create' }}</button>
<a href="{{ route('projects.index') }}" class="btn btn-inverse waves-effect waves-light">Cancel</a>
