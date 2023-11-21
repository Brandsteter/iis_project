@extends('app')
@section('content')
    <div>
        <categorydetail :category="{{$category}}"></categorydetail>
    </div>
@endsection
