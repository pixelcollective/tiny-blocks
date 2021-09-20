<div id={!! $id !!}>
  <div class="{!! $className !!}__column-a">
    @isset($attr->heading)
      <h2 class="{!! $className !!}__column-a__heading">
        {!! $attr->heading !!}
      </h2>
    @endisset
  </div>

  @isset($content)
    <div class="{!! $className !!}__column-b">
      {!! $content !!}
    </div>
  @endisset
</div>