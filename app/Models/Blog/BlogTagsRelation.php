<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;

class BlogTagRelation extends Model
{
    protected $table = 'blog_tag_relation';

    protected $fillable = [
        'blog_id',
        'tag_id'
    ];

    const CREATED_AT = null;
    const UPDATED_AT = null;
}
