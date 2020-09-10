<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    public function index()
    {
        $builder = Tag::query()->latest();
        request('name') && $builder->where('name', 'like', '%' . request('name') . '%');
        $tags = $builder->get();
        return view('tag', compact('tags'));
    }

    public function store()
    {
        $this->vld(['name' => 'required|unique:tags|max:8']);
        Tag::query()->create(['name' => request('name'), 'user_id' => uid()]);
        return $this->backOk();
    }

    public function remove()
    {
        $this->vld(['id' => 'required|exists:tags']);
        $tag = Tag::query()->find(request('id'));
        if ($tag->userId != uid()) {
            return $this->backErr('msg.forbidden');
        }
        if (DB::table('image_tag')->where('tag_id', request('id'))->exists()) {
            return $this->backErr('有使用了该标签的图片，无法删除');
        }
        $tag->delete();
        return $this->backOk();
    }
}