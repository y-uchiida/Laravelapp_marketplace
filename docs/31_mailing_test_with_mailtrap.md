# Mailtrap を利用してメール送信のテストを行う

## mailtrapに登録する
Googleアカウント、またはGithubアカウントで登録できる

## .envファイルを書き換える
mailtrapの「SMTP setting」の画面に設定項目があるので、それに沿って.envを修正する  
修正したら、.envのキャッシュをクリアしておく
```
php artisan config:cache
```

## メール関連の設定ファイル
`config/mail.php` に記述がある  
基本的には、env() ヘルパ関数を用いて、.env で設定した内容がそのまま使われるようになっている  

## Mailable クラスで、メールの送信内容を規定する
購入確認用のメール、本人確認用のメールなど、送信するメールの内容ごとに`Mailable`クラスを作成する  
Mailable クラスのbuild() メソッド内で、メールの送信者や本文情報などを設定していく  
artisan コマンドからひな形を作成する  
```bash
# Mailable クラス app/Mail/TestMail.php が生成される
$ sail artisan make:mail TestMail
Mail created successfully.

```

### build()メソッド内で利用できる設定用メソッド
Mailable クラスのbuild() メソッド内で、$this オブジェクトに対してメソッドチェインで利用する  
- from アドレスを設定する... from('email address', 'sender name')  
- 件名を設定する... subject('subject string')  
- 利用するview を指定する... view('blade file path')  
- プレーンテキストの本文内容を指定する... text('plain text file path')  

### Mailable クラスの設定例
TestMail.php を設定してみる例
```php
<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    /* ... 中略 ... */

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
```

## 送信設定
コントローラで、`mail` ファサードを利用する  
`to()` メソッドに送信する内容を設定し、`send()` メソッドをチェインして送信する

### コントローラでのMail ファサードの利用例
```php
<?php

/* app/Http/Controllers/SendTestMailController.php */

namespace App\Http\Controllers;

/* メール送信のためのファサードを読み込み */
use Illuminate\Support\Facades\Mail;

/* 送信するメールの内容として、予め作成したMailableクラスを読み込み */
use App\Mail\TestMail;

class SendTestMailController extends Controller
{
    public function index()
    {
        $mail = new TestMail();
        Mail::to('test@mail.example.com') /* to() メソッドで送信先を設定 */
            ->send($mail); /* send() メソッドにMailable クラスのインスタンスを渡して送信 */

        return ('<p>test mail sent.</p> <p><a href="/">back to top</a></p>');
    }
}
```
