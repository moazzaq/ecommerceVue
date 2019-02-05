<?php
namespace App\Http\Controllers\Admin;
use App\DataTables\ShippingsDatatable;
use App\Http\Controllers\Controller;
use App\shipping;
use Illuminate\Http\Request;
use Storage;

class ShippingsController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ShippingsDatatable $trade) {
        return $trade->render('admin.shippings.index', ['title' => trans('admin.manufacturers')]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin.shippings.create', ['title' => trans('admin.add')]);
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
                'name_ar'      => 'required',
                'name_en'      => 'required',
                'user_id'      => 'required|numeric',
                'lat'          => 'sometimes|nullable',
                'lng'          => 'sometimes|nullable',
                'icon'         => 'sometimes|nullable|'.v_image(),
            ], [], [
                'name_ar'      => trans('admin.name_ar'),
                'name_en'      => trans('admin.name_en'),
                'user_id'      => trans('admin.user_id'),
                'lat'          => trans('admin.lat'),
                'lng'          => trans('admin.lng'),
                'icon'         => trans('admin.icon'),
            ]);

        if (request()->hasFile('icon')) {
            $data['icon'] = up()->upload([
                'file'        => 'icon',
                'path'        => 'shippings',
                'upload_type' => 'single',
                'delete_file' => '',
            ]);
        }

        shipping::create($data);
        session()->flash('success', trans('admin.record_added'));
        return redirect(aurl('shippings'));
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
        $shipping = shipping::find($id);
        $title    = trans('admin.edit');
        return view('admin.shippings.edit', compact('shipping', 'title'));
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
                'name_ar'      => 'required',
                'name_en'      => 'required',
                'user_id'      => 'required|numeric',
                'lat'          => 'sometimes|nullable',
                'lng'          => 'sometimes|nullable',
                'icon'         => 'sometimes|nullable|'.v_image(),
            ], [], [
                'name_ar'      => trans('admin.name_ar'),
                'name_en'      => trans('admin.name_en'),
                'user_id'      => trans('admin.user_id'),
                'lat'          => trans('admin.lat'),
                'lng'          => trans('admin.lng'),
                'icon'         => trans('admin.icon'),
            ]);

        if (request()->hasFile('icon')) {
            $data['icon'] = up()->upload([
                'file'        => 'icon',
                'path'        => 'shippings',
                'upload_type' => 'single',
                'delete_file' => shipping::find($id)->icon,
            ]);
        }

        shipping::where('id', $id)->update($data);
        session()->flash('success', trans('admin.updated_record'));
        return redirect(aurl('shippings'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $shippings = shipping::find($id);
        Storage::delete($shippings->logo);
        $shippings->delete();
        session()->flash('success', trans('admin.deleted_record'));
        return redirect(aurl('shippings'));
    }

    public function multi_delete() {
        if (is_array(request('item'))) {
            foreach (request('item') as $id) {
                $shippings = shipping::find($id);
                Storage::delete($shippings->logo);
                $shippings->delete();
            }
        } else {
            $shippings = shipping::find(request('item'));
            Storage::delete($shippings->logo);
            $shippings->delete();
        }
        session()->flash('success', trans('admin.deleted_record'));
        return redirect(aurl('shippings'));
    }
}
