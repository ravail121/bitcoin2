@extends('admin.layout.master')

@section('css')
@stop
@section('body')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <i class="icon fa fa-clock-o"></i> Cron Jobs Management
            </div>
            <div class="card-body">
                <form method="POST" action="{{route('cron-jobs.update')}}">
                    {{csrf_field()}}
                    <div class="form-row align-items-center">
                        <div class="col-auto my-1">
                        <!-- <label class="mr-sm-2" for="inlineFormCustomSelect">Action</label> -->
                        <select name="action_type" class="custom-select mr-sm-2" id="inlineFormCustomSelect">
                            <option value="restart">Restart Cron</option>
                            <option value="start">Start Cron</option>
                            <option value="stop">Stop Cron</option>
                        </select>
                        </div>
                        <div class="col-auto my-1">
                        <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

@stop