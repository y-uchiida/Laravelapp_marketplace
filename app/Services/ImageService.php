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
        /* InterventionImage を用いて画僧を 1920 * 1080 にリサイズする */
        $fileName = uniqid(rand() . '_'); /* 1. ファイル名が一意な値になるように設定 */
        $extension = $imageFile->extension(); /* 拡張子を取得 */
        $fileNameToStore = $fileName . '.' . $extension;
        $resizedImage = InterventionImage::make($imageFile)->resize(1920, 1080)->encode();
        Storage::put('public/shops/' . $fileNameToStore, $resizedImage);

        return $fileNameToStore;
    }
}
