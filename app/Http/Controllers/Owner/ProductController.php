<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Image;
use App\Models\Product;
use App\Models\SecondaryCategory;
use App\Models\Owner;

class ProductController extends Controller
{

    /* コントローラ全体の共通初期化処理を、コンストラクタとして設定 */
    public function __construct()
    {
        $this->middleware('auth:owners');
        $this->middleware(function($request, $next){
            /* URLのパスパラメータから、product_id を取得 */
            $id = $request->route()->parameter('product');

            if($id !== null){
                /* product_id が指定されていた場合、ログイン中のownerのIDと一致するかを判定 */
                /* まず、productのowner_id(外部キーの値)を取得 */
                $productsOwnerId = Product::findOrFail($id)->shop->owner->id;

                /* Authファサードから、ログイン中のidと一致するかを判定、false なら404エラーにする */
                if ($productsOwnerId !== Auth::id()){
                    abort(404);
                }
            }
            return ($next($request));
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* Eager ロードなしの場合 */
        $products = Owner::findOrFail(Auth::id())->shop->product;

        /* Eagerロードで、shop, product, imageFirst のリレーション先レコードをあらかじめ取得しておく */
        $ownerInfo = Owner::with('shop.product.imageFirst')->find(Auth::id());

        return (view('owner.products.index', compact('ownerInfo')));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
