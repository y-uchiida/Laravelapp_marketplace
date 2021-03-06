<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\Image;
use App\Models\Product;

/* バリデーションロジックを切り離すため、UploadImageRequest を読み込み */
use App\Http\Requests\UploadImageRequest;


/* 共通処理として分離したアップロード処理を含むサービスクラスを読み込み */
use App\Services\ImageService;

class ImageController extends Controller
{
    /* コントローラ全体の共通初期化処理を、コンストラクタとして設定 */
    public function __construct()
    {
        /* ミドルウェアでownerとして認証されているかを確認 */
        $this->middleware('auth:owners');

        /* クロージャをカスタムミドルウェアとして追加、ImageController独自の初期化処理 */
        $this->middleware(function ($request, $next) {

            /* パスパラメータからimageを取り出してidとして扱う  */
            $id = $request->route()->parameter('image');
            /* id がnullではない場合、特定のカラムのデータを扱うページとしてデータを取得 */
            if(!is_null($id)){
            $imagesOwnerId = Image::findOrFail($id)->owner->id;
                $imageId = (int)$imagesOwnerId;
                if($imageId !== Auth::id()){
                    abort(404);
                }
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* アップロード日時が新しいものから表示(ページネーション20件ごと) */
        $images = Image::where('owner_id', Auth::id())
        ->orderBy('updated_at', 'desc')
        ->paginate(20);

        return view('owner.images.index',
        compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('owner.images.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UploadImageRequest $request)
    {
        /* 複数の画像がアップロードされてくるので、foreachで配列要素ごとに保存処理をする */
        $imageFiles = $request->file('files');
        if(!is_null($imageFiles)){
            foreach($imageFiles as $imageFile){
                $fileNameToStore = ImageService::upload($imageFile, 'products');
                Image::create([
                    'owner_id' => Auth::id(),
                    'filename' => $fileNameToStore
                ]);
            }
        }

        return redirect()
            ->route('owner.images.index')
            ->with(['message' => '画像登録を実施しました。', 'status' => 'info']);
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
        $image = Image::findOrFail($id);
        return (view('owner.images.edit', compact('image')));
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
        /* images テーブルの項目に対するバリデーション */
        $request->validate([
            'title' => 'string|max:50'
        ]);

        /* URLのパスパラメータから渡されたidの値を用いてレコードを取得 */
        $image = Image::findOrFail($id);
        $image->title = $request->title;

        $image->save();

        return (redirect()
            ->route('owner.images.index')
            ->with(['message' => '画像情報を更新しました', 'status' => 'info'])
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $image = Image::findOrFail($id);

        /* ImageはProductに対して外部キー制約が設定されているので、
         * 削除対象のimageが利用されているproducts テーブルを変更(nullに修正)する
         */
        $imageInProducts =
            Product::where('image1', $image->id)
                ->orWhere('image2', $image->id)
                ->orWhere('image3', $image->id)
                ->orWhere('image4', $image->id)
                ->get();

        /* レコードが取得できたら、削除対象の画像のid をnull に置き換える処理をeach() で順番に処理を行う */
        if ($imageInProducts){
            $imageInProducts->each(function($product) use($image){
                if ($product->image1 === $image->id){
                    $product->image1 = null;
                }
                if ($product->image2 === $image->id){
                    $product->image2 = null;
                }
                if ($product->image3 === $image->id){
                    $product->image3 = null;
                }
                if ($product->image4 === $image->id){
                    $product->image4 = null;
                }
                $product->save();
            });
        }

        /* 対象のファイルが格納されているパスを取得 */
        $filePath = "public/products/{$image->filename}";

        /* 対象ファイルの存在確認をし、あればdelete */
        if(Storage::exists($filePath)){
            Storage::delete($filePath);
        }

        $image->delete();

        return (redirect()
            ->route('owner.images.index')
            ->with(['message' => '画像を削除しました', 'status' => 'alert'])
        );
    }
}
