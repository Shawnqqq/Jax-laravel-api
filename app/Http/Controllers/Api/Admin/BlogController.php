<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog\Blog;
use App\Models\Blog\BlogTag;
use App\Models\Blog\BlogTagRelation;

use App\Http\Requests\Api\Admin\Blog\BlogRequest;
use App\Http\Resources\PaginationCollection;

class BlogController extends Controller
{
    public function index(BlogRequest $request) {
        $title = $request->title;
        $tag_id = $request->tag_id;
        $blog = Blog::with('blog_tags')
            ->having('title','like','%'.$title.'%')
            ->orderBy('id', 'desc');

        if($tag_id) {
            $blog = $blog->intags($tag_id);
        }
        $response = $blog->paginate($request->input('page_size', 10));
        return new PaginationCollection($response);
    }

    public function show(BlogRequest $request, $id) {
        $blog = Blog::with('blog_tags')->findOrFail($id);
        return $this->success($blog);
    }

    public function store(BlogRequest $request) {
        $tag_id = $request->tag_id;
        $data = $request->validated();
        $blog = Blog::create($data);

        $array = [];
        foreach ($tag_id as $id) {
            $value = ['blog_id' => $blog->id , 'tag_id' => $id];
            array_push($array,$value);
        }
        BlogTagRelation::insert($array);
        return $this->success([ 'id' => $blog->id ], '博客创建成功');
    }

    public function update(BlogRequest $request, $id) {
        $blog = Blog::findOrFail($id);
        $tag_id = $request->tag_id;
        $data = $request->validated();
        $blog->update($data);

        $relation = BlogTagRelation::where('blog_id', '=', $id)->delete();
        $array = [];
        foreach ($tag_id as $tag) {
            $value = ['blog_id' => $id , 'tag_id' => $tag];
            array_push($array,$value);
        }
        BlogTagRelation::insert($array);
        return $this->success(null ,'博客编辑成功');
    }

    public function destroy($id) {
        $blog = Blog::findOrFail($id);
        $relation = BlogTagRelation::where('blog_id', '=', $id)->delete();
        $blog->delete();

        return $this->success(null, '博客删除成功');
    }
}
