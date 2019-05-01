{{--
@foreach($project->photos as $photo)
--}}

@foreach($result as $key => $field)

<div style="background: #FFF; margin:0px; padding: 0px; height: 100%;">

  {{--
  <div style="padding: 25px;">
    <table style="width:100%;">
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


  <div style="padding: 25px;">
    <table style="width:100%;">

      @foreach($field as $kk => $v)
        @if(($kk % 2)==0)
          <tr>
        @endif

          <td><b>{{ $v['label'] }}:<b/> {{ ucwords($v['value']) }}</td>

        @if(($loop->iteration % 2)==0)
          </tr>
        @endif
      @endforeach
    </table>
  </div>

  {{--
  <div style="padding: 25px;">
    <table style="width:100%;">
      <tr style="margin-top:100px;">
        <td><b>Area:</b> {{ ucwords($photo->area) }}</td>
        <td><b>Unit:</b> {{ ucwords($photo->unit) }}</td>
        <td col-span=3 style="text-align:right;">{{ str_pad($loop->iteration, 3, '0', STR_PAD_LEFT) }}</td>
      </tr>
      <tr>
        <td><b>Floor:</b> {{ ucwords($photo->floor) }}</td>
        <td><b>Probe Location:</b> {{ ucwords($photo->probe_location) }}</td>
      </tr>
      <tr>
        <td><b>Building:</b> {{ ucwords($photo->building) }}</td>
        <td><b>Probe Number:</b> {{ $photo->probe_number }}</td>
      </tr>
    </table>
  </div>
  --}}
</div>
@endforeach

@push('css')
<style type="text/css" media="print">
    @media print {
      @page { margin: 0; }
    }
</style>
@endpush
