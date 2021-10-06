@extends('admin.layout.master')

@section('body')
<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <h3 class="tile-title ">Email Template</h3>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> CODE </th>
                                <th> DESCRIPTION </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td> 1 </td>
                                <td>
                                    <pre>&#123;&#123;message&#125;&#125;</pre>
                                </td>
                                <td> Details Text From Script</td>
                            </tr>
                            <tr>
                                <td> 2 </td>
                                <td>
                                    <pre>&#123;&#123;name&#125;&#125;</pre>
                                </td>
                                <td> Users Name. Will Pull From Database and Use in EMAIL text</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <h3 class="tile-title ">Email Template</h3>
            <div class="tile-body">
                <form role="form" method="POST" action="{{route('template.update')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-body">
                        <div class="form-group">
                            <label><strong>Email Sent Form</strong></label>
                            <input type="email" name="esender" class="form-control input-lg" value="{{$temp->esender}}">
                        </div>

                        <div class="form-group">
                            <label><strong>Email Message</strong></label>
                            <textarea class="form-control" name="emessage" id="emessage" rows="10">
									{!!$temp->emessage!!}
								</textarea>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-block btn-lg">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



@endsection

@section('script')
<!-- <script src="{{asset('assets/admin/js/nicEdit-latest.js')}}"></script> -->
<!-- <script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script> -->
<script src="https://www.trustbitcoinbank.com/assets/admin/nicEdit/nicEdit-latest.js"></script>
<script>
bkLib.onDomLoaded(function() {
    new nicEditor({
        fullPanel: true
    }).panelInstance('emessage');
});
</script>
@stop