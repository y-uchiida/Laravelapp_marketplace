<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Mail;
use App\Mail\ThanksMail;

class SendThanksMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /* プロパティを作成、ユーザー情報と商品情報を受け取れるようにする */
    public $products;
    public $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($products, $user)
    {
        $this->products = $products;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /* to() は、渡されたオブジェクトの中からメールアドレスの入ったものを探してくれるので、
         * userを指定しておけばOK
         */
        Mail::to($this->user)
            ->send(new ThanksMail($this->products, $this->user));
    }
}
