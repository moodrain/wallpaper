@extends('layout.frame')

@section('title', '标签')

@section('main')
    <el-row>
        <el-col :xs="24" :span="16">
            <br />
            <el-card>
                <el-form inline style="height: 45px;overflow: hidden">
                    <x-input exp="model:form.name;holder:名称" />
                    <el-form-item>
                        <el-button icon="el-icon-search" @click="$to('/tag', form)"></el-button>
                        <el-button icon="el-icon-plus" @click="$submit(form)"></el-button>
                    </el-form-item>
                </el-form>
                <el-divider></el-divider>
                <el-table :data="tags">
                    <el-table-column prop="id" label="ID"></el-table-column>
                    <el-table-column prop="name" label="名称"></el-table-column>
                    <el-table-column label="操作">
                        <template slot-scope="scope">
                            <el-button icon="el-icon-delete" @click="remove(scope.row.id)"></el-button>
                        </template>
                    </el-table-column>
                </el-table>
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
                    menuActive: 'tag',
                    tags: @json($tags),
                    form: {
                        name: $query('name'),
                    }
                }
            },
            methods: {
                @include('piece.method')
                remove(id) {
                    this.$confirm('确认要删除该标签？').then(() => {
                        this.$submit('/tag/remove', {id})
                    }).catch(() => {})
                }
            },
            mounted() {
                @include('piece.init')
            }
        })
    </script>
@endsection