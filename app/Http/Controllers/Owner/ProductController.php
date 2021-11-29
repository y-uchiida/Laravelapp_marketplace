<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Image;
use App\Models\Owner;
use App\Models\PrimaryCategory;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{

    /* コントローラ全体の共通初期化処理を、コンストラクタとして設定 */
    public function __construct()
    {
        $this->middleware('auth:owners');
        $this->middleware(function ($request, $next) {
            /* URLのパスパラメータから、product_id を取得 */
            $id = $request->route()->parameter('product');

            if ($id !== null) {
                /* product_id が指定されていた場合、ログイン中のownerのIDと一致するかを判定 */
                /* まず、productのowner_id(外部キーの値)を取得 */
                $productsOwnerId = Product::findOrFail($id)->shop->owner->id;

                /* Authファサードから、ログイン中のidと一致するかを判定、false なら404エラーにする */
                if ($productsOwnerId !== Auth::id()) {
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
        // $products = Owner::findOrFail(Auth::id())->shop->product;

        /* Eagerロードで、shop, product, imageFirst のリレーション先レコードをあらかじめ取得しておく */
        $ownerInfo = Owner::with('shop.product.imageFirst')->find(Auth::id());

        // dd($ownerInfo);

        return (view('owner.products.index', compact('ownerInfo')));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /* ログイン中のowner のshop 情報を取得 */
        $shops = Shop::where('owner_id', Auth::id())
            ->select('id', 'name')->get();

        /* ログイン中のowner の image 情報を取得 */
        $images = Image::where('owner_id', Auth::id())
            ->select('id', 'title', 'filename')
            ->orderBy('updated_at', 'desc')
            ->get();

        /* category 情報は、option タグに一覧表示するため全件を取得しておく */
        $categories = PrimaryCategory::with('secondary')->get();

        return (view('owner.products.create', compact('shops', 'images', 'categories')));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        /* products テーブルと stock テーブルの2つのレコードを追加するので、トランザクションを利用する */
        try {
            DB::transaction(function () use ($request) {
                /* product のid をstoreに保存する必要があるので、まずproductの追加をする */
                $product = Product::create([
                    'name' => $request->name,
                    'information' => $request->information,
                    'price' => $request->price,
                    'sort_order' => $request->sort_order,
                    'shop_id' => $request->shop_id,
                    'secondary_category_id' => $request->category,
                    'image1' => $request->image1,
                    'image2' => $request->image2,
                    'image3' => $request->image3,
                    'image4' => $request->image4,
                    'is_selling' => $request->is_selling,
                ]);

                /* stock のレコードを作成 */
                Stock::create([
                    'product_id' => $product->id,
                    'type' => \Constant::STOCK_ADD, /* 新規在庫なので、入庫扱い(1)とする */
                    'quantity' => $request->quantity,
                ]);
            });

        } catch (Throwable $e) {
            /* 例外の内容をログに保存し、再度throwする */
            Log::error($e);
            throw ($e);
        }

        /* try-catch を抜けてきていればレコードの作成は完了しているので、あとはリダイレクトをする */
        return (redirect()->route('owner.products.index')->with(['message' => '商品を追加しました', 'status' => 'info']));
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
        $product = Product::findOrFail($id);

        /* 編集対象の商品から、quantity カラムの合計値を使って、現在の在庫数を取得 */
        $quantity = Stock::where('product_id', $product->id)->sum('quantity');

        $shops = Shop::where('owner_id', Auth::id())->get();

        $images = Image::where('owner_id', Auth::id())
            ->select('id', 'title', 'filename')
            ->orderBy('updated_at', 'desc')
            ->get();

        $categories = PrimaryCategory::with('secondary')->get();

        return (view('owner.products.edit', compact(
            'product', 'quantity', 'shops', 'images', 'categories'
        )));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        /* 在庫数に関するバリデーション */
        $request->validate([
            'current_quantity' => 'required|integer',
        ]);

        $product = Product::findOrFail($id);

        /* 編集対象の商品から、quantity カラムの合計値を使って、現在の在庫数を取得 */
        $quantity = Stock::where('product_id', $product->id)->sum('quantity');

        /* 編集操作中に、別のユーザーの操作などによってデータベース上の在庫数が変化していた場合は
         * 更新処理を行わずに編集画面へ戻す
         */
        if ($request->current_quantity !== $quantity) {
            $id = $request->route()->parameter('product');
            return (redirect()->route('owner.products.edit', ['product' => $id])
                    ->with([
                        'message' => '在庫数が変更されています。再度確認してください。',
                        'status' => 'alert',
                    ]));
        }

        /* データベース更新処理を実行
         * products テーブルと stock テーブルの2つのレコードを追加するので、トランザクションを利用する
         */
        try {
            DB::transaction(function () use ($request, $product) {

                $product->name = $request->name;
                $product->information = $request->information;
                $product->price = $request->price;
                $product->sort_order = $request->sort_order;
                $product->shop_id = $request->shop_id;
                $product->secondary_category_id = $request->category;
                $product->image1 = $request->image1;
                $product->image2 = $request->image2;
                $product->image3 = $request->image3;
                $product->image4 = $request->image4;
                $product->is_selling = $request->is_selling;

                /* まずproduct を保存する */
                $product->save();

                /* 入庫・出庫のどちらかによって、quantityの符号を設定 */
                $newQuantity = $request->type === \Constant::STOCK_ADD ? $request->quantity : $request->quantity * -1;

                /* Stock テーブルを更新 */
                Stock::create([
                    'product_id' => $product->id,
                    'type' => $request->type,
                    'quantity' => $newQuantity,
                ]);
            }, 2);
        } catch (Throwable $e) {
            /* 例外の内容をログに保存し、再度throwする */
            Log::error($e);
            throw $e;
        }

        /* try-catch を抜けてきていればレコードの作成は完了しているので、あとはリダイレクトをする */
        return (redirect()->route('owner.products.index')->with(['message' => '商品情報を更新しました', 'status' => 'info']));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::findOrFail($id)->delete();

        return (redirect()->route('owner.products.index')
            ->with([
                'message' => '商品を削除しました',
                'status' => 'alert'
            ]));
    }
}
