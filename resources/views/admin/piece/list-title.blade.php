@php
    $getTitle = function() use ($m) {
        $navs = config('view.admin.nav');
        $name = $m;
        foreach ($navs as $nav) {
            if ($nav[0] == $m) {
                $name = $nav[1];
            }
        }
        return $name . '列表';
    };
@endphp

@section('title')
    {{ $getTitle() }}
@endsection