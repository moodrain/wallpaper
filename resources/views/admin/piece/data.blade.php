msg: '{{ $msg ?? session('msg') ?? '' }}',
errMsg: '@if(filled($errors)) {{ $errors->first() }} @endif',