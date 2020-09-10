@extends('layout.app')
@section('title', '登录')
@section('html')
    <div id="app">
        <br />
        <el-row>
            <el-col :span="6" :offset="9" :xs="{span:20,offset:2}">
                <el-card>
                    <el-form>
                        <x-input exp="model:form.email;pre:邮箱;ref:email" />
                        <x-input exp="model:form.password;pre:密码;type:password;ref:password" />
                        <el-form-item>
                            <el-button @click="login">登录</el-button>
                            <el-divider direction="vertical"></el-divider>
                            <el-link href="/register">或 注册</el-link>
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
                        password: '',
                    }
                }
            },
            methods: {
                @include('piece.method')
                login() {
                    if (! this.form.email || ! this.form.password) {
                        return
                    }
                    $submit(this.form)
                }
            },
            mounted() {
                @include('piece.init')
                this.form.email ? this.$refs.password.focus() : this.$refs.email.focus()
            }
        })
        $enter(() => vue.login())
    </script>
@endsection

@section('css')
    @include('layout.css')
@endsection