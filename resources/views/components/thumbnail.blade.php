{{-- shops の画像か、products の画像かによって、パスを切り替える --}}
@php
if($type === 'shops'){
  $path = 'storage/shops';
}
if($type === 'products'){
  $path = 'storage/products';
}
@endphp

<div>
    @if (empty($filename))
        <img src="{{ asset('images/no_image.png') }}">
    @else
        <img src="{{ asset("$path/$filename") }}">
    @endif
</div>
