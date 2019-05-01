@foreach($result as $key => $field)

<div style="background: #FFF; margin:0px; padding: 0px; height: 100%;">

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

  <div style="padding: 25px;">
    <table style="width:100%;">
      @php
        $numOfCols = 3;
        $rowCount = 0;
      @endphp

      <tr>
      @foreach($field['data'] as $kk => $v)

        <td><b>{{ $v['label'] }}:</b> {{ ucwords($v['value']) }} </td>

         @php
          $rowCount++
         @endphp

        @if($rowCount % $numOfCols == 0)
          </tr><tr>
        @endif

      @endforeach

    </table>
    <img src="{{ url('uploads/'.env('PROJECT_PHOTOS_DIR').'/'.$project->id.'/thumb/'. $field['image_url']) }}" alt="" width="150" height="150" style="margin-top:10px;">
  </div>



</div>
@endforeach

@push('css')
<style type="text/css" media="print">
    @media print {
      @page { margin: 0; }
    }
</style>
@endpush

<script>
  window.print();
</script>
