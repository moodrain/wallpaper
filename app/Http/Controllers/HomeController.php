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
        $homes->makeVisible('token');
        return view('home.index', compact('homes'));
    }

    public function edit(Home $home)
    {
        $home->load('images.tags');
        $home->images->makeVisible('pivot');
        dj($home);
        $home->images = $home->images->sortByDesc('pivot.created_at')->values();
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
        return $this->directOk('/home');
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
        return $this->directOk('/home');
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
            'homeId' => request('all') ? '' : 'required|exists:homes,id',
            'imageIds' => 'required|array|exists:images,id',
            'all' => 'bool',
        ], function() {
            $images = Image::query()->where('user_id', uid())->whereIn('id', request('imageIds'))->get();
            expIf($images->count() != count(request('imageIds')), '图片不存在');
            $homeBuilder = Home::query()->where('user_id', uid());
            ! request('all') && $homeBuilder->where('id', request('homeId'));
            $homes = $homeBuilder->get();
            expIf($homes->isEmpty(), '桌面不存在');
            foreach($homes as $home) {
                $home->images()->syncWithoutDetaching(request('imageIds'));
            }
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