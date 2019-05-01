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
          <form class="" action="{{ route('projects.print') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="project_id" value="{{ $project->id }}">

            <h3 class="box-title">Select items to print
              <button id="btn-print" type="submit" class="btnprn btn btn-warning btn-sm btn-flat pull-right"><i class="fas fa-print" aria-hidden="true"></i> Print</button>

              {{--
              <a id="btn-print" href="{{ route('projects.print', $project->id) }}" class="btnprn btn btn-warning btn-sm btn-flat pull-right" title="Print Project">
                <i class="fas fa-print" aria-hidden="true"></i> Print
              </a>
              --}}
              {{--
              <a id="btn-print" href="{{ route('projects.print', $project->id) }}" class="btnprn btn btn-warning btn-sm btn-flat pull-right" title="Print Project">
                <i class="fas fa-print" aria-hidden="true"></i> Print
              </a>
              --}}
            </h3>

            <div class="row">
              <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="checkbox checkbox-purple checkbox-circle">
                  <input name="area" type="checkbox" checked="">
                  <label for="area"> Photo Area </label>
                </div>
                <div class="checkbox checkbox-purple checkbox-circle">
                  <input name="floor" type="checkbox" checked="">
                  <label for="floor"> Photo Floor </label>
                </div>
                <div class="checkbox checkbox-purple checkbox-circle">
                  <input name="building" type="checkbox" checked="">
                  <label for="building"> Photo Building </label>
                </div>
                <div class="checkbox checkbox-purple checkbox-circle">
                  <input name="unit" type="checkbox" checked="">
                  <label for="unit"> Photo Unit </label>
                </div>
              </div>

              <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="checkbox checkbox-purple checkbox-circle">
                  <input name="probe_location" type="checkbox" checked="">
                  <label for="probe_location"> Photo Probe Location </label>
                </div>
                <div class="checkbox checkbox-purple checkbox-circle">
                  <input name="probe_number" type="checkbox" checked="">
                  <label for="probe_number"> Photo Probe Number </label>
                </div>
                <div class="checkbox checkbox-purple checkbox-circle">
                  <input name="caption" type="checkbox">
                  <label for="caption"> Photo Caption </label>
                </div>
              </div>
            </div>
          </form>
        </div>
    </div>
</div>

{{--
<div style="background: #fff; padding: 25px; margin-bottom: 30px;">
  <table id="print-wrapper" style="width:100%;">
    <tr>
      <td>
        <img src="{{ asset('plugins/images/logo.png') }}" alt="home" width="120" />
      </td>
      <td style="text-align: right">
        <span><b>{{ $project->name }}</b></span><br/>
        {{ $project->title }}
      </td>
    </tr>
  </table>
</div>
--}}
@endsection

@push('header_scripts')


@endpush

@push('footer_scripts')
<!-- JQuery printPage -->
<script src="{{ asset('js/jquery.printPage.js') }}"></script>

<!-- printThis
<script type="text/javascript" src="{{ asset('js/printThis.js') }}"></script>
-->
<script type="text/javascript">
$(document).ready(function(){
//$('.btnprn').printPage();
});
  $(function() {
    //$('#btn-print').printPage();

    /*
    $('#btn-print').on("click", function (e) {
        e.preventDefault();
        $('#print-wrapper').printThis({
          printContainer: true,
          debug: true
        });
    });
    */
  });

</script>
@endpush
