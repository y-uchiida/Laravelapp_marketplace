@props(['status' => 'info'])

@php
/* with() メソッドで、statusキーにフラッシュメッセージの種別を入れているのでそれによって見映えを変える */
if(session('status') === 'info'){$bgColor = 'bg-blue-300';}
if(session('status') === 'alert'){$bgColor = 'bg-red-500';}
@endphp

@if(session('message'))
  <div class="{{ $bgColor }} w-1/2 mx-auto p-2 text-white">
    {{ session('message' )}}
  </div>
@endif
