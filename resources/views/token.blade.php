@extends('layout.frame')

@section('title', '令牌')

@section('main')
    <el-row>
        <el-col :xs="24" :span="8">
            <br />
            <el-card>
                <el-form inline>
                    <el-form-item>
                        <el-input v-model="token">
                            <template slot="prepend">
                                令牌
                            </template>
                        </el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button @click="$submit('/token')">重新生成</el-button>
                    </el-form-item>
                </el-form>
            </el-card>
        </el-col>
    </el-row>
@endsection

@section('script')
    <script>
        new Vue({
            el: '#app',
            data () {
                return {
                    @include('piece.data')
                    menuActive: 'token',
                    token: '{{ $token }}',
                }
            },
            methods: {
                @include('piece.method')
            },
            mounted() {
                @include('piece.init')
            }
        })
    </script>
@endsection