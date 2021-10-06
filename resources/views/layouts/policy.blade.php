@extends('front.layout.master')
@section('body')

    <div class="row">
        <div class="col-md-12">
            <div class="jumbotron text-center">
                <h1 class="display-4">{{$page_title}}</h1>
            </div>
        </div>

        <div class="col-md-12">
            <p>{!! $general->policy !!}</p>
        </div>
    </div>

@stop