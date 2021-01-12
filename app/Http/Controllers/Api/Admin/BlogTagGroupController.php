<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog\BlogTag;
use App\Models\Blog\BlogTagGroup;

use App\Http\Requests\Api\Admin\Blog\BlogTagGroupRequest;
use App\Http\Resources\PaginationCollection;

class BlogTagGroupController extends Controller
{
    public function index(BlogTagGroupRequest $request) {
        $name = $request->name;
        $group = BlogTagGroup::with('tags')
            ->where('name','like','%'.$name.'%')
            ->orderBy('sort', 'asc')
            ->get();
        return $this->success($group);
    }

    public function store(BlogTagGroupRequest $request) {
        $count = BlogTagGroup::count();
        $data = $request->validated();
        $data['sort'] = $count + 1;

        $group = BlogTagGroup::create($data);
        return $this->success([ 'id' => $group->id ],'类型创建成功');
    }

    public function update(BlogTagGroupRequest $request, $id) {
        $group = BlogTagGroup::findOrFail($id);
        $data = $request->validated();
        $group->update($data);
        return $this->success(null, '类型编辑完成');
    }

    public function destroy($id) {
        $group = BlogTagGroup::findOrFail($id);
        $flag = BlogTag::where('group_id',$group->id)->exists();
        if ($flag) {
            return $this->error(1, '类型已经被分类引用，无法删除');
        }
        $group->delete();
        return $this->success(null, '类型删除成功');
    }

    public function sort(BlogTagGroupRequest $request) {
        $sort_array = $request->sort;
        foreach ($sort_array as $key => $id) {
            BlogTagGroup::where('id',$id)->update(['sort' => $key + 1]);
        }
        return $this->success(null, '排序更新成功');
    }
}
