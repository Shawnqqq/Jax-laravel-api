<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog\BlogTag;
use App\Models\Blog\BlogTagGroup;
use App\Models\Blog\Blog;
use App\Models\Blog\BlogTagRelation;

use App\Http\Requests\Api\Admin\Blog\BlogTagsRequest;
use App\Http\Resources\PaginationCollection;

class BlogTagController extends Controller
{
    public function index(BlogTagsRequest $request) {
        $name = $request->name;
        $data = [];
        $group_id = $request->group_id;
        if($group_id) {
            $data['group_id'] = $group_id;
        }
        $tags = BlogTag::with('group')
            ->having('name','like','%'.$name.'%')
            ->orWhere($data)
            ->orderBy('sort', 'asc')
            ->get();
        return $this->success($tags);
    }

    public function store(BlogTagsRequest $request) {
        $group_id = $request->group_id;
        $flag = BlogTagGroup::findOrFail($group_id);

        $count = BlogTag::where('group_id',$group_id)->count();
        $data = $request->validated();
        $data['sort'] = $count + 1;

        $blog = BlogTag::create($data);
        return $this->success([ 'id' => $blog->id],'标签创建成功');
    }
    
    public function status(BlogTagsRequest $request, $id) {
        $count = BlogTag::where(['id' => $id, 'status' => 0])->count();
        if($count){
            BlogTag::where(['id' => $id, 'status' => 0])->update(['status' => 1]);
        } else {
            BlogTag::where(['id' => $id, 'status' => 1])->update(['status' => 0]);
        }
        return $this->success(null,'修改标签状态成功');
    }

    public function update(BlogTagsRequest $request, $id) {
        $tags = BlogTag::findOrFail($id);
        $group_id = $request->group_id;
        $flag = BlogTagGroup::findOrFail($group_id);
        $data = $request->validated();
        $tags->update($data);

        return $this->success(null , '标签编辑成功');
    }

    public function destroy($id) {
        $tags = BlogTag::findOrFail($id);
        $flag = BlogTagRelation::where('tag_id', $id)->exists();
        if ($flag) {
            return $this->error(1, '标签已经被文章引用，无法删除');
        }
        $tags->delete();

        return $this->success(null, '标签删除成功');
    }

    public function sort(BlogTagsRequest $request) {
        $sort_array = $request->sort;
        foreach ($sort_array as $key => $id) {
            BlogTag::where('id',$id)->update(['sort' => $key + 1]);
        }
        return $this->success(null, '更新成功');
    }
}