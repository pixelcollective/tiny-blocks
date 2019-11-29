<div class="@isset($classname)wp-block-{!! $classname !!}@else{!! 'wp-block' !!}@endif @isset($attr->align)align{!! $attr->align !!}@endif">
  @yield('block')
</div>
