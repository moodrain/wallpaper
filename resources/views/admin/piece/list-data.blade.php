@include('piece.data')
menuActive: '{{ $m }}-list',
selects: [],
sort: {
    prop: '{{ request('sort.prop') }}',
    order: '{{ request('sort.order') ?? 'asc' }}',
},
list: @json($l).data,
page: {{ $l->currentPage() }},
total: {{ $l->total() }},
sortOptions: @json(get_class_vars($modelClass)['sortRule']),
@include('admin.piece.list-search')