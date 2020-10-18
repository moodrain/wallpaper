@extends('layout.frame')

@section('title', '图片')

@section('main')
    <el-row>
        <el-col :xs="24" :span="17">
            <el-card>
                <el-form inline>
                    <x-select exp="model:search.tag;label:标签;key:id;selectLabel:name;value:id;data:tags" />
                    <el-button icon="el-icon-search" @click="$to(search, true)"></el-button>
                    <el-button icon="el-icon-upload2" @click="show.upload = !show.upload"></el-button>
                    <el-button icon="el-icon-finished" @click="(multiSelect = ! multiSelect) && (imageSelects = [])" :type="multiSelect ? 'primary' : ''"></el-button>
                    <el-button v-if="multiSelect" :type="imageSelects.length == images.length ? 'primary' : ''" @click="imageSelects.length == images.length ? imageSelects = [] : imageSelects = images.concat()">全选</el-button>
                </el-form>
                <div v-if="show.upload">
                    <el-divider></el-divider>
                    <el-upload drag multiple action="/image/upload" :on-success="uploaded" :show-file-list="false" :with-credentials="true" accept="image/*">
                        <i class="el-icon-upload"></i>
                        <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
                    </el-upload>
                </div>
            </el-card>
            <br />
            <el-card style="overflow: scroll;" :style="style.imageBox">
                <div>
                    <el-card class="image-card" shadow="hover" v-for="image in images" :key="image.id"
                             @click.right.native.prevent="toEdit(image)"
                             @click.native="toPreview(image)"
                             :class="multiSelect && imageSelects.includes(image) ? 'card-select' : ''"
                    >
                        <el-image class="image" lazy :src="image.thumb200" fit="contain"></el-image>
                    </el-card>
                </div>
            </el-card>
            <br />
            <el-card>
                <x-pager :size="200"></x-pager>
            </el-card>
        </el-col>
    </el-row>

    <el-dialog :visible.sync="show.edit" custom-class="edit-dialog">
        <el-form>
            <el-form-item label="标签">
                <el-select v-model="edit.tags" filterable multiple>
                    <el-option v-for="tag in tags" :key="tag.id" :label="tag.name" :value="tag.id"></el-option>
                </el-select>
                <el-button icon="el-icon-check" @click="addTag"></el-button>
            </el-form-item>
            <el-form-item label="桌面">
                <el-select v-model="home.id" filterable>
                    <el-option v-for="h in homes" :key="h.id" :label="h.name" :value="h.id"></el-option>
                </el-select>
                <el-button icon="el-icon-plus" @click="toHome(false)"></el-button>
            </el-form-item>
            <el-form-item>
                <el-button icon="el-icon-delete" type="danger" @click="remove"></el-button>
                <el-button @click="toHome(true)">添加到所有桌面</el-button>
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
            menuActive: 'image',
            tags: @json(\App\Models\Tag::query()->get(['id', 'name'])),
            homes: @json($homes),
            search: {
                name: '',
                tag: null,
            },
            images: @json($pager->all()),
            imageSelects: [],
            page: {{ $pager->currentPage() }},
            total: {{ $pager->total() }},
            show: {
                upload: false,
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
            home: {
                id: null,
            },
            multiSelect: false,
            style: {
                imageBox: {height: ''}
            }
        }
    },
    methods: {
        @include('piece.method')
        uploaded(rs) {
            if (rs.code !== 0) {
                this.$notify.error(rs.msg)
                return
            }
            this.images.unshift(rs.data)
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
            this.$confirm('确认要删除' + (this.multiSelect ? ('选定的 ' + this.imageSelects.length + ' 张') : '该') + '图片吗?').then(() => {
                let data = this.multiSelect ? {ids: this.imageSelects.map(i => i.id)} : {id: this.edit.id}
                this.$fet('/image/remove', data, 'post').then(rs =>{
                    this.show.edit = false
                    if (rs.code !== 0) {
                        this.$notify.error(rs.msg)
                        return
                    }
                    let ids = this.multiSelect ? this.imageSelects.map(i => i.id) : [this.edit.id]
                    this.images = this.images.filter(i => ! ids.includes(i.id))
                    this.imageSelects = []
                    this.multiSelect = false
                })
            }).catch(() => {})
        },
        addTag() {
            let data = {
                id: this.edit.id,
                tags: this.edit.tags.concat(),
            }
            if (this.multiSelect) {
                data.ids = this.imageSelects.map(i => i.id)
            }
            this.$fet('/image/tag', data, 'post').then(rs => {
                this.show.edit = false
                if (rs.code !== 0) {
                    this.$notify.error(rs.msg)
                    return
                }
                if (this.multiSelect) {
                    let imageSelects = this.images.filter(i => this.imageSelects.includes(i))
                    imageSelects.forEach(i => {
                        i.tags = []
                        i.tagIds = []
                        this.edit.tags.forEach(t => {
                            i.tags.push(t)
                            i.tagIds.push(t)
                        })
                    })
                    this.multiSelect = false
                } else {
                    let find = this.images.find(i => i.id === this.edit.id)
                    find.tagIds = []
                    find.tags = []
                    this.edit.tags.forEach(t => {
                        find.tags.push(t)
                        find.tagIds.push(t)
                    })
                }
            })
        },
        toPreview(image) {
            if (this.multiSelect) {
                let find = this.images.find(i => i.id === image.id)
                this.imageSelects.includes(find) ? this.imageSelects.splice(this.imageSelects.indexOf(find), 1) : this.imageSelects.push(find)
                return
            }
            this.preview.url = image.thumb800
            this.show.preview = true
        },
        toHome(all) {
            this.$fet('/home/image/add', {
                homeId: this.home.id,
                imageIds: this.multiSelect ? this.imageSelects.map(i => i.id) : [this.edit.id],
                all,
            }, 'post').then(rs => {
                this.show.edit = false
                this.home.id = null
                if (rs.code !== 0 ){
                    this.$notify.error(rs.msg)
                }
            })
            this.multiSelect = false
            this.imageSelects = []
        },
    },
    mounted() {
        @include('piece.init')
        this.style.imageBox.height = (document.body.clientHeight - 335) + 'px'
    }
})
</script>
@endsection