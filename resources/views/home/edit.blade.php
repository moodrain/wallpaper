@extends('layout.frame')

@section('title', '桌面')

@section('main')
    <el-row>
        <el-col :xs="24" :span="16">
            <el-card>
                <el-form>
                    <el-form-item>
                        <el-input  v-model="form.name"><template slot="prepend">名称</template></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button icon="el-icon-finished" @click="(multiSelect = ! multiSelect) && (imageSelects = [])" :type="multiSelect ? 'primary' : ''"></el-button>
                        <el-button v-if="multiSelect" :type="imageSelects.length == images.length ? 'primary' : ''" @click="imageSelects.length == images.length ? imageSelects = [] : imageSelects = images.concat()">全选</el-button>
                    </el-form-item>
                </el-form>
                <el-divider>图片</el-divider>
                <div>
                    <el-card class="image-card" shadow="hover" v-for="image in images" :key="image.id"
                             @click.right.native.prevent="toEdit(image)" @click.native="toPreview(image)"
                             :class="multiSelect && imageSelects.includes(image) ? 'card-select' : ''"
                    >
                        <el-image class="image" lazy :src="image.thumb200" fit="contain"></el-image>
                    </el-card>
                </div>
                <el-divider></el-divider>
                <el-button @click="$submit('/home/save', form)">保存</el-button>
            </el-card>
        </el-col>
    </el-row>

    <el-dialog :visible.sync="show.edit" custom-class="edit-dialog">
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
            imageSelects: [],
            tags: @json(\App\Models\Tag::query()->get(['id', 'name'])),
            multiSelect: false,
        }
    },
    methods: {
        @include('piece.method')
        toPreview(image) {
            if (this.multiSelect) {
                let find = this.images.find(i => i.id === image.id)
                this.imageSelects.includes(find) ? this.imageSelects.splice(this.imageSelects.indexOf(find), 1) : this.imageSelects.push(find)
                return
            }
            this.preview.url = image.thumb800
            this.show.preview = true
        },
        toEdit(image) {
            if (this.multiSelect && ! this.imageSelects.includes(image)) {
                this.imageSelects.push(image)
            }
            this.show.edit = true
            this.edit.id = image.id
            this.edit.tags = image.tagIds
        },
        remove() {
            this.show.edit = false
            let ids = this.multiSelect ? this.imageSelects.map(i => i.id) : [this.edit.id]
            this.images = this.images.filter(i => ! ids.includes(i.id))
            this.form.images = this.form.images.filter(i => ! ids.includes(i))
            this.multiSelect = false
            this.imageSelects = []
        }
    },
    mounted() {
        @include('piece.init')
    }
})
</script>
@endsection