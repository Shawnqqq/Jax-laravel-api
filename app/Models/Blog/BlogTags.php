<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;
use App\Models\Blog\BlogType;

class BlogTag extends Model
{
    protected $table = 'blog_tags';

    protected $fillable = [
        'name',
        'group_id',
        'status',
        'sort'
    ];

    public function group()
    {
        return $this->belongsTo('App\Models\Blog\BlogTagGroup','group_id');
    }

    public function blog()
    {
        return $this->belongsToMany('App\Models\Blog\BlogTag','blog_tag_relation','tag_id','blog_id');
    }
}