{{-- components/sample_slot/layout-sample1.blade.php を利用する --}}
<x-my_components.layout-sample1>

    {{-- 名前付きslot header_navの内容を記述する--}}
    <x-slot name="header_nav">
        <header>
            header
            <ul>
                <li>menu 1</li>
                <li>menu 2</li>
                <li>menu 3</li>
            </ul>
        </header>
    </x-slot>

    <p style="color: violet;">this text showd from sample1.blade.php</p>
    {{-- コンポーネントに属性として値を渡す(コントローラからの変数を使う場合は先頭にコロン) --}}
    <x-my_components.attribute_sample1 title="foo" author="bar" :var1="$var1" :var2="$var2" />

    <x-my_components.attribute_sample1 title="hoge" author="fuga" :var-1="$var1" :var2="$var2" />

    {{-- 属性値を渡さない場合、コンポーネント側で渡しておいた初期値(@props)が利用される --}}
    <x-my_components.attribute_sample1 />

    <x-my_components.attribute_sample1 title="背景色を変える" style="background: lightgreen;" />

</x-my_components.layout-sample1>
