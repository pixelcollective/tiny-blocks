@extends($layout)

@section('block')
  <div class="{!! $classname !!}__column-a">
    @isset($attr->heading)
      <h2 class="{!! $classname !!}__column-a__heading">
        {!! $attr->heading !!}
      </h2>
    @endisset
  </div>

  <div class="{!! $classname !!}__column-b">
    {!! $content !!}
  </div>
@endsection
