<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\UploadFile;
use App\Utils\Qiniu;

class FileController extends Controller
{
    public function qiniuUploadToken(Request $request) {
        $validator = Validator::make($request->all(), [
            'file_name' => 'required|string',
            'bucket' => 'string',
            'space' => 'string',
            'folder' => 'string'
        ]);
        $error = $validator->errors();
        if($error->first()) {
            return $this->error(1, $error->first());
        }

        $qiniu = new Qiniu();
        $uploadToken = $qiniu->uploadToken($request->file_name, $request->space, $request->folder);
        return $this->success($uploadToken);
    }

    public function qiniuFileStore(Request $request) {
        $validator = Validator::make($request->all(), [
            'file_name' => 'required|string',
            'path' => 'required|string',
            'bucket' => 'string',
            'title' => 'string'
        ]);
        $error = $validator->errors();
        if($error->first()) {
            return $this->error(1, $error->first());
        }

        $bucket = $request->input('bucket');
        $qiniu = new Qiniu($bucket);
        $bucket = $qiniu->bucketDefaultName($bucket);
        $path = $request->input('path');
        $name = $request->input('file_name');
        $title = $request->input('title');
        $extension = pathinfo($name, PATHINFO_EXTENSION);

        $f = UploadFile::create([
            'driver' => 'qiniu',
            'bucket' => $bucket,
            'path' => $path,
            'user_id' => null,
            'name' => $name,
            'title' => $title,
            'extension' => $extension,
            'status' => 1,
        ]);

        return $this->success([
            'id' => $f->id,
            'path' => $qiniu->url($path, 3600),
        ], '上传成功');
    }
}
