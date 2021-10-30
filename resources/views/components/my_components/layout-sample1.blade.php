{{-- 初期値を設定 --}}
@props([
    'header_nav' => 'header initial',
])

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title></title>
</head>
<body>
    {{-- $slot 以外のスロットは、<x-slot name="変数名"></x-slot> の形式で利用される --}}
    {{ $header_nav }}

	<h1>head from layout-sample1.blade.php</h1>

    {{-- $slot は、読み込まれた先のコンポーネントタグの中の内容に置き換えられる --}}
    {{ $slot }}
</body>
</html>
