@extends('admin.layout.master')
@section('style')

@endsection
@section('body')

<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <h3 class="tile-title pull-left">Filters</h3>
            <br>
            <div class="row">
                <form method="GET" class="" action="{{route('search.overview')}}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Country</label>
                                <select name="country_id"  class="form-control select ">
                                    <option value="">Select Country</option>
                                    @foreach($countries as $key => $country)
                                    @if($country_id)
                                    <option value="{{ $country_id }}"
                                        {{ $country->id == $country_id ? 'selected' : '' }}>{{ $country->name }}
                                    </option>
                                    @else
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endif
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">City</label>
                                <input type="text" name="city" id="city" value="{{$city ? $city:''}}" class="form-control input-lg" placeholder="Type to search">

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Verified</label>
                                <select name="verified" class="form-control select input-lg">
                                    <option value="">Select</option>


                                    <option value="0" {{$verified =='0'? 'selected':''}}>No</option>
                                    <option value="1" {{$verified =='1'? 'selected':''}}>Yes</option>

                                </select>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Active</label>
                                <select name="active" class="form-control select input-lg">
                                    <option value="">Select</option>


                                    <option value="0" {{$active =='0'? 'selected':''}}>No</option>
                                    <option value="1" {{$active =='1'? 'selected':''}}>Yes</option>

                                </select>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Has Ads</label>
                                <select name="ads" class="form-control select input-lg">
                                    <option value="">Select</option>


                                    <option value="0" {{$ads =='0'? 'selected':''}}>No</option>
                                    <option value="1" {{$ads =='1'? 'selected':''}}>Yes</option>

                                </select>

                            </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group">
                        <button class="btn btn-outline btn-primary" style="margin: 31px;" type="submit">Search
                        <i class="fa fa-search"></i>
                    </button>
                        </div> </div>
                        
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table myTable">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Verified</th>
                            <th scope="col">Active</th>
                            <th scope="col">Age</th>
                            <th scope="col">Total traded</th>
                            <th scope="col">No of disputes</th>
                            <th scope="col">Number of Ads</th>
                            <th scope="col">Username</th>
                            <th scope="col">Country</th>
                            <th scope="col">Mobile</th>
                            <th scope="col">Open Deals</th>
                            <th scope="col">Balance</th>

                            <th scope="col">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td data-label="Name">
                                <a href="{{route('user.single', $user->username)}}">
                                    {{$user->name}}
                                </a>
                            </td>
                            <td data-label="Email">{{$user->email}}</td>
                            @if(!$user->verified)
                            <td data-label="Verified"><span class="bg-danger"> No</span> </td>
                            @else
                            <td data-label="Verified"><span class="bg-success"> Yes</span> </td>
                            @endif
                            @if(!$user->status)
                            <td data-label="Active"><span class="bg-danger"> No</span> </td>
                            @else
                            <td data-label="Active"><span class="bg-success"> Yes</span> </td>
                            @endif

                            @if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $user->user_dob) || !$user->user_dob)
                            <td data-label="DOB">N/A</td>
                            @else
                            <td data-label="DOB">{{date_diff(date_create($user->user_dob), date_create(date('Y-m-d')))->y}}
                            </td>
                            @endif

                            <td data-label="Total traded"> {{$user->trade_btc}} BTC</td>
                            <td data-label="disputes">{{$user->disputes}}</td>
                            @if(!$user->adds)
                            <td data-label="Advertise">0</td>
                            @else
                            <td data-label="Advertise">{{$user->adds}}</td>
                            @endif
                            <td data-label="Username">
                                <a href="{{route('user.single', $user->username)}}">
                                    {{$user->username}}
                                </a></td>
                            @if($user->country)
                            <td data-label="Country">{{$user->country->name}}</td>
                            @else
                            <td data-label="Country"> N/A</td>
                            @endif
                            @if($user->phone)
                            <td data-label="Mobile">{{$user->phone }}</td>
                            @else
                            <td data-label="Mobile"> N/A</td>
                            @endif
                            <td data-label="Open Deals">{{$user->opendeals}}</td>

                            @if(!$user->blnce)
                            <td data-label="Balance">0</td>
                            @else
                            <td data-label="Balance">{{$user->blnce}}</td>
                            @endif

                            <td data-label="Details" class="form-group">
                                <form class="" action="index.html" method="post">

                                    @csrf
                                    <input type="hidden" value="{{ $user->id }}" id="activationUser">
                                    <input type="hidden" value="{{ csrf_token() }}" id="csrf_token">
                                    <style>
                                    .toggle-off.btn {
                                        padding-left: 0px;
                                    }

                                    .btn {
                                        padding: 0.375rem 0.45rem;
                                    }
                                    </style>
                                    <input class="form-control" id="activation" onchange="updateStatus({{ $user->id }} ,{{$user->status}})"
                                        data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                        data-width="100%" data-on="Active" data-off="Inactive" type="checkbox"
                                        name="status" {{ $user->status == "1" ? 'checked' : '' }}>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
               
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
  $(document).ready(function() {
    $("#city").keyup(function (event) {
        var val = $('[name=city]').val();


        if (/\s/.test(val)) {
            // It has any kind of whitespace
            console.log('yes');
            var arr = val.split(' ');
            var val = arr[arr.length - 1];
        }
        if (val == '') {
            return false;
        }

        $.ajax({
            url: '/city/' + val,
            type: 'GET',
            cache: false,
            async: false,

            success: function (data) {
                console.log(data);
                var availableWords = data;
                autocomplete(document.getElementById("city"), availableWords);
            },
            error: function (data) {
                console.log(data);

            }

        });

    });
  });
