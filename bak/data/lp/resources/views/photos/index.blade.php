@extends('layouts.app')
@section('title', 'Project Photos')

@section('content')
<div class="row bg-title">
  <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
      <h4 class="page-title">Project Photos</h4>
  </div>
  <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
    <a href="{{ route('photos.create', $project->id) }}" class="btn btn-success btn-sm btn-flat pull-right m-l-20" title="Add New Project">
      <i class="fa fa-plus" aria-hidden="true"></i> ADD Photo
    </a>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('projects.index') }}">Projects</a></li>
        <li><a href="{{ route('projects.edit', $project->id) }}">{{ ucwords($project->name) }}</a></li>
        <li class="active">Photos</li>
    </ol>
  </div>
</div>
@include('inc.messages')

<div class="row">
    <div class="col-md-12">
        <div class="white-box">
          <p>Project Name: {{ $project->name }}</p>
          <p>Document Title: {{ $project->title }}</p>
        </div>
    </div>
</div>

<!-- /row -->
@if(count($project->photos) > 0)
  <div class="row" id="photos-container">
  @foreach($project->photos as $photo)
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 photo-box-wrapper" id="{{ $photo->id }}">
          <div class="white-box photo-box">
              <div class="product-img">
                  <img src="{{ asset($photo->getPhotoUrl($photo->image_url, 'large')) }}">
                  <div class="pro-img-overlay">
                    <span class="mytooltip tooltip-effect-5">
                      <span class="tooltip-item">
                        <a href="javascript:void(0)" class="bg-info">
                          <i class="ti-eye"></i>
                        </a>
                      </span>
                      <span class="tooltip-content clearfix">
                        <span class="tooltip-text">
                          @if($photo->caption != null)
                            <span>Caption: {{ $photo->caption }}</span></br>
                          @endif
                          <span>Area: {{ $photo->area }}</span></br>
                          <span>Floor: {{ $photo->floor }}</span></br>
                          <span>Building: {{ $photo->building }}</span></br>
                          <span>Unit: {{ $photo->unit }}</span></br>
                          <span>Probe Location: {{ $photo->probe_location }}</span></br>
                          <span>Probe Number: {{ $photo->probe_number }}</span></br>
                          <span>Created At: {{ $photo->created_at }}</span></br>
                          <span>Taken At: {{ $photo->captured_at }}</span></br>
                        </span>
                      </span>
                    </span>
                    <a class="bg-primary" data-toggle="modal" data-target="#edit-photo-{{ $photo->id }}" data-whatever="@mdo">
                      <i class="ti-marker-alt"></i>
                    </a>
                    <a class="bg-danger remove-photo" data-photo-id="{{ $photo->id }}">
                      <i class="ti-trash"></i>
                    </a>
                  </div>

              </div>

          </div>
      </div>


      <div class="modal fade" id="edit-photo-{{ $photo->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title">Edit Photo</h4>
                  </div>
                  <form action="{{ route('photos.update', $photo->id) }}" method="POST" enctype="multipart/form-data">
                    {{ method_field('PATCH') }}
                    {{ csrf_field() }}
                    <div class="modal-body">
                            <div class="">
                              <div class="text-center m-t-10">
                                <img class="edit-img" id="photo-{{ $photo->id }}" src="{{ asset($photo->getPhotoUrl($photo->image_url, 'medium')) }}">
                              </div>
                              <div class="">


                                  <h3>Rotate Photo:</h3>
                                    <div class="form-group">
                                      <button type="button" class="btn btn-info" data-method="rotate" data-option="-90" title="Rotate Left" data-photo-id="{{ $photo->id }}">
                                        <i class="fa fa-undo"></i>
                                      </button>
                                      <button type="button" class="btn btn-info" data-method="rotate" data-option="90" title="Rotate Right" data-photo-id="{{ $photo->id }}">
                                        <i class="fa fa-redo"></i>
                                      </button>
                                      <input type="hidden" name="pic_rotate" id="pic_rotate_{{ $photo->id }}" value="0" data-rotate="0">
                                      {{--<span id="show_{{ $photo->id }}"></span>--}}
                                    </div>
                                  <hr/>
                                  <h3>Add Caption:</h3>
                                  <div class="form-group">
                                      <label for="caption" class="control-label">Enter Caption:</label>
                                      <input type="text" class="form-control" name="caption" placeholder="Enter photo caption" value="{{ ($photo->caption)?$photo->caption:old('caption') }}">
                                  </div>
                              </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-success waves-effect waves-light">Save changes</button>
                    </div>
                  </form>
              </div>
          </div>
      </div>

  @endforeach



  </div>
@else
  <div class="row">
      <div class="col-md-12">
          <div class="white-box text-center">
            There are no photos to display
          </div>
      </div>
  </div>
@endif

@endsection

@push('css')
<style>
  #draggable { width: 150px; height: 150px; padding: 0.5em; }
</style>
@endpush

