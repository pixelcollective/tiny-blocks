<div class="{!! $attr->className !!}__column-a">
  @isset($attr->heading)
  <h2 class="{!! $attr->className !!}__column-a__heading">
    {!! $attr->heading !!}
  </h2>
  @endisset
</div>

<div class="{!! $attr->className !!}__column-b">
  @isset($content)
  {!! $content !!}
  @endisset
</div>