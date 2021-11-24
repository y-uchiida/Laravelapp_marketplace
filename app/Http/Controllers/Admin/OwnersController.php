<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OwnersController extends Controller
{

    /* コントローラのオブジェクトのコンストラクタ
     * ここでもミドルウェアを設定することができる
     */
    public function __counstruct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* 一覧表示するカラムだけをselect()で指定し、paginate()でページネーション取得 */
        $owners = Owner::select('id', 'name', 'email', 'created_at')->paginate(3);
        return view('admin.owners.index', compact('owners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.owners.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /* バリデーションを実施 */
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:owners',
            'password' => 'required|string|confirmed|min:8',
        ]);

        Owner::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('admin.owners.index')
            ->with([
                'status' => 'info',
                'message' => "オーナー {$request->name} を新規登録しました"
            ]);
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
        /* findOrFail() メソッドで、指定のプライマリキーの値のレコードを取得する
         * 存在しないキーだった場合、404エラーを返す(存在チェックをif でやらなくていいので楽    )
         */
        $owner = Owner::findOrFail($id);

        return view('admin.owners.edit', compact('owner'));
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
        /* バリデーションを実施 */
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:owners',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $owner = Owner::findOrFail($id);
        $owner->name = $request->name;
        $owner->email = $request->email;
        $owner->password = Hash::make($request->password);
        $owner->save();

        return redirect()
        ->route('admin.owners.index')
        ->with([
            'status' => 'info',
            'message' => 'オーナー情報を更新しました',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /* 指定のidでオブジェクトを取得し、それを削除する */
        $owner = Owner::findOrFail($id);
        $owner_name = $owner->name;
        $owner->delete();

        return redirect()
        ->route('admin.owners.index')
        ->with([
            'status' => 'alert',
            'message' => "オーナー {$owner_name} を削除しました"
        ]);
    }

    /* 期限切れオーナー情報を一覧表示する */
    public function expiredOwnerIndex(){
        /* onlyTrashed() で、ソフトデリートされた(deleted_at に値が入っている)レコードに絞り込みする */
        $expiredOwners = Owner::onlyTrashed()->get();
        return view('admin.expired-owners', compact('expiredOwners'));
    }

    /* 期限切れオーナーを、物理削除する */
    public function expiredOwnerDestroy($id){
        /* onlyTrashed()のチェインでfindOrFail() を記述するとソフトデリートされたレコードを対象にデータを取得できる */
        Owner::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('admin.expired-owners.index');
    }
}
