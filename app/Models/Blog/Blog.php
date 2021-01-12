<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = 'blogs';

    protected $fillable = [
        'title',
        'content',
        'cover_url',
        'status'
    ];

    public function blog_tags()
    {
        return $this->belongsToMany('App\Models\Blog\BlogTag','blog_tag_relation','blog_id','tag_id');
    }


    public function scopeIntags($query, $tag_ids)
    {
        return $query->whereHas('blog_tags', function($q) use ($tag_ids){
            $q->whereIn('tag_id', $tag_ids);
        });
    }

    public function scopeStatus($query, $status = 1) {
        $query->where('status', $status);
    }
}
