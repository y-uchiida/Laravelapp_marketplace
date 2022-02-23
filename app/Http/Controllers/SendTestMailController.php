<?php

namespace App\Http\Controllers;

/* メール送信のためのファサードを読み込み */
use Illuminate\Support\Facades\Mail;

/* 送信するメールの内容として、予め作成したMailableクラスを読み込み */
use App\Mail\TestMail;

/* メール送信をするjobクラスを読み込み */
use App\Jobs\SendTestMailJob;

class SendTestMailController extends Controller
{
    public function send_test_mail_sync()
    {
        $mail = new TestMail();
        Mail::to('test@mail.example.com') /* to() メソッドで送信先を設定 */
            ->send($mail); /* send() メソッドにMailable クラスのインスタンスを渡して送信 */

        return ('<p>test mail sent.</p> <p><a href="/">back to top</a></p>');
    }

    public function send_test_mail_async()
    {
        SendTestMailJob::dispatch(); /* メール送信処理のジョブをキューに追加 */
        return ('<p>mail send job appended to queue.</p> <p><a href="/">back to top</a></p>');
    }
}
