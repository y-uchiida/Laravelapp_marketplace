<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/* メール送信のためのファサードを読み込み */
use Illuminate\Support\Facades\Mail;

/* 送信するメールの内容として、予め作成したMailableクラスを読み込み */
use App\Mail\TestMail;

class SendTestMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mail = new TestMail();
        Mail::to('test@mail.example.com') /* to() メソッドで送信先を設定 */
            ->send($mail); /* send() メソッドにMailable クラスのインスタンスを渡して送信 */

    }
}
