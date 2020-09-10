@foreach(config('view.user.nav.' . (mobile() ? 'mobile' : 'pc'), []) as $item)

    @if(is_string($item[2]))
        @if(user() || ! in_array($item[0], config('view.nav.auth', [])))
            <el-menu-item index="{{ $item[0] }}" @click="$to('/{{ $item[2] }}', {}, true)">{{ $item[1] }}</el-menu-item>
        @endif
    @else
        <el-submenu index="{{ $item[0] }}">
            <template slot="title">{{ $item[1] }}</template>
            @foreach($item[2] as $subItem)
                @if(user() || ! in_array($subItem[0], config('view.nav.auth', [])))
                    <el-menu-item index="{{ $subItem[0] }}" @click="$to('/{{ $subItem[2] }}', {}, true)">{{ $subItem[1] }}</el-menu-item>
                @endif
            @endforeach
        </el-submenu>
    @endif

@endforeach