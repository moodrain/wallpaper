@extends('layout.frame')

@section('title', '桌面')

@section('main')
    <el-row>
        <el-col :xs="24" :span="16">
            <el-card>
                <el-input v-model="form.name"><template slot="prepend">名称</template></el-input>
                <el-divider>图片</el-divider>
                <div>
                    <el-card class="image-card" shadow="hover" v-for="image in images" :key="image.id" @click.right.native.prevent="toEdit(image)" @click.native="toPreview(image)">
                        <el-image class="image" lazy :src="image.thumb200" fit="contain"></el-image>
                    </el-card>
                </div>
                <el-divider></el-divider>
                <el-button @click="$submit('/home/save', form)">保存</el-button>
            </el-card>
        </el-col>
    </el-row>

    <el-dialog :visible.sync="show.edit" style="width: 50%;margin-left: 25%">
        <el-form>
            <el-form-item label="标签">
                <el-select v-model="edit.tags" filterable multiple disabled>
                    <el-option v-for="tag in tags" :key="tag.id" :label="tag.name" :value="tag.id"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item>
                <el-button @click="remove">移除</el-button>
            </el-form-item>
        </el-form>
    </el-dialog>

    <el-dialog :visible.sync="show.preview" full-screen top="10px">
        <img :src="preview.url" style="max-width: 100%;max-height: 100%;object-fit: contain;width: min-content;height: min-content" />
    </el-dialog>
@endsection

@section('script')
<script>
new Vue({
    el: '#app',
    data () {
        return {
            @include('piece.data')
            menuActive: 'home',
            form: {
                id: {{ $home->id }},
                name: '{{ $home->name }}',
                images: @json($home->imageIds),
            },
            show: {
                edit: false,
                preview: false,
            },
            edit: {
                id: null,
                tags: [],
            },
            preview: {
                url: '',
            },
            images: @json($home->images),
            tags: @json(\App\Models\Tag::query()->get(['id', 'name'])),
        }
    },
    methods: {
        @include('piece.method')
        toPreview(image) {
            this.preview.url = image.thumb800
            this.show.preview = true
        },
        toEdit(image) {
            this.show.edit = true
            this.edit.id = image.id
            this.edit.tags = image.tagIds
        },
        remove() {
            this.show.edit = false
            for (let i = 0;i < this.images.length;i++) {
                if (this.images[i].id === this.edit.id) {
                    this.images.splice(i, 1)
                    break
                }
            }
            for (let i = 0;i < this.form.images.length;i++) {
                if (this.form.images[i] === this.edit.id) {
                    this.form.images.splice(i, 1)
                    break
                }
            }
        }
    },
    mounted() {
        @include('piece.init')
    }
})
</script>
@endsection