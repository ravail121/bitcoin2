@extends('admin.layout.master')

@section('body')

<div class="page-content-wrapper">
    <div class="page-content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <i class="icon fa fa-file"></i> Address stats
                    </div>
                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6" style="margin-top: 5px;">
                                <a href="#" class="text-decoration">
                                    <div class="widget-small primary  "><i class="icon fa fa-address-card-o fa-3x"></i>
                                        <div class="info">
                                            <h4>Total Addresses</h4>
                                            <p><b>{{\App\Models\WalletAddresses::all()->count()}}</b></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6" style="margin-top: 5px;">
                                <a href="#" class="text-decoration">
                                    <div class="widget-small danger "><i class="icon fa fa-address-card-o fa-3x"></i>
                                        <div class="info">
                                            <h4>Assigned Addresses</h4>
                                            <p><b>{{\App\Models\WalletAddresses::where('status', 1)->count()}}</b>
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
                    <div class="card-header">
                        <i class="icon fa fa-file"></i> Upload addresses (.csv file)
                    </div>
                <div class="card-body">
            <div class="row">

                <div class="col-md-12 ">

                    <form action="{{route('addresses.upload')}}" method="post" enctype="multipart/form-data">

                        {{csrf_field()}}

                        <div class="input-group input-group-lg">
                            <!-- <span class="input-group-addon" id="sizing-addon1">Upload csv</span> -->

                            <input type="file" name="file" class="form-control">


                        </div>


                        <br><br>
                        <button type="submit" class="btn btn-success btn-block">Upload</button>
                    </form>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection