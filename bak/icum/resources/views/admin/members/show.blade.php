@extends('admin.layouts.app')
@section('title', 'Members')

@section('content')
<div class="row bg-title">
  <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
      <h4 class="page-title">Members</h4>
  </div>
  <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
      <ol class="breadcrumb">
          <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          <li><a href="{{ route('admin.members.index') }}">Members</a></li>
          <li class="active">{{ ucwords($member->first_name) }} {{ ucwords($member->last_name) }}</li>
      </ol>
  </div>
</div>
<!-- /.col-lg-12 -->

<div class="row">
    <div class="col-lg-12">
        <div class="white-box">
          <!-- START project details -->
          <div class="">
            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12">
                  <h3 class="box-title">Member Details
                    <a href="{{ route('admin.members.index') }}" class="fcbtn btn btn-primary btn-outline btn-1c btn-sm pull-right">Go Back</a>
                  </h3>
                  <div class="table-responsive">
                      <table class="table">
                          <tbody>
                              <tr>
                                  <td width="390">Name</td>
                                  <td> {{ ucwords($member->name) }} </td>
                              </tr>
                              <tr>
                                  <td>Email Address</td>
                                  <td> {{ $member->email }} </td>
                              </tr>
                              <tr>
                                  <td>Joined at</td>
                                  <td> {{ $member->joined_at }} </td>
                              </tr>
                              <tr>
                                  <td>Last login at</td>
                                  <td> {{ $member->last_login_at }} </td>
                              </tr>
                              <tr>
                                  <td>Created at</td>
                                  <td> {{ $member->created_at }} </td>
                              </tr>
                              <tr>
                                  <td>Updated at</td>
                                  <td> {{ $member->updated_at }} </td>
                              </tr>
                              <tr>
                                  <td>Last IP</td>
                                  <td> {{ $member->last_ip }} </td>
                              </tr>
                              <tr>
                                  <td>Status</td>
                                  <td>
                                    @if($member->status == 1)
                                      <span class="text-success">Enable</span>
                                    @else
                                      <span class="text-danger">Disable</span>
                                    @endif
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
    </div>
</div>

@endsection

@push('header_scripts')


@endpush

@push('footer_scripts')
<!-- JQuery printPage -->


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