function updateStatus(userId,status1) {
   
    let url = '/adminio/user/update-status/' + userId;
    let status;

    if (status1) {
        status = 0;
    } else {
        status = 1;
    }
    let data = {
        "_token": "{{ csrf_token() }}",
        status
    }

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'PUT',
        url: url,
        data: data,
        success: function(responce) {
            console.log(responce);
        }
    });
}
function autocomplete(inp, arr) {
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    console.log(arr)
    console.log(arr.length)
    /*execute a function when someone writes in the text field:*/
    // inp.addEventListener("input", function(e) {
    // console.log(this.value)
    var a, b, i, val;
    /*close any already open lists of autocompleted values*/
    closeAllLists();
    // if (!val) { return false;}
    currentFocus = -1;
    /*create a DIV element that will contain the items (values):*/
    a = document.createElement("DIV");
    a.setAttribute("id", this.id + "autocomplete-list");
    a.setAttribute("class", "autocomplete-items");
    /*append the DIV element as a child of the autocomplete container:*/
    inp.parentNode.appendChild(a);
    /*for each item in the array...*/
    for (i = 0; i < arr.length; i++) {
        console.log(arr[i]);
        /*check if the item starts with the same letters as the text field value:*/
        //   if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
        /*create a DIV element for each matching element:*/
        b = document.createElement("DIV");
        b.setAttribute("class", "high");
        /*make the matching letters bold:*/
        // b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
        b.innerHTML += arr[i];
        /*insert a input field that will hold the current array item's value:*/
        b.innerHTML += '<input type="hidden" value="' + arr[i] + '">';
        /*execute a function when someone clicks on the item value (DIV element):*/
        b.addEventListener("click", function (e) {
            /*insert the value for the autocomplete text field:*/
            if (/\s/.test(inp.value)) {
                // It has any kind of whitespace
                console.log('yes2');
                var str = inp.value;
                var arr1 = inp.value.split(' ');
                // var val1 = arr1[arr1.length-1];
                arr1.pop();
                var str1 = arr1.join(' ');
                inp.value = str1 + ' ' + this.getElementsByTagName("input")[0].value;
                // console.log(inp.value);
                closeAllLists();
            } else {
                // console.log(this.getElementsByTagName("input"));
                inp.value = this.getElementsByTagName("input")[0].value;
                // console.log(inp.value);
                /*close the list of autocompleted values,
                (or any other open lists of autocompleted values:*/
                closeAllLists();
            }


        });
        a.appendChild(b);
        //   }
    }
    // });
    //     /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function (e) {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.keyCode == 40) {
            /*If the arrow DOWN key is pressed,
            increase the currentFocus variable:*/
            currentFocus++;
            /*and and make the current item more visible:*/
            addActive(x);
        } else if (e.keyCode == 38) { //up
            /*If the arrow UP key is pressed,
            decrease the currentFocus variable:*/
            currentFocus--;
            /*and and make the current item more visible:*/
            addActive(x);
        } else if (e.keyCode == 13) {
            /*If the ENTER key is pressed, prevent the form from being submitted,*/
            e.preventDefault();
            if (currentFocus > -1) {
                /*and simulate a click on the "active" item:*/
                if (x) x[currentFocus].click();
            }
        }
    });
    function addActive(x) {
        /*a function to classify an item as "active":*/
        if (!x) return false;
        /*start by removing the "active" class on all items:*/
        removeActive(x);
        if (currentFocus >= x.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = (x.length - 1);
        /*add class "autocomplete-active":*/
        x[currentFocus].addClass("autocomplete-active");
    }
    function removeActive(x) {
        /*a function to remove the "active" class from all autocomplete items:*/
        for (var i = 0; i < x.length; i++) {
            x[i].classList.removeClass("autocomplete-active");
        }
    }
    function closeAllLists(elmnt) {
        /*close all autocomplete lists in the document,
        except the one passed as an argument:*/
        var x = document.getElementsByClassName("autocomplete-items");
        for (var i = 0; i < x.length; i++) {
            if (elmnt != x[i] && elmnt != inp) {
                x[i].parentNode.removeChild(x[i]);
            }
        }
    }
    //   /*execute a function when someone clicks in the document:*/
    document.addEventListener("click", function (e) {
        closeAllLists(e.target);
    });
}
</script>
@endsection