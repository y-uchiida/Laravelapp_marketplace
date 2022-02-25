<?php

namespace App\Constants;

class Common
{
    const STOCK_ADD = '1';
    const STOCK_REDUCE = '2';

    /* 商品一覧の並び順 */
    const ORDER_RECOMMEND = 0;
    const ORDER_HIGHER = 1;
    const ORDER_LOWER = 2;
    const ORDER_LATER = 3;
    const ORDER_OLDER = 4;

    const SORT_ORDER = [
        self::ORDER_RECOMMEND => ['value' => 'recommend', 'label' => 'おすすめ順'],
        self::ORDER_HIGHER => ['value' => 'higherPrice', 'label' => '価格の高い順'],
        self::ORDER_LOWER => ['value' => 'lowerPrice', 'label' => '価格の安い順'],
        self::ORDER_LATER => ['value' => 'later', 'label' => '新しい順'],
        self::ORDER_OLDER => ['value' => 'older', 'label' => '古い順']
    ];
}
