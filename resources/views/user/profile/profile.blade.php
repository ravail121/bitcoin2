@extends( 'front.layout.master2' )
@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@stop
@section('body')
<!--bitcoin Edit Profile Strat--->
<section class="mb-5 editprofile">
    @if (count($errors) > 0)
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger alert-dismissible">
                <h3><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Alert!</h3>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </div>
        </div>
    </div>
    @endif
    <div class="logo-header text-center">
        <p class="edit">Edit Your Profile</p>
    </div>
    <div class="edit-content pt-30">
        <form method="post" action="/user/{{$user->username}}/edit-profile/step-1" enctype="multipart/form-data">
                @csrf
                @method('put')
            <div class="Category-span">
                <div class="form-group col-lg-12">
                    <span for="inputState" class="Select-control">Name</span>
                    <input type="text" class="form-control editprofile-input"  name="name" required value="{{ $user->name }}" aria-describedby="emailHelp" placeholder="Enter Your Name Here" />
                </div>
            </div>
            <div class="Category-span">
                <div class="form-group col-lg-12">
                    <span for="inputState" class="Select-control">Email</span>
                    <input type="email" value="{{ $user->email }}" name="email" readonly class="form-control editprofile-input" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Your Email" />
                </div>
            </div>
            <div class="Category-span">
                <div class="form-group col-lg-12">
                    <span for="inputState" class="Select-control">Phone (With your country code)</span>
                    <input  type="tel" name="phone" value="{{ old('phone')?old('phone'): $user->phone }}" required class="form-control editprofile-input"  aria-describedby="emailHelp" placeholder="Enter Phone Number" />
                </div>
            </div>
            <div class="Category-span">
                <div class="form-group col-lg-12">
                    <span for="inputState" class="Select-control">Zip-Code</span>
                    <input type="number" name="zip_code" value="{{ old('zip_code')?old('zip_code'): $user->zip_code  }}" class="form-control editprofile-input" aria-describedby="emailHelp" placeholder="Enter Zip-Code" />
                </div>
            </div>
            <div class="Category-span">
                <div class="form-group col-lg-12">
                    <span for="inputState" class="Select-control">Address</span>
                    <input type="text" class="form-control editprofile-input" name="address" value="{{ old('address')?old('address'): $user->address }}" aria-describedby="emailHelp" placeholder="Enter Address" />
                </div>
            </div>

            <div class="Category-span">
                <div class="form-group col-lg-12">
                    <span for="inputState" class="Select-control">City</span>
                    <input type="text" class="form-control editprofile-input" name="city" id="city" value="{{$user->city ? $user->city:''}}" aria-describedby="emailHelp" placeholder="Type to search" />
                </div>
            </div>

                <div class="Category-span">
                <div class="form-group col-lg-12">
                    <span for="inputState" class="Select-control">Date of birth</span>
                    <input class="form-control editprofile-input"  type="date" name="user_dob" value="{{ old('user_dob')?old('user_dob'): $user->user_dob }}" aria-describedby="emailHelp" placeholder="Enter Your Date of Birth" />
                    <!-- <span class="calnder"><i class="fa fa-calendar" aria-hidden="true"></i></span> -->
                </div>
            </div>
            

            <div class="Category-span select-cat-edit">
                <div class="form-group col-lg-12">
                    <span for="inputState" class="Select-control">Country</span>
                    <select name="country_id" required class="form-control input-control">
                        <!-- <option disabled value="" selected>Select Country </option> -->
                        @foreach($countries as $key => $country)
                        @if($user->country_id)
                        <option value="{{ $country->id }}"
                            {{ $country->id == $user->country_id ? 'selected' : '' }}>{{ $country->name }}</option>
                        @else
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary Search">Update & Continue</button>
        </form>
    </div>
</section>

<!--bitcoin Edit Profile Strat--->

@stop
@section('script')
<script>
$(document).ready(function() {
    setTimeout(function() {
        window.location
            .reload(); // you can pass true to reload function to ignore the client cache and reload from the server
    }, 200000); //delayTime should be written in milliseconds e.g. 1000 which equals 1 second
    $("#id_photo").change(function() {
        console.log('hiiiii')
        readURL(this, '#id_photo_Preview');
    });
    $("#address_photo").change(function() {
        console.log('hiiiii')
        readURL(this, '#address_photo_Preview');
    });
    $("#id_photo_id").change(function() {
        console.log('hiiiii')
        readURL(this, '#id_photo_id_Preview');
    });

    function readURL(input, previewId) {
        
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $(previewId).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
});
$(document).ready(function() {
    $("#city").keyup(function(event) {
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

            success: function(data) {
                console.log(data);
                var availableWords = data;
                autocomplete(document.getElementById("city"), availableWords);
            },
            error: function(data) {
                console.log(data);

            }

        });

    });
});

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
        b.addEventListener("click", function(e) {
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
    inp.addEventListener("keydown", function(e) {
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
    document.addEventListener("click", function(e) {
        closeAllLists(e.target);
    });
}
</script>
@endsection