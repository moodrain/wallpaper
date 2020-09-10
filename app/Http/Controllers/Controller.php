<?php

namespace App\Http\Controllers;

use App\Models\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->initSearch();
        $this->initSort();
        singleUser() && Auth::loginUsingId(singleUser()->id);
    }

    private function initSearch()
    {
        $search = (array) request('search');
        foreach ($search as $key => $value) {
            if ($value === null || $value === '') {
                unset($search[$key]);
            }
        }
        $this->search = $search;
    }

    private function initSort()
    {
        $sort = (array) request('sort');
        foreach ($sort as $key => $value) {
            if ($value === null || $value === '') {
                unset($sort[$key]);
            }
        }
        $this->sort = $sort;
    }

    protected function mSearch($builder): Builder
    {
        return $builder->search($this->search)->sort();
    }

    protected function vld($rules)
    {
        $this->validate(request(), $rules);
    }

    protected function viewOk($view, $para = [])
    {
        return view($view, array_merge($para, ['msg' => __('msg.success')]));
    }

    protected function directOk($uri)
    {
        return redirect($uri)->with('msg', __('msg.success'));
    }

    protected function backOk()
    {
        return redirect()->back()->withInput()->with('msg', __('msg.success'));
    }

    protected function backErr($errMsg)
    {
        return redirect()->back()->withInput()->withErrors(__($errMsg));
    }

    protected function api($rules, callable $handle) {
        try {
            $validator = Validator::make(request()->all(), $rules);
            expIf($validator->fails(),$validator->errors()->first());
            return $handle();
        } catch (\Exception $e) {
            return ers($e->getMessage());
        }
    }

}
