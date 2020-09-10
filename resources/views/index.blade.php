@extends('layout.frame')

@section('title', '图片')

@section('main')
    <el-row>
        <el-col :span="24">
            <br />
            <el-card>
                <el-form inline>
                    <x-select exp="model:search.tag;label:标签;key:id;selectLabel:name;value:id;data:tags" />
                    <el-form-item>
                        <el-button icon="el-icon-search" @click="$to(search, true)"></el-button>
                    </el-form-item>
                    <el-form-item>
                        <el-button icon="el-icon-upload2" @click="show.upload = !show.upload"></el-button>
                    </el-form-item>
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
            <el-card>
                <div>
                    <el-card class="image-card" shadow="hover" v-for="image in images" :key="image.id" @click.right.native.prevent="toEdit(image)" @click.native="toPreview(image)">
                        <el-image class="image" lazy :src="image.thumb200" fit="contain"></el-image>
                    </el-card>
                </div>
            </el-card>
            <br />
            <el-card>
                <x-pager :size="50"></x-pager>
            </el-card>
        </el-col>
    </el-row>

    <el-dialog :visible.sync="show.edit" style="width: 50%;margin-left: 25%">
        <el-form>
            <el-form-item label="标签">
                <el-select v-model="edit.tags" filterable multiple>
                    <el-option v-for="tag in tags" :key="tag.id" :label="tag.name" :value="tag.id"></el-option>
                </el-select>
                <el-button icon="el-icon-check" @click="save"></el-button>
            </el-form-item>
            <el-form-item label="桌面">
                <el-select v-model="home.id" filterable>
                    <el-option v-for="h in homes" :key="h.id" :label="h.name" :value="h.id"></el-option>
                </el-select>
                <el-button icon="el-icon-plus" @click="toHome"></el-button>
            </el-form-item>
            <el-form-item>
                <el-button icon="el-icon-delete" type="danger" @click="remove"></el-button>
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
            this.show.edit = true
            this.edit.id = image.id
            this.edit.tags = image.tagIds
        },
        remove() {
            this.$confirm('确认要删除该图片吗?').then(() => {
                this.$fet('/image/remove', {id: this.edit.id}, 'post').then(rs =>{
                    this.show.edit = false
                    if (rs.code !== 0) {
                        this.$notify.error(rs.msg)
                        return
                    }
                    for (let i = 0;i < this.images.length;i++) {
                        if (this.images[i].id === this.edit.id) {
                            this.images.splice(i, 1)
                            break
                        }
                    }
                })
            })
        },
        save() {
            this.$fet('/image/save', this.edit, 'post').then(rs => {
                this.show.edit = false
                if (rs.code !== 0) {
                    this.$notify.error(rs.msg)
                    return
                }
                let find = this.images.find(i => i.id === this.edit.id)
                find.tagIds = []
                find.tags = []
                this.edit.tags.forEach(t => {
                    find.tags.push(t)
                    find.tagIds.push(t)
                })
            })
        },
        toPreview(image) {
            this.preview.url = image.thumb800
            this.show.preview = true
        },
        toHome() {
            this.$fet('/home/image/add', {
                homeId: this.home.id,
                imageId: this.edit.id,
            }, 'post').then(rs => {
                this.show.edit = false
                this.home.id = null
                if (rs.code !== 0 ){
                    this.$notify.error(rs.msg)
                }
            })
        }
    },
    mounted() {
        @include('piece.init')
    }
})
</script>
@endsection