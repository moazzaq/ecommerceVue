<?php
namespace App\Http\Controllers\Admin;

use App\DataTables\WeightDatatable;
use App\Http\Controllers\Controller;
use App\Weight;
use Illuminate\Http\Request;
use Storage;

class WeightsController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(WeightDatatable $weight) {
        return $weight->render('admin.weights.index', ['title' => trans('admin.weights')]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin.weights.create', ['title' => trans('admin.add')]);
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
                'title_ar'      => 'required',
                'title_en'      => 'required',
            ], [], [
                'title_ar'      => trans('admin.title_ar'),
                'title_en'      => trans('admin.title_en'),
            ]);

        
        Weight::create($data);
        session()->flash('success', trans('admin.record_added'));
        return redirect(aurl('weights'));
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
        $weight = Weight::find($id);
        $title    = trans('admin.edit');
        return view('admin.weights.edit', compact('weight', 'title'));
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
                'title_ar'      => 'required',
                'title_en'      => 'required',
            ], [], [
                'title_ar'      => trans('admin.title_ar'),
                'title_en'      => trans('admin.title_en'),
            ]);

        Weight::where('id', $id)->update($data);
        session()->flash('success', trans('admin.updated_record'));
        return redirect(aurl('weights'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $weights = Weight::find($id);
        Storage::delete($weights->logo);
        $weights->delete();
        session()->flash('success', trans('admin.deleted_record'));
        return redirect(aurl('weights'));
    }

    public function multi_delete() {
        if (is_array(request('item'))) {
            foreach (request('item') as $id) {
                $weights = Weight::find($id);
                Storage::delete($weights->logo);
                $weights->delete();
            }
        } else {
            $weights = Weight::find(request('item'));
            Storage::delete($weights->logo);
            $weights->delete();
        }
        session()->flash('success', trans('admin.deleted_record'));
        return redirect(aurl('weights'));
    }
}
