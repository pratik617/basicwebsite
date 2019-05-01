@extends('layouts.app')
@section('title', 'Projects')

@section('content')
<div class="row bg-title">
  <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
      <h4 class="page-title">Projects</h4>
  </div>
  <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
      <ol class="breadcrumb">
          <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
          <li><a href="{{ route('projects.index') }}">Projects</a></li>
          <li class="active">{{ $project->name }}</li>
      </ol>
  </div>
</div>
<!-- /.col-lg-12 -->

<div class="row">
    <div class="col-md-12">
        <div class="white-box">
            <h3 class="box-title">Select items to print
              <a href="{{ route('projects.print', $project->id) }}" class="btn btn-warning btn-sm btn-flat pull-right" title="Print Project">
                <i class="fas fa-print" aria-hidden="true"></i> Print
              </a>
            </h3>

            <div class="row">
              <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="checkbox checkbox-purple checkbox-circle">
                  <input id="area" type="checkbox" checked="">
                  <label for="area"> Photo Area </label>
                </div>
                <div class="checkbox checkbox-purple checkbox-circle">
                  <input id="floor" type="checkbox" checked="">
                  <label for="floor"> Photo Floor </label>
                </div>
                <div class="checkbox checkbox-purple checkbox-circle">
                  <input id="building" type="checkbox" checked="">
                  <label for="building"> Photo Building </label>
                </div>
                <div class="checkbox checkbox-purple checkbox-circle">
                  <input id="unit" type="checkbox" checked="">
                  <label for="unit"> Photo Unit </label>
                </div>
              </div>

              <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="checkbox checkbox-purple checkbox-circle">
                  <input id="probe_location" type="checkbox" checked="">
                  <label for="probe_location"> Photo Probe Location </label>
                </div>
                <div class="checkbox checkbox-purple checkbox-circle">
                  <input id="probe_number" type="checkbox" checked="">
                  <label for="probe_number"> Photo Probe Number </label>
                </div>
                <div class="checkbox checkbox-purple checkbox-circle">
                  <input id="caption" type="checkbox" checked="">
                  <label for="caption"> Photo Caption </label>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>

<!-- /row -->
<div class="row">
    <div class="col-lg-12">
        <div class="white-box">
          <!-- START project details -->
          <div class="">
            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12">
                  <h3 class="box-title">Project Details</h3>
                  <div class="table-responsive">
                      <table class="table">
                          <tbody>
                              <tr>
                                  <td width="390">Project Name</td>
                                  <td> {{ $project->name }} </td>
                              </tr>
                              <tr>
                                  <td>Project Title</td>
                                  <td> {{ $project->title }} </td>
                              </tr>
                              <tr>
                                  <td>Project Code</td>
                                  <td> {{ $project->code }} </td>
                              </tr>
                              <tr>
                                  <td>Address</td>
                                  <td> {{ $project->address }},
                                    @if($project->address2)
                                      {{ $project->address2 }},
                                    @endif
                                    {{ $project->city }},
                                    {{ $project->state }},
                                    {{ $project->country }} -
                                    {{ $project->postal_code }}
                                  </td>
                              </tr>
                          </tbody>
                      </table>
                  </div>
              </div>
            </div>
          </div>
          <!-- END project details -->
        </div>


        <!-- START photos details -->
        @foreach($project->photos as $photo)
        <div class="white-box">
          <div class="">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-6">
                    {{--<div class="white-box text-center"> <img src="{{ asset(env('STORAGE_DIR').'/'.env('PROJECT_PHOTOS_DIR').'/'.$photo->project_id.'/'.$photo->image_url) }}" class="img-responsive" /> </div>--}}

                    <div class="white-box text-center">
                      <img src="{{ $photo->getPhotoUrl($photo->image_url, 'large') }}" class="img-responsive" />
                    </div>

                </div>
                <div class="col-lg-9 col-md-9 col-sm-6">
                  <h3 class="box-title">Photo {{ $loop->index + 1 }}{{ ($photo->caption)?' - '.$photo->caption:'' }}</h3>
                  <div class="table-responsive">
                      <table class="table">
                          <tbody>
                              <tr>
                                  <td width="390">Area</td>
                                  <td> {{ ucwords($photo->area) }} </td>
                              </tr>
                              <tr>
                                  <td>Floor</td>
                                  <td> {{ ucwords($photo->floor) }} </td>
                              </tr>
                              <tr>
                                  <td>Building</td>
                                  <td> {{ ucwords($photo->building) }} </td>
                              </tr>
                              <tr>
                                  <td>Unit</td>
                                  <td> {{ ucwords($photo->unit) }} </td>
                              </tr>
                              <tr>
                                  <td>Probe Location</td>
                                  <td> {{ ucwords($photo->probe_location) }} </td>
                              </tr>
                              <tr>
                                  <td>Probe Number</td>
                                  <td> {{ $photo->probe_number }} </td>
                              </tr>
                              <tr>
                                  <td>Caption</td>
                                  <td> {{ ucwords($photo->caption) }} </td>
                              </tr>
                          </tbody>
                      </table>
                  </div>

                </div>
            </div>
        </div>
        </div>
        <!-- END photos details -->
        @endforeach

    </div>
</div>
@endsection

@push('header_scripts')
<!-- JQuery printPage -->
<script src="{{ asset('js/jquery.printPage.js') }}"></script>
@endpush

@push('footer_scripts')
<script type="text/javascript">
  $(function() {

  });

</script>
@endpush
