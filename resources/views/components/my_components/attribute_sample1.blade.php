{{-- 属性値の初期値を、@propsに連想配列として渡しておくことができる --}}
@props([
    'title' => 'aricle title (initial)',
    'author' => 'no name',
    'var1' => 'no variable',
    'var2'=> 'no variable',
])

{{-- style 属性の初期値として、prepends 内の値を設定する(classの場合は不要、詳細はReadouble にも記述あり) --}}
<article {{ $attributes->merge(['style' => $attributes->prepends( 'border: 1px solid #999; padding: 1em; border-radius: 8px;') ]) }}>
    {{-- コンポーネント呼び出し時のタグに、title="..."をつけることで設定 --}}
    <p>title: {{ $title }}</p>

    {{-- コンポーネント呼び出し時のタグに、author="..."をつけることで設定 --}}
    <p>author: {{ $author }}</p>

    {{-- コントローラからのreturn で受け取った変数 var_1 を表示する --}}
    <p>$var1 from controller: {{ $var1 }}</p>

    {{-- コントローラからのreturn で受け取った変数 var_2 を表示する --}}
    <p>$var2 from controller: {{ $var2 }}</p>

</article>
