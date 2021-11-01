<?php

namespace App\Http\Controllers;

class ServiceContainerTestController extends Controller
{
    public function showServiceContainer()
    {
        /* サービスコンテナapp は、いろいろな機能を持ったサービスクラスを格納している */

        /* シンプルなサービスを追加してみる */
        app()->bind('mySampleService1', function () {
            return 'this is mySampleService1';
        });

        /* サービスを変数に取り出す */
        $service_obj = app()->make('mySampleService1');

        dd($service_obj, app());
    }

    public function DI_test()
    {
        /* サービスコンテナを使わず、個別にインスタンス化する */
        $message_obj = new Message;
        $sample_1 = new Sample($message_obj);
        $sample_1->run(); /* Message_1 の send() が実行される */

        /* サービスコンテナを利用して、依存しているクラス(Message)を自動でインスタンス化する */
        app()->bind('sample', Sample::class); /* Sample クラスをサービスコンテナに追加しておく(::class をつける) */
        $sample_2 = app()->make('sample'); /* make で、Sampleクラスのインスタンスを取り出す */
        $sample_2->run();

    }
}

/* Sample: 外部クラスに依存して動作するクラス */
class Sample
{
    public $message;

    public function __construct(Message $message)
    {
        /* 引数で受け取ったオブジェクトを、メンバプロパティに格納 */
        $this->message = $message;
    }

    /* メンバメソッドとしてインスタンス化されたMessageのメソッドを利用できる */
    public function run()
    {
        $this->message->send();
    }
}

/* Message: テキストを表示するだけのクラス */
class Message
{
    public function send()
    {
        echo ('<p>hello from Message</p>');
    }
}
