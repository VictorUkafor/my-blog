@extends('layouts.app')

@section('content')
<div class="card ml-4 mr-4">
  <div class="card-body">
    <h5 class="card-title">{{$post->title}}</h5>
    <h6 class="card-subtitle mb-2 text-muted">{{$post->description}}</h6>

    <p class="card-text">{{$post->content}}</p>
  </div>
</div>
@endsection
