@extends('layout.frame')

@section('title', '桌面')

@section('main')
    <el-row>
        <el-col :xs="24" :span="16">
            <br />
            <el-card>
                <el-form inline>
                    <el-form-item>
                        <el-input v-model="form.name" placeholder="新建桌面"></el-input>
                    </el-form-item>
                    <el-button @click="$submit(form)" icon="el-icon-plus"></el-button>
                </el-form>
            </el-card>
            <br />
            <el-card>
                <el-table :data="homes">
                    <el-table-column prop="id" label="ID"></el-table-column>
                    <el-table-column prop="name" label="名称"></el-table-column>
                    <el-table-column prop="imagesCount" label="图片数" width="70" align="center"></el-table-column>
                    <el-table-column label="链接" width="80" align="center">
                        <template slot-scope="scope">
                            <el-button class="clipboard-btn" icon="el-icon-document" :data-clipboard-text="host + '?token=' + scope.row.token"></el-button>
                        </template>
                    </el-table-column>
                    <el-table-column label="操作">
                        <template slot-scope="scope">
                            <el-button @click="$to('/home/' + scope.row.id)" icon="el-icon-edit"></el-button>
                            <el-button @click="remove(scope.row.id)" icon="el-icon-delete"></el-button>
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
            menuActive: 'home',
            homes: @json($homes),
            form: {
                name: '',
            },
            host: window.location.host
        }
    },
    methods: {
        @include('piece.method')
        remove(id) {
            this.$confirm('确认要删除该桌面吗?').then(() => {
                this.$submit('/home/remove', {id})
            }).catch(() => {})
        },
    },
    mounted() {
        @include('piece.init')
    }
})
</script>
@endsection