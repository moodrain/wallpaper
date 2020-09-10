<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Builder as LaravelBuilder;
use Illuminate\Support\Str;

class Builder extends LaravelBuilder {

    public function search($queries, $rules = [])
    {
        ! $rules && $rules = get_class_vars(get_class($this->model))['searchRule'];
        foreach ($rules as $name => $type) {
            $name = explode('/', $name)[0];
            if (isset($queries[$name])) {
                $value =  $queries[$name];
                if (in_array($type, ['=', '>', '<', '>=', '<=', '<>', '!='])) {
                    $this->where(Str::snake($name), $type, $queries[$name]);
                    continue;
                }
                switch ($type) {
                    case 'like':
                        $this->where(Str::snake($name), 'like', "%$value%");
                        break;
                    case 'between':
                        $this->whereBetween(Str::snake($name), $value);
                        break;
                }
            }
        }
        return $this;
    }

    public function sort()
    {
        request()->filled('sort.prop') && $this->orderBy(Str::snake(request('sort.prop')), request('sort.order') == 'desc' ? 'desc' : 'asc');
        return $this;
    }

}
