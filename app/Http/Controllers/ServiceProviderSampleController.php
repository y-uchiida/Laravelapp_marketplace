<?php

namespace App\Http\Controllers;

class ServiceProviderSampleController extends Controller
{
    public function ServiceProviderSample()
    {
        /* サンプルとして、EncryptionServiceProviderを動かしてみる */

        $enc = app()->make('encrypter');
        $pw = 'password_qwerty1234';
        $crypted = $enc->encrypt($pw);
        $decrypted = $enc->decrypt($crypted);

        dd("raw pw: $pw", "ecrypted: $crypted", "decrypted: $decrypted");
    }

    public function add_serviceProviderSample()
    {
        /* artisan make:provider で作成した、`app/Providers/SampleServiceProvider` をapp.phpの
         * サービスプロバイダの配列に追加してあるので、app()->make() でこれをインスタンス化して利用できる
         */
        $service_obj = app()->make('mySampleServiceProvider');

        dd($service_obj);
    }
}