@push('footer_scripts')
<script type="text/javascript">
  $(function() {
    $("[data-method=rotate]").click(function() {
      var photo_id = $(this).attr('data-photo-id');
      var data_option = $(this).attr('data-option');
      var data_rotate = $('#pic_rotate_'+photo_id).attr('data-rotate');
      var pic_rotate = $('#pic_rotate_'+photo_id).val();

      //var degree = parseInt(pic_rotate) + parseInt(data_option);
      var rotate_degree = parseInt(data_rotate) + parseInt(data_option);
      /*
      if (degree == 360 || degree == -360) {
        degree = 0;
      }*/
      //$('#show_'+photo_id).text(rotate_degree);
      var img = document.getElementById('photo-'+photo_id);
      img.style.transform = 'rotate('+rotate_degree+'deg)';
      $('#pic_rotate_'+photo_id).attr('data-rotate', rotate_degree);

      //$('#show_'+photo_id).text(degree);
      //$('#pic_rotate_'+photo_id).val(degree);
      //var pic_rotate_set = $('#pic_rotate_set').val();
      //alert(pic_rotate_set)

      var d = parseInt(pic_rotate) - parseInt(data_option);
      $('#pic_rotate_'+photo_id).val(d);
      //var d = parseInt(pic_rotate) - parseInt(data_option);
      //$('#show_'+photo_id).text(d);
      //$('#pic_rotate_'+photo_id).val(d);

      /*
      if (data_option == 90) {

      } else if (data_option == -90) {

      }*/
      //alert(photo_id);

    });


    $('.btn-rotate').click(function() {
      //var id = $(this).attr('data-photo-id');
      //var degree = $('#txt-rotate-'+id).val();
      //var id = $(this).attr('data-photo-id');
      //var 'degree_'+id = $(this).val();
      //alert('degree_'+id)
      //var degree_id = 0;
      //alert(degree)
      //alert(id)
      //var img = document.getElementById('photo-'+id);
      //var img = document.getElementById('photo-'+id);
      //img.style.transform = 'rotate('+degree+'deg)';
      //img.style.transform = 'rotate(90deg)';
    });

    /*
    $('.txt-rotate').blur(function() {
      var degree = $(this).val();
      var id = $(this).attr('data-photo-id');
      //var photo_id = $(this).parent('form ')
      //alert(degree)
      //alert(id)
      //$('photo-'+id).style.transform = 'rotate(180deg)';
      //$('#photo-'+id).style.transform = 'rotate(180deg)';
      var img = document.getElementById('photo-'+id);
      img.style.transform = 'rotate('+degree+'deg)';
    });
    */
    /*
    $('.frm-rotate').submit(function(e) {
      e.preventDefault();
      var t = $(this).serialize();
      console.log(t);
    });
    */

    // Start darg photos
    $( "#photos-container" ).sortable({

    });
    $( ".photo-box-wrapper" ).draggable({
      containment: '#photos-container',
      connectToSortable: "#photos-container",
      cursor: 'move',
      stop: function() {
        var idsInOrder = $("#photos-container").sortable('toArray');

        $.ajax({
          type: "POST",
          url: '{{ route("photos.order.store") }}',
          data: {_token: '{{ csrf_token() }}', idsInOrder},
          cache: false,
          success: function(response){
            if (response == 1) {
              $.toast({
                  heading: 'SUCCESS',
                  text: 'Order Changed Successfully!',
                  position: 'top-right',
                  loaderBg: '#ff6849',
                  icon: 'success',
                  hideAfter: 3500,
                  stack: 6
              });
            } else {
              $.toast({
                  heading: 'ERROR!',
                  text: 'Something went wrong!',
                  position: 'top-right',
                  loaderBg: '#ff6849',
                  icon: 'error',
                  hideAfter: 3500,
                  stack: 6
              });
              location.reload();
            }
          }
        });

      }
    });
    // End drag phots

    // Start delete photo
    $('.remove-photo').click(function(){
      var photoId = $(this).attr("data-photo-id");
      swal({
          title: "Are you sure?",
          text: "You will not be able to recover this imaginary file!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Yes, delete it!",
          closeOnConfirm: false
      }).then(function(isConfirm) {
        if (isConfirm.value == true) {
          $.ajax({
            type: "DELETE",
            url: '{{ route("photos.destroy") }}',
            data: {_token: '{{ csrf_token() }}', method: '_DELETE', photoId},
            cache: false,
            success: function(response) {
              if (response == 1) {
                $('#'+photoId).remove();
                $.toast({
                    heading: 'SUCCESS',
                    text: 'Your file was successfully deleted!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 3500,
                    stack: 6
                });
              } else {
                $.toast({
                    heading: 'ERROR!',
                    text: 'Something went wrong!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'error',
                    hideAfter: 3500,
                    stack: 6
                });
                location.reload();
              }
            }
          })




        }
      })

    });


    // End delete photo
































  });

</script>
@endpush
