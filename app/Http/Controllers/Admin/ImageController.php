<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\ImageRequest;
use Illuminate\Support\Str;
use Storage;

class ImageController
{
    public function upload(ImageRequest $request) {
        $file = $request->file('image');
        $name = Str::random(10);
        $url = Storage::putFileAs('/images',$file,$name.'.'.$file->extension()); 
        return [ 'url'=>env('APP_URL').'/'.$url];
        
    }
}
