<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog\Blog;
use App\Models\Blog\BlogTagGroup;
use App\Models\Blog\BlogTagRelation;
use App\Http\Resources\PaginationCollection;

use App\Http\Requests\Api\Web\BlogRequest;

class BlogController extends Controller
{
    public function index(Request $request) {
        $title = $request->title;
        $tag_ids = $request->tag_id;
        $blog = Blog::status(1)
            ->with('blog_tags')
            ->having('title','like','%'.$title.'%')
            ->orderBy('id', 'desc');
        if($tag_ids) {
            $blog = $blog->intags($tag_ids);
        }
        $response = $blog->paginate($request->input('page_size', 10));
        return new PaginationCollection($response);
    }

    public function show(Request $request, $id) {
        $blog = Blog::with('blog_tags')->findOrFail($id);
        return $this->success($blog);
    }

    public function tags(Request $request) {
        $tags = BlogTagGroup::with('tags')
            ->orderBy('sort', 'asc')
            ->get();
        return $this->success($tags);
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


    // public function recommend(Request $request) {
    //     $limit = $request->limit ?: 10;
    //     $id = $request->id;
    //     $blog = Blog::orderBy('id', 'desc')->take($limit)->get();
    //     return $this->success($blog);
    // }
}

