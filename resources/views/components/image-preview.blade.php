<el-dialog :visible.sync="imagePreview.show" style="text-align: center" :width="imagePreview.width + 'px'" top="0">
    <el-image :src="imagePreview.src" fit="contain"></el-image>
</el-dialog>