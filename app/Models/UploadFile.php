<?php

namespace App\Models;
use TimStorage;
use Illuminate\Database\Eloquent\Model;

class UploadFile extends Model
{
    protected $table = 'upload_files';

    protected $fillable = [
        'driver',
        'bucket',
        'path',
        'user_id',
        'title',
        'name',
        'extension',
        'size',
        'mime',
        'hash',
        'status',
    ];

    public function name()
    {
        if (! empty($this->title)) {
            if (empty($this->extension)) {
                return $this->title;
            }

            return $this->title.'.'.$this->extension;
        }

        return $this->name;
    }

    public function path($params = null, $expiration = null)
    {
        $path = $this->path;
        if (! empty($params)) {
            if (is_array($params)) {
                $path .= '?'.http_build_query($params);
            } else {
                $path .= $params;
            }
        }

        return TimStorage::disk($this->driver, $this->bucket)->url($path, $expiration);
    }

    public function downloadPath($attname = null)
    {
        $attname = $attname ?? $this->name();

        return $this->path(compact('attname'));
    }
}
