@extends('layouts.app')
@section('title', 'Add Photos')

@section('content')
<div class="row bg-title">
  <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
      <h4 class="page-title">Add Photos</h4>
  </div>
  <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
      <ol class="breadcrumb">
          <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
          <li><a href="{{ route('projects.index') }}">Projects</a></li>
          <li><a href="{{ route('projects.edit', $project->id) }}">{{ ucwords($project->name) }}</a></li>
          <li class="active">Add Photos</li>
      </ol>
  </div>
</div>
<!-- /.col-lg-12 -->

<!-- .row -->
<div class="row">
    <div class="col-sm-12">
        <div class="white-box">
            <h5>Project name: {{ $project->name }}</h5>
            <h5>Document title: {{ $project->title }}</h5>
            <form class="form-horizontal" method="POST" action="{{ route('photos.store', $project->id) }}" enctype="multipart/form-data">
              {{ csrf_field() }}

              @include ('photos.form', ['formMode' => 'create'])

            </form>
        </div>
    </div>
</div>
<!-- /.row -->
@endsection
