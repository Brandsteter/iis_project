@extends('app')
@section('content')
    <div>
        <eventdetail :event="{{$event}}"></eventdetail>
    </div>
@endsection
