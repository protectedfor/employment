@extends('layouts.app')
@section('content')

    <div class="indexcategory">
        <div class="container">
            <div class="row">
                <!-- левый тул бар -->
                <div class="col-md-9 b-{{ $pages->slug }}">
                    <h1 style="margin-bottom: 10px;">{{ $pages->title }}</h1>
                    {!! $pages->description !!}
                </div>
            </div>
        </div>
    </div>
    <!-- конец полезного -->
@endsection