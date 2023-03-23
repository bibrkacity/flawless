@extends('layouts.app')

@section('title')
    Binary tree
@endsection

@section('css')
    @parent
    <link rel="stylesheet" type="text/css" href="/css/tree.css" />
@endsection

@php
    $width = pow(2,$data['levels']);
    if($width == 0)
        $width =1;
    $width *= 100;
@endphp

@section('js')
    @parent
    <script type="text/javascript" src="/js/tree.js"></script>
    <script type="text/javascript">
        let json = `{!!   $data['json'] !!}`;
        let width = {{ $width }};
        let _token = '{{ csrf_token() }}';
    </script>
@endsection

@section('content')
    <h1> Binary tree </h1>
    @if( $data['error'] > 0 )
        <h2> Error:</h2>
        {{ $data['error']  }}
    @else

        <div class="node_outer" id="node{{ $data['node']->model->id }}" style="width:{{ $width }}px;position:relative"></div>

        <script type="text/javascript">
            render();
        </script>

    @endif
@endsection
