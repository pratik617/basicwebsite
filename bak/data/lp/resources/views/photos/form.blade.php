<input type="hidden" name="project_id" value="{{ $project->id }}">

<div class="form-group{{ $errors->has('area') ? ' has-error' : ''}}">
  <label class="col-md-12 label-control" for="area">Area</label>
  <div class="col-md-12">
    <input type="text" id="area" name="area" class="form-control " placeholder="Enter area" value="{{ (isset($photo->area))?$photo->area:old('area') }}" >
    {!! $errors->first('area', '<span class="help-block"><small>:message</small></span>') !!}
  </div>
</div>

<div class="form-group{{ $errors->has('floor') ? ' has-error' : ''}}">
  <label class="col-md-12 label-control" for="floor">Floor</label>
  <div class="col-md-12">
    <input type="text" id="floor" name="floor" class="form-control " placeholder="Enter floor" value="{{ (isset($photo->floor))?$photo->floor:old('floor') }}" >
    {!! $errors->first('floor', '<span class="help-block"><small>:message</small></span>') !!}
  </div>
</div>

<div class="form-group{{ $errors->has('building') ? ' has-error' : ''}}">
  <label class="col-md-12 label-control" for="building">Building</label>
  <div class="col-md-12">
    <input type="text" id="building" name="building" class="form-control " placeholder="Enter building" value="{{ (isset($photo->building))?$photo->building:old('building') }}" >
    {!! $errors->first('building', '<span class="help-block"><small>:message</small></span>') !!}
  </div>
</div>

<div class="form-group{{ $errors->has('unit') ? ' has-error' : ''}}">
  <label class="col-md-12 label-control" for="unit">Unit</label>
  <div class="col-md-12">
    <input type="text" id="unit" name="unit" class="form-control " placeholder="Enter unit" value="{{ (isset($photo->unit))?$photo->unit:old('unit') }}" >
    {!! $errors->first('unit', '<span class="help-block"><small>:message</small></span>') !!}
  </div>
</div>

<div class="form-group{{ $errors->has('probe_location') ? ' has-error' : ''}}">
  <label class="col-md-12 label-control" for="probe_location">Probe Location</label>
  <div class="col-md-12">
    <input type="text" id="probe_location" name="probe_location" class="form-control " placeholder="Enter probe location" value="{{ (isset($photo->probe_location))?$photo->probe_location:old('probe_location') }}" >
    {!! $errors->first('probe_location', '<span class="help-block"><small>:message</small></span>') !!}
  </div>
</div>

<div class="form-group{{ $errors->has('probe_number') ? ' has-error' : ''}}">
  <label class="col-md-12 label-control" for="probe_number">Probe Number</label>
  <div class="col-md-12">
    <input type="text" id="probe_number" name="probe_number" class="form-control " placeholder="Enter probe number" value="{{ (isset($photo->probe_number))?$photo->probe_number:old('probe_number') }}">
    {!! $errors->first('probe_number', '<span class="help-block"><small>:message</small></span>') !!}
  </div>
</div>

<div class="form-group{{ $errors->has('photos') ? ' has-error' : ''}}">
  <label for="photos" class="col-md-12 label-control">Upload Photos</label>
  <div class="col-md-12">
    <input type="file" id="photos" name="photos[]" multiple>
    {!! $errors->first('photos', '<span class="help-block"><small>:message</small></span>') !!}
  </div>
</div>

<button type="submit" class="btn btn-success waves-effect waves-light m-r-10">{{ $formMode === 'edit' ? 'Update' : 'Add Photos' }}</button>
<a href="{{ route('photos.index', $project->id) }}" class="btn btn-inverse waves-effect waves-light">Cancel</a>
