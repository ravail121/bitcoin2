@extends('front.layout.master')
@section('body')
<style>
@media screen and (max-width:425px){
.display-4 {
    font-size: 2.5rem;
    font-weight: 600;
    }
}
</style>
    <div class="row">
        <div class="col-md-12">
            <div class="jumbotron text-center">
                <h1 class="display-4">{{$page_title}}</h1>
            </div>
        </div>

        <div class="col-md-12">
            <p>{!! $menu->description !!}</p>
        </div>
    </div>
@stop
