<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/* Blade コンポーネントの動作テスト用 */
class BladeComponentSampleController extends Controller
{
    public function showSampleComponent1(){
        $var1 = "controller showSampleComponent1 var 1";
        $var2 = "controller showSampleComponent1 var 2";

        /* resources/views/sample_components/sample1.blade.php を表示 */
        return view("sample_components.sample1", compact("var1", "var2"));
    }

    public function showSampleComponent2(){
        /* resources/views/sample_components/sample2.blade.php を表示 */
        $var1 = "controller showSampleComponent2 var 1";
        $var2 = "controller showSampleComponent2 var 2";

        return view("sample_components.sample2", compact("var1", "var2"));
    }
}
