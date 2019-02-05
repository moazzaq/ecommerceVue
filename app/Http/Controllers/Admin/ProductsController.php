<?php
namespace App\Http\Controllers\Admin;
use App\DataTables\ProductsDatatable;
use App\Http\Controllers\Controller;
use App\Product;
use App\Size;
use App\Weight;
use Illuminate\Http\Request;
use Storage;
class ProductsController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductsDatatable $product) {
        return $product->render('admin.Products.index', ['title' => trans('admin.products')]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $product = Product::create(['title' => '']);
        if (!empty($product)){
            return redirect(aurl('products/'.$product->id.'/edit'));
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store() {
        $data = $this->validate(request(),
            [
                'product_name_ar' => 'required',
                'product_name_en' => 'required',
                'mob'             => 'required',
                'code'            => 'required',
                'logo'            => 'required|'.v_image(),
            ], [], [
                'product_name_ar' => trans('admin.product_name_ar'),
                'product_name_en' => trans('admin.product_name_en'),
                'mob'             => trans('admin.mob'),
                'code'            => trans('admin.code'),
                'logo'            => trans('admin.logo'),
            ]);
        if (request()->hasFile('logo')) {
            $data['logo'] = up()->upload([
                'file'        => 'logo',
                'path'        => 'products',
                'upload_type' => 'single',
                'delete_file' => '',
            ]);
        }
        Product::create($data);
        session()->flash('success', trans('admin.record_added'));
        return redirect(aurl('products'));
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $product = Product::find($id);
        return view('admin.products.product', ['title' =>
            trans('admin.create_or_edit_products',['title'=>$product->title])
            ,'product'=>$product]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r, $id) {
        $data = $this->validate(request(),
            [
                'product_name_ar' => 'required',
                'product_name_en' => 'required',
                'mob'             => 'required',
                'code'            => 'required',
                'logo'            => 'sometimes|nullable|'.v_image(),
            ], [], [
                'product_name_ar' => trans('admin.product_name_ar'),
                'product_name_en' => trans('admin.product_name_en'),
                'mob'             => trans('admin.mob'),
                'code'            => trans('admin.code'),
                'logo'            => trans('admin.logo'),
            ]);
        if (request()->hasFile('logo')) {
            $data['logo'] = up()->upload([
                'file'        => 'logo',
                'path'        => 'products',
                'upload_type' => 'single',
                'delete_file' => Product::find($id)->logo,
            ]);
        }
        Product::where('id', $id)->update($data);
        session()->flash('success', trans('admin.updated_record'));
        return redirect(aurl('products'));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $products = Product::find($id);
        Storage::delete($products->logo);
        $products->delete();
        session()->flash('success', trans('admin.deleted_record'));
        return redirect(aurl('products'));
    }
    public function multi_delete() {
        if (is_array(request('item'))) {
            foreach (request('item') as $id) {
                $products = Product::find($id);
                Storage::delete($products->logo);
                $products->delete();
            }
        } else {
            $products = Product::find(request('item'));
            Storage::delete($products->logo);
            $products->delete();
        }
        session()->flash('success', trans('admin.deleted_record'));
        return redirect(aurl('products'));
    }
    public function upload_file($id){
       // $product = Product::find($id);
        if (request()->hasFile('file')) {
           $fid = up()->upload([
                'file'        => 'file',
                'path'        => 'products/'.$id,
                'upload_type' => 'files',
                'file_type' => 'product',
                'relation_id' => $id,
            ]);
            return response(['status'=>true,'id'=>$fid],200);
        }

    }
    public function delete_file(){
        if (request()->has('id')) {
            return up()->delete(request('id'));
        }

    }
    public function update_product($id)
    {
        $product =  Product::where('id',$id)->update([
           'photo' => up()->upload([
               'file' => 'file',
               'path' => 'products/' . $id,
               'upload_type' => 'single',
               'delete_file' => '',
        ])
        ]);
        return response(['status'=>true,'photo'=>$product->photo],200);
    }
    public function delete_main_imageProduct($id){
        $product =  Product::find($id);
        Storage::delete($product->photo);
        $product->photo = null;
        $product->save();
    }
    public function prepare_size_weight(){
        if (request()->ajax() && request()->has('dep_id')){
            $sizes = Size::where('department_id',request('dep_id'))->pluck('name_'.session('lang'),'id');
            $weights = Weight::pluck('name_'.session('lang'),'id');
            return view('admin.products.ajax.size_weight',compact('sizes','weights'))->render();
        }else{
            return 'الرجاء قم باختيار قسم';
        }
    }

}