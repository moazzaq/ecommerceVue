<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\File;

class UploadController extends Controller
{
    /*
 'name',
 'size',
 'file',
 'path',
 'full_file',
 'mime_type',
 'file_type',
 'relation_id',
  */
    public function delete($id){
        $file = File::find($id);
        if (!empty($file)){
            Storage::delete($file->full_file);
            $file->delete();
        }
    }
    public function upload($data = []) {
        if (in_array('new_name', $data)) {
            $new_name = $data['new_name'] === null?time():$data['new_name'];
        }
        if (request()->hasFile($data['file']) && $data['upload_type'] == 'single') {
            Storage::has($data['delete_file'])?Storage::delete($data['delete_file']):'';
            return request()->file($data['file'])->store($data['path']);
        }elseif (request()->hasFile($data['file']) && $data['upload_type'] == 'files') {

            $file = request()->file($data['file']);
            $file->store($data['path']);
            $size = $file->getSize();
            $name = $file->getClientOriginalName();
            $mime_type = $file->getMimeType();
            $hash_name = $file->hashName();
            $add = File::create([
                'name' => $name,
                'size' => $size,
                'file' => $hash_name,
                'path' => $data['path'],
                'full_file' => $data['path'] .'/'. $hash_name,
                'mime_type' => $mime_type,
                'file_type' => $data['file_type'],
                'relation_id' => $data['relation_id'],
            ]);
//            return $data['path'] .'/'. $hash_name;
            return $add->id;
        }
    }
}
