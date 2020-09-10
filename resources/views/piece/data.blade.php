msg: '{{ $msg ?? session('msg') ?? '' }}',
errMsg: '@if(filled($errors)) {{ $errors->first() }} @endif',
imagePreview: {
    show: false,
    src: null,
    width: null,
},