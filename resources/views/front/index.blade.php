@extends( empty(Auth::user()) ?'front.layout.master' :'front.layout.master2')


@section('style')
@stop
@section('body')
<style>
.qk_fd1 {
    margin-right: 1.7em;
}

@media screen and (min-width:1199px) and (max-width:2500px) {
    .payWid {
        width: 22% !important;
    }
}
.btnsearch {
    font-size: 18px !important;
    padding: 9px 24px !important;
}

@media screen and (max-width:1024px) {
    .btnsearch {
        margin-top: 5px;
    }
}

@media screen and (max-width:768px) {
    .qk_fd1 {
        margin-right: 0.7em;
    }
}
</style>
<section class="feature-area pt-60">
    <div class="container">
        <div class="row">
            <ul class="tabs" role="tablist">
                <li>
                    <input type="radio" name="tabs" id="tab1" checked />
                    <label for="tab1" role="tab" aria-selected="true" aria-controls="panel1" tabindex="0">quick buy</label>
                    <div id="tab-content1" class="tab-content" role="tabpanel" aria-labelledby="description" aria-hidden="false">
                        <form action="{{route('quick.search')}}" method="post" accept-charset="utf-8">
                            @csrf
                            <input type="hidden" name="add_type" value="1">
                            <div class="form-row Category-span">
                                <div class="form-group col-md-6 select-cat-index">
                                    <span for="inputState" class="Select-control">Select Currency </span>
                                    <select required id="inputState" name="currency_id" class="form-control input-control">
                                        <option disabled value="all" selected>Select Currency </option>
                                        @foreach($currency as $data)
                                        <option value="{{$data->id}}" @if($data->id == session()->get('currency_id')) selected @endif >{{$data->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6 select-cat-index">
                                    <span for="inputState" class="Select-control">Select Country</span>
                                    <select required id="inputState" name="country" class="form-control input-control">
                                        <option disabled value="all" selected>Select Country</option>
                                        @foreach($countries as $data)
                                        <option value="{{$data->id}}" @if($data->name == session()->get('country')) selected @endif >{{$data->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-row Category-span">
                                <div class="form-group col-md-6 select-cat-index">
                                    <span for="inputState" class="Select-control">Select Category</span>
                                    <select required id="inputState" name="category_id" class="form-control input-control">
                                        <option disabled value="all" selected>Select Category</option>
                                        @foreach($categories as $data)
                                        <option value="{{$data->id}}">{{$data->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6 select-cat-index">
                                    <span for="inputState" class="Select-control">All Online Offers</span>
                                    <select required id="inputState" name="method_id" class="form-control input-control">
                                        <option disabled value="all" selected>All Online Offers</option>
                                        @foreach($methods as $data)
                                        <option value="{{$data->id}}">{{$data->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary Search">Search</button>
                        </form>
                    </div>
                </li>

                <li>
                    <input type="radio" name="tabs" id="tab2" />
                    <label for="tab2" role="tab" aria-selected="false" aria-controls="panel2" tabindex="0">quick Sell</label>
                    <div id="tab-content2" class="tab-content" role="tabpanel" aria-labelledby="specification" aria-hidden="true">
                        <form action="{{route('quick.search')}}" method="post" accept-charset="utf-8">
                            @csrf
                            <input type="hidden" name="add_type" value="2">
                            <div class="form-row Category-span">
                                <div class="form-group col-md-6 select-cat-index">
                                    <span for="inputState" class="Select-control">Select Currency </span>
                                    <select required id="inputState" name="currency_id" class="form-control input-control">
                                        <option disabled value="all" selected>Select Currency </option>
                                        @foreach($currency as $data)
                                        <option value="{{$data->id}}" @if($data->id == session()->get('currency_id')) selected @endif >{{$data->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6 select-cat-index">
                                    <span for="inputState" class="Select-control">Select Country</span>
                                    <select required id="inputState" name="country" class="form-control input-control">
                                        <option disabled value="all" selected>Select Country</option>
                                        @foreach($countries as $data)
                                        <option value="{{$data->id}}" @if($data->name == session()->get('country')) selected @endif >{{$data->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-row Category-span">
                                <div class="form-group col-md-6 select-cat-index">
                                    <span for="inputState" class="Select-control">Select Category</span>
                                    <select required id="inputState" name="category_id" class="form-control input-control">
                                        <option disabled value="all" selected>Select Category</option>
                                        @foreach($categories as $data)
                                        <option value="{{$data->id}}">{{$data->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6 select-cat-index">
                                    <span for="inputState" class="Select-control">All Online Offers</span>
                                    <select required id="inputState" name="method_id" class="form-control input-control">
                                        <option disabled value="all" selected>All Online Offers</option>
                                        @foreach($methods as $data)
                                        <option value="{{$data->id}}">{{$data->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary Search">Search</button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</section>

<!-- <hr class="container"/> -->

<section class="table-section">
    <div class="container">
        <div class="row">
            <div class="buy"><span>Buy Bitcoin</span></div>
                <div class="table-div datatable table-responsive">
                    @if(empty($sellOffers) || count($sellOffers) ==0)

                    <p style="margin-top: 45px;">No advertisement available</p>
                    <b> Be the first to advertise in your country
                        and be the leader in your market.</b>
                    @else
                    @include('front.trade-table', [
                        'type' => 'buy',
                        'offers' => $sellOffers,
                    ])
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<section class="table1">
    <div class="container">
        <div class="row">
            <div class="buy"><span>Sell Bitcoin</span></div>
                <div class="table-div datatable table-responsive">
                    @if(empty($buyOffers) || count($buyOffers) ==0)

                    <p style="margin-top: 45px;">No advertisement available</p>
                    <b> Be the first to advertise in your country
                        and be the leader in your market.</b>
                    @else
                    @include('front.trade-table', [
                        'type' => 'sell',
                        'offers' => $buyOffers,
                    ])
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- <div  style="margin: auto;width: 40%;
  padding: 10px;">
                <label for="size"> Showing advertisements in:
                     </label>
                <select name="current_country" class="form-control" id="current_country"
                    style="background-color: #66a3ac; color: white;">
                    <option value="all">Select Country</option>

                    @foreach($countries as $data)

                    <option value="{{$data->iso}}" @if($data->name == session()->get('country')) selected @endif >{{$data->name}}</option>

                    @endforeach
                </select>
           
        



</div> -->

<section class="for-add">
    <div class="container">
        <div class="row">
            <div class="form-row showing-span">
                <div class="form-group select-adve">
                    <span for="inputState" class="Select-control">Showing Advertisements in:</span>
                    <select name="current_country"  id="current_country" class="form-control input-control">
                        <option disabled value="" selected>Select Country </option>
                        @foreach($countries as $data)
                        <option value="{{$data->iso}}" @if($data->name == session()->get('country')) selected @endif >{{$data->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
@section('script')

    
<script>
$(document).ready(function() {


    $(document).on('change', '#current_country', function() {
        var country = $(this).val();
        $.ajax({
            type: "GET",
            url: '/country-change/' + country,
            success: function(data1) {
                // swal("Country Updated", "", "success");
                location.reload();

            }
        })

    })
    $(document).on('change', '#category_id2', function() {
        $('#method_id1')
            .empty()


        var categories = {!!json_encode($categories) !!};
        var methods = {!!json_encode($methods) !!};
        var current = $(this).val();
        var string = '';
        for (var i in methods) {
            //    if(methods[i].id == $(this).val()){

            var cat_ids = methods[i].category_ids;


            if (cat_ids) {
                var cat_ids = cat_ids.split(',');
                console.log(cat_ids)

                if (cat_ids.indexOf(current.toString()) !== -1) {

                    string += '<option value="' + methods[i].id + '">' + methods[i].name + ' </option>';

                }

            }
        }
        if (string == '') {
            string += '<option value=""> No Payment Method</option>';
        }
        $('#method_id1').append(string)
        $('#method_id1').attr("disabled", false);
        console.log(categories)

    })

    $(document).on('change', '#category_id3', function() {
        $('#method_id2')
            .empty()


        var categories = {!!json_encode($categories) !!};
        var methods = {!!json_encode($methods) !!};
        var current = $(this).val();
        var string = '';
        for (var i in methods) {
            //    if(methods[i].id == $(this).val()){

            var cat_ids = methods[i].category_ids;


            if (cat_ids) {
                var cat_ids = cat_ids.split(',');
                console.log(cat_ids)

                if (cat_ids.indexOf(current.toString()) !== -1) {

                    string += '<option value="' + methods[i].id + '">' + methods[i].name + ' </option>';

                }

            }
        }
        if (string == '') {
            string += '<option value=""> No Payment Method</option>';
        }
        $('#method_id2').append(string)
        $('#method_id2').attr("disabled", false);
        console.log(categories)

    })



    // Handler for .ready() called.
    if (window.location.href.indexOf("?p=") > -1) {
        $('html, body').animate({
            scrollTop: $('#searchsell').offset().top
        }, 'slow');
    }
    if (window.location.href.indexOf("?q=") > -1) {
        $('html, body').animate({
            scrollTop: $('#searchbuy').offset().top
        }, 'slow');
    }

});
</script>

@stop