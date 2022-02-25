<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/* メール送信用のファサードと、Mailable クラスを読み込み */
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderedMail;

class SendOrderedMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /* プロパティを作成、ユーザー情報と商品情報を受け取れるようにする */
    public $product;
    public $user;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($product, $user)
    {
        $this->product = $product;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->product['email'])
            ->send(new OrderedMail($this->product, $this->user));
    }
}
