@extends('admin.layout.app')

@section('title')
@endsection

@section('html')

    <div id="loading" style="position: absolute;z-index: 3000;background: #b4f3f4;width: 100%;height: 100%"></div>

    @if(mobile())

        <div id="app">

            <el-container style="height: 100%;width: 100%;">

                <el-header style="height: 60px;width: 100%;padding: 0;overflow: hidden">

                    <el-menu style="height: 100%;width: max-content" :default-active="menuActive" background-color="#545c64" text-color="#fff" active-text-color="#ffd04b" mode="horizontal">

                        @include('admin.layout.frame-nav')

                        <el-submenu index="user">
                            <template slot="title">{{ user()->name }}</template>
                            <a href="javascript:" onclick="document.querySelector('#logout').submit()"><el-menu-item index="user-logout">登出</el-menu-item></a>
                        </el-submenu>

                    </el-menu>

                </el-header>

                <el-main ref="main" style="width: 100%;height: 100%;background: #b4f3f4;overflow: scroll;">
                    @yield('main')
                </el-main>

            </el-container>

        </div>

    @else

        <div id="app">
            <el-container style="height: 100%">

                <el-aside style="width: 200px;height: 100%;overflow: hidden">

                    <el-menu style="height: 100%;width: 100%" :default-active="menuActive" background-color="#545c64" text-color="#fff" active-text-color="#ffd04b">

                        <el-container style="width: 100%;height: 60px;line-height: 60px;">
                            <p style="color: white;font-size: 1.4em;width: 100%;text-align: center;user-select: none">{{ env('APP_NAME') }}</p>
                        </el-container>

                        @include('admin.layout.frame-nav')

                    </el-menu>

                </el-aside>

                <el-container>

                    <el-header style="user-select: none;background-color: #545c64;color: #fff;line-height: 60px">

                        <el-dropdown style="float: right">
                            <p style="cursor: pointer;color: #fff">{{ user()->name }} <i class="el-icon-arrow-down el-icon--right"></i></p>
                            <el-dropdown-menu slot="dropdown">
                                <el-dropdown-item><a href="javascript:" onclick="document.querySelector('#logout').submit()">登出</a></el-dropdown-item>
                            </el-dropdown-menu>
                        </el-dropdown>

                    </el-header>

                    <el-main ref="main" style="width: 100%;height: 100%;background: #b4f3f4;overflow: scroll;">
                        @yield('main')
                    </el-main>

                </el-container>

            </el-container>
        </div>

    @endif

    <form hidden id="logout" action="/logout" method="POST"></form>

@endsection



@section('js')
    @include('admin.layout.js')
    @yield('script')
@endsection

@section('css')
    @include('admin.layout.css')
    @yield('style')
@endsection