<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /* ルーティングで制御しているので良しとして、trueに変更 */
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        /* コントローラのアクションで$request->validate() メソッドを行う際の引数と同様に、
         * バリデーションの条件を連想配列形式で記述する
         */
        return [
            'image' => 'image|mimes:jpg,jpeg,png|max:2048',
            /* files のプリフィックス付きで入ってくる複数アップロードされたデータに対しても、同様のバリデーションをする */
            'files.*.image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'image' => '指定されたファイルが画像ではありません。',
            'mimes' => '指定された拡張子（jpg/jpeg/png）ではありません。',
            'max' => 'ファイルサイズは2MB以内にしてください。',
        ];
    }
}
