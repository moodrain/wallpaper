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
        $pager = $builder->paginate(50);
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

    public function save()
    {
        return $this->api([
            'id' => 'required|exists:images',
            'tags' => 'array',
            'tags.*' => 'required|exists:tags,id',
        ], function() {
            $image = Image::query()->where('user_id', uid())->find(request('id'));
            expIf(! $image, '图片不存在');
            $image->tags()->sync(request('tags'));
            $image->save();
            return rs();
        });
    }

    public function remove(OssService $oss)
    {
        return $this->api(['id' => 'required'], function() use ($oss) {
            $image = Image::query()->where('user_id', uid())->find(request('id'));
            expIf(! $image, '图片不存在');
            expIf(DB::table('home_image')->where('image_id', $image->id)->exists(), '有使用了该图片的桌面，无法删除');
            $image->delete();
            if (! Image::query()->where('md5', $image->md5)->exists()) {
                $oss->delete('moodrain', $image->path);
            }
            return rs();
        });
    }
}