<?php

namespace App\Services;

/* ファイル保存用のファサード Storage を読み込み */
use Illuminate\Support\Facades\Storage;

/* アップロードされたファイルを編集(リサイズ)するため、InterventionImage を利用する */
use InterventionImage;

class ImageService
{
    /* 画像のアップロード処理(名前の設定とリサイズ)を共通化 */
    public static function upload($imageFile, $folderName)
    {
        /* 画像が配列で入ってくるか、そうでないかによって、取得方法を切り替える */
        $file = $imageFile;
        if(is_array($imageFile))
        {
          $file = $imageFile['image'];
        }

        /* InterventionImage を用いて画像を 1920 * 1080 にリサイズする */
        $fileName = uniqid(rand() . '_'); /* 1. ファイル名が一意な値になるように設定 */
        $extension = $file->extension(); /* 拡張子を取得 */
        $fileNameToStore = $fileName . '.' . $extension;
        $resizedImage = InterventionImage::make($file)->resize(1920, 1080)->encode();
        Storage::put("public/$folderName/" . $fileNameToStore, $resizedImage);

        return $fileNameToStore;
    }
}
