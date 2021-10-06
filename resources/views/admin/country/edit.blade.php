@extends('admin.layout.master')
@section('css')

    <style>
        button#btn_add {
            margin-bottom: 10px;
        }
    </style>
@stop

@section('body')
    <div class="row">
        <div class="col-md-12">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="tile">
                <div class="tile-body">
                    <form class="form-horizontal" method="post" action="{{route('currency.update', $crypto->id)}}">
                        @csrf
                        {{method_field('put')}}


                            <div class="form-group error">
                                <label for="inputName" class="col-sm-12 control-label bold uppercase"><strong>Name :</strong> </label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control has-error bold" id="name" name="name" value="{{$crypto->name}}" placeholder="Crypto Name">
                                </div>
                            </div>
                        <div class="form-group error">
                            <label for="inputName" class="col-sm-12 control-label bold uppercase"><strong>USD Rate:</strong> </label>
                            <div class="col-sm-12">

                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text">1 USD = </span>
                                    </div>
                                    <input type="text" class="form-control has-error bold" value="{{$crypto->usd_rate}}" name="usd_rate">
                                </div>

                            </div>
                        </div>

                            <div class="form-group error">
                                <label for="inputName" class="col-sm-12 control-label bold uppercase"><strong>Status</strong> </label>
                                <div class="col-sm-12">
                                    <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                           data-width="100%" type="checkbox" {{$crypto->status ==1? 'checked': ''}} data-on="Active" data-off="Deactive"
                                           name="status">
                                </div>
                            </div>



                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary bold uppercase btn-block"><i class="fa fa-send"></i> Update Crypto</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>


@stop
