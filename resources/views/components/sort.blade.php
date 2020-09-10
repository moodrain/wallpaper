<x-select exp="model:sort.prop;label:排序;data:sortOptions"></x-select>
<el-form-item>
    <el-select v-model="sort.order">
        <el-option :key="'ascending'" :label="'升序'" :value="'asc'"></el-option>
        <el-option :key="'descending'" :label="'降序'" :value="'desc'"></el-option>
    </el-select>
</el-form-item>