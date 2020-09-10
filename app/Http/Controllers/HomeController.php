<?php

namespace App\Http\Controllers;

use App\Models\Home;
use App\Models\Image;
use App\Services\HomeService;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{
    public function index()
    {
        $homes = Home::query()->where('user_id', uid())->withCount('images')->get();
        return view('home.index', compact('homes'));
    }

    public function edit(Home $home)
    {
        $home->load('images.tags');
        $home->images->map(function($e) { $e->append('tagIds'); });
        return view('home.edit', compact('home'));
    }

    public function store()
    {
        $this->vld(['name' => ['required', Rule::unique('homes')->where('user_id', uid())]]);
        $home = new Home([
            'userId' => uid(),
            'name' => request('name'),
        ]);
        $home->save();
        $home->token = $this->homeSrv->genToken($home->userId, $home->id);
        $home->save();
        return $this->backOk();
    }

    public function save()
    {
        $this->vld([
            'id' => ['required', Rule::exists('homes')->where('user_id', uid())],
            'name' => ['required', Rule::unique('homes')->where('user_id', uid())->ignore(request('id'))],
            'images' => 'array',
            'images.*' => ['required', Rule::exists('images', 'id')->where('user_id', uid())],
        ]);
        $home = Home::query()->find(request('id'));
        $home->fill(request()->only('home'));
        $home->save();
        $home->images()->sync(request('images'));
        return $this->backOk();
    }

    public function remove()
    {
        $this->vld(['id' => ['required', Rule::exists('homes')->where('user_id', uid())]]);
        Home::query()->where('id', request('id'))->delete();
        return $this->backOk();
    }

    public function addImage()
    {
        return $this->api([
            'homeId' => 'required|exists:homes,id',
            'imageId' => 'required|exists:images,id'
        ], function() {
            $home = Home::query()->where('user_id', uid())->find(request('homeId'));
            $image = Image::query()->where('user_id', uid())->find(request('imageId'));
            expIf(! $home || ! $image, '桌面或图片不存在');
            $home->images()->syncWithoutDetaching([request('imageId')]);
            return rs();
        });
    }

    public function home()
    {
        return $this->api(['token' => 'required'], function() {
            $home = Home::query()->with('images')->where('token', request('token'))->first();
            expIf(! $home, '找不到桌面');
            expIf($home->images->isEmpty(), '该桌面没有图片');
            return rs($home->images);
        });
    }

    private $homeSrv;

    public function __construct(HomeService $homeSrv)
    {
        parent::__construct();
        $this->homeSrv = $homeSrv;
    }
}