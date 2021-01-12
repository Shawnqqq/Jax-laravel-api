<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;

class BlogTagGroup extends Model
{
    protected $table = 'blog_tag_groups';

    protected $fillable = [
        'name',
        'sort'
    ];

    public function tags($status = 1)
    {
        return $this->hasMany('App\Models\Blog\BlogTag','group_id','id')->orderBy('sort', 'asc')->where('status', $status);
    }
}