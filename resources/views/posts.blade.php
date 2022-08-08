@extends('layouts.app')

@section('content')
<posts :userId="{!! Auth::id() ? Auth::id() : 0 !!}"/>
@endsection
