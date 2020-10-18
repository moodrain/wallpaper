<?php

namespace App\Http\Controllers;

use App\Models\Home;
use App\Models\Image;
use App\Services\OssService;
use Illuminate\Support\Facades\DB;

class ImageController extends Controller
{
    public function index()
    {
        $builder = Image::query()->where('user_id', uid())->latest('updated_at')->with(['tags']);
        request('tag') && $builder->whereHas('tag', function($q) {
            $q->where('tag_id', request('tag'));
        });
        request('name') && $builder->where('name', 'like', '%' . request('name') . '%');
        $pager = $builder->paginate(200);
        $pager->getCollection()->append('tagIds');
        $homes = Home::query()->where('user_id', uid())->latest()->get(['id', 'name']);
        return view('index', compact('pager', 'homes'));
    }

    public function upload(OssService $oss)
    {
        return $this->api(['file' => 'required|file'], function() use ($oss) {
            $file = request()->file('file');
            expIf($file->getSize() > 10 * 1024 * 1024, '图片大小不能超过 10 M');
            $content = file_get_contents($file->getRealPath());
            $md5 = md5($content);
            $img = Image::query()->firstOrNew(['user_id' => uid(), 'md5' => $md5], ['size' => $file->getSize() / 1024]);
            if (! Image::query()->where('md5', $md5)->exists()) {
                $oss->put('moodrain', $img->path, $content);
            }
            $img->save();
            ! $img->isDirty() && $img->touch();
            $tags = data_get($img->tags, '*.id');
            unset($img->tags);
            $img->tags = $tags;
            return rs($img);
        });
    }

    public function tag()
    {
        return $this->api([
            'id' => 'exists:images',
            'ids' => 'array|exists:images,id',
            'tags' => 'array',
            'tags.*' => 'required|exists:tags,id',
        ], function() {
            $ids = array_unique(array_merge(request('id') ? [request('id')] : [], request('ids', [])));
            $images = Image::query()->where('user_id', uid())->whereIn('id', $ids)->get();
            expIf($images->count() != count($ids), '图片不存在');
            foreach($images as $image) {
                $image->tags()->sync(request('tags'));
            }
            return rs();
        });
    }

    public function remove(OssService $oss)
    {
        return $this->api(['id' => '', 'ids' => 'array'], function() use ($oss) {
            $ids = array_unique(array_merge(request('id') ? [request('id')] : [], request('ids', [])));
            $images = Image::query()->where('user_id', uid())->whereIn('id', $ids)->get();
            expIf($images->count() != count($ids), '图片不存在');
            $homeExists = Home::query()->where('user_id', uid())->whereHas('images', function($q) use ($ids) {
                $q->whereIn('image_id', $ids);
            })->exists();
            expIf($homeExists, '有使用了该图片的桌面，无法删除');
            Image::query()->whereIn('id', $ids)->delete();
            foreach($images as $image) {
                if (! Image::query()->where('md5', $image->md5)->exists()) {
                    $oss->delete('moodrain', $image->path);
                }
            }
            return rs();
        });
    }
}