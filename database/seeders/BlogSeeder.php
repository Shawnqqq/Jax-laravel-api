<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blog\Blog;
use App\Models\Blog\BlogTag;
use App\Models\Blog\BlogTagGroup;
use App\Models\Blog\BlogTagRelation;


class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BlogTagGroup::updateOrCreate([ 'name' => '前端', 'sort' => '1']);
        BlogTag::updateOrCreate([
            'group_id' => '1',
            'name' => 'Html' ,
            'sort' => '1',
            'status' => 1
        ]);
        Blog::updateOrCreate([
            'title' => '你好，世界',
            'content' => '<p>Hello,World</p>',
            'status' => 1
        ]);
        BlogTagRelation::updateOrCreate([ 'blog_id' => 1, 'tag_id' => 1]);
    }
}
