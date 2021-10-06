@extends('front.layout.master2')

@section('css')
    {{-- <link rel="stylesheet" href="{{asset('assets/admin/css/table.css')}}"> --}}
    <style>
        button#btn_add {
            margin-bottom: 10px;
        }
    </style>
@stop

@section('body')
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form  method="post" action="{{route('canned.messages.add', Auth::user()->username)}}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header terms-text">
                    <h5 class="modal-title text-center" id="exampleModalLabel"><p class="pr-succ"><i class="fa fa-share"></i> Create Note</span></p></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <p>Message</p>
                    <textarea cols="55" rows="5" id="message" name="message" placeholder="Type your Secret Note -  Canned Message here"></textarea>
                    
                        
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary cancle" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-secondary save-message">Save Message</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Modal 1 end -->
<!--bitcoin blance Strat--->
<section class="bitcoin-blacnce">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 balane">
                <p><span>Secret Messages</span></p>
            </div>
            
        </div>
    </div>
</section>

<div class="col-lg-2 offset-lg-10 pt-30">
    <div class="add-messa">
        <p><span class="add-message-btn"><a class="" href="" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa fa-plus"></i> Add Secret Note</a></span></p>
    </div>
</div>


<!--Bitcoin Create Add Form-->
<section class="pt-30 create-add">
    <div class="container">
        <div class="row">
            <div class="table datatable next table-responsive">

                <table class="table table-striped bit-table message">
                    <thead>
                        <tr>
                            <th>SR.</th>
                                

                            <th>Message</th>
                            
                            <th>Action</th>
                            
                            
                        </tr>

                    </thead>
                    <tbody id="products-list" name="products-list">
                    @foreach ($messages as $key => $data)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $data->message }}</td>
                            <td >
                                <a href="#myModal-edit-{{$data->id}}" data-toggle="modal" class="btn btn-primary bold uppercase"><i class="fa fa-edit"></i> Edit</a>
                                <a href="{{route('canned.messages.delete', [Auth::user()->username, $data->id])}}"  class="btn btn-danger bold uppercase"><i class="fa fa-times"></i> Delete</a>
                            </td>
                        </tr>

                        <!-- Modal for DELETE -->
                        <div class="modal fade" id="myModal-edit-{{$data->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form class="form-horizontal" method="post" action="{{route('canned.messages.edit', Auth::user()->username)}}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel"><i class="fa fa-share-square"></i> Update Note</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group error">
                                                <label for="inputName" class="col-sm-12 control-label bold uppercase"><strong>Message :</strong> </label>
                                                <div class="col-sm-12">
                                                    <textarea type="text" rows="10" class="form-control has-error bold " id="message-edit" name="message" placeholder="Type your Secret Note -  Canned Message here">{{$data->message}}</textarea>
                                                </div>
                                            </div>
                                            <input type="hidden" name="id" id="id-edit" value="{{$data->id}}" />
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-info" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                                            <button type="submit" class="btn btn-primary bold uppercase"><i class="fa fa-send"></i> Update Message</button>

                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>

                    @endforeach
                    </tbody>
                </table>
                
            </div>
            {{$messages->links()}}
        </div>
    </div>
</section>

<!--Bitcoin Create Add Form-->
 

@stop
