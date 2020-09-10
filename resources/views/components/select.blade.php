@php(extract(bladeIncludeExp($exp ?? '')))
<el-form-item

    @isset($if)
        v-if="{{ $if }}"
    @endisset

    @isset($class)
        class="{{ $class }}"
    @endisset

    @isset($label)
        label="{{ $label }}"
    @endisset

    @isset($ref)
        ref="{{ $ref }}"
    @endisset

>

    <el-select

        clearable
        filterable

        @isset($multiple)
            multiple
        @endisset

        @isset($model)
            v-model="{{ $model }}"
        @endisset

        @isset($change)
            @change="{{ $change }}"
        @endisset

    >

        <el-option
            v-for="item in {{ $data }}" :key="{{ ! empty($key) ? "item.$key" : 'item' }}" :label="{{ ! empty($selectLabel) ? "item.$selectLabel" : 'item' }}" :value="{{ ! empty($value) ? "item.$value" : 'item' }}">
        </el-option>

    </el-select>

</el-form-item>