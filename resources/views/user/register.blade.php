@extends('layout.app')
@section('title', '注册')
@section('html')
    <div id="app">
        <br />
        <el-row>
            <el-col :span="6" :offset="9" :xs="{span:20,offset:2}">
                <el-card>
                    <el-form>
                        <x-input exp="model:form.email;pre:邮箱" />
                        <x-input exp="model:form.name;pre:名称" />
                        <x-input exp="model:form.password;pre:密码;type:password" />
                        <x-input exp="model:form.rePassword;pre:重复密码;type:password" />
                        <el-form-item>
                            <el-button @click="register">注册</el-button>
                            <el-divider direction="vertical"></el-divider>
                            <el-link href="/login">或 登录</el-link>
                        </el-form-item>
                    </el-form>
                </el-card>
            </el-col>
        </el-row>
    </div>
@endsection

@section('js')
    @include('layout.js')
    <script>
        let vue = new Vue({
            el: '#app',
            data() {
                return {
                    @include('piece.data')
                    form: {
                        email: '{{ old('email') }}',
                        name: '{{ old('name') }}',
                        password: '',
                        rePassword: '',
                    }
                }
            },
            methods: {
                @include('piece.method')
                register() {
                    if (! this.form.email || ! this.form.password || ! this.form.name) {
                        alert('请完整填写表单')
                        return
                    }
                    if (this.form.password != this.form.rePassword) {
                        alert('两次密码不一致')
                        return
                    }
                    $submit(this.form)
                }
            },
            mounted() {
                @include('piece.init')
            }
        })
        $enter(() => vue.register())
    </script>
@endsection

@section('css')
    @include('layout.css')
@endsection