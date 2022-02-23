<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from('sender@laravelapp.com', 'Laravel App Mailer') /* 送信者を設定 */
            ->subject('this is test mail from Laravel app') /* メール件名を設定 */
            ->view('emails.test_mail'); /* 本文で利用するview ファイルを指定(resources/views/emails/test_mail.blade.php) */
    }
}
