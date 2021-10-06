@extends( 'front.layout.master2' )
@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@stop
@section('body')
<style>
.verfied {
    border: 2px solid #4BB543;
    border-radius: 5px;
    padding: 5px 3px;
}

.unverfied {
    border: 2px solid #cc3300;
    border-radius: 5px;
    padding: 5px 3px;
}

.autocomplete-items {
    display: contents;
}
</style>
<!--bitcoin blance Strat--->
<section class="bitcoin-blacnce">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 balane">
                <p><span>Edit Your Profile</span></p>
            </div>
        </div>
    </div>
</section>

<!--bitcoin blance Strat--->
<!--bitcoin Edit Profile Strat--->
<section class="mb-5 pt-30">
    <div class="container">
        @if (count($errors) > 0)
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible">
                        <!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> -->
                        <h12><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Alert!</h12>
                        @foreach ($errors as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
        <form method="post" id="step-2" action="/user/{{$user->username}}/edit-profile/step-2" enctype="multipart/form-data">
            <div class="row">
                @csrf
                @method('put')
                <div class="col-lg-12">
                    <div class="img">
                        <span class="img-uplod"><i class="fa fa-bullhorn"></i> Image upload instructions</span>
                        <ul class="upload-doc">
                            <li>Upload a color image of the entire document.</li>
                            <li>Screenshots are not allowed.</li>
                            <li>JPG, JPEG or PNG format only. [Maximum Size:4mb]</li>

                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 pt-30">
                    <div class="img">
                        <span class="img-uplod"> <i class="fa fa-bullhorn"></i>  ID verification</span>
                        <p>Please upload a copy of one of the following:</p>
                        <ul class="upload-doc">
                            <li>Passport.</li>
                            <li>Driving License.</li>
                            <li>Any government-issued ID with a photo.</li>
                        </ul>
                    </div>
                    <div class="col-lg-12 pt-30">
                        <div class="upload-plus">
                            <div class="input-file-container">  
                                <input class="input-file"  type="file" accept="image/*" name="id_photo" id="id_photo" />
                                <label tabindex="0" for="id_photo" class="input-file-trigger" id="input-file-trigger">+</label>
                            </div>  
                            <p class="file-return" id="abc"></p>
                        </div>
                        @if($user->id_photo_status ==1)
                            <div class="approved">Verified</div>
                        @elseif($user->id_photo_status ==0)
                            <div class="panding">Unverified</div>
                        @else
                            <div class="rejected">Rejected</div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-4 pt-30">
                    <div class="img">
                        <span class="img-uplod"> <i class="fa fa-bullhorn"></i>  Address verification</span>
                        <p>Any letter or invoice showing your full name and full address. The address on the document must match the address entered by you.</p>
                    </div>
                    <div class="col-lg-12 pt-30">
                        <div class="upload-plus">
                            <div class="input-file-container">  
                                <input class="input-file" type="file" accept="image/*" name="address_photo" id="address_photo" />
                                <label tabindex="0" for="address_photo" class="input-file-trigger" id="input-file-trigger1">+</label>
                            </div>
                            <p class="file-return" id="abc1"></p>
                        </div>
                        @if($user->address_photo_status ==1)
                            <div class="approved">Verified</div>
                        @elseif($user->address_photo_status ==0)
                            <div class="panding">Unverified</div>
                        @else
                            <div class="rejected">Rejected</div>
                        @endif
                    </div>
                </div>      
                <div class="col-lg-4 pt-30">
                    <div class="img">
                        <span class="img-uplod"> <i class="fa fa-bullhorn"></i>  Image of your ID beside your face</span>
                        <img style="width: 250px;height: auto;margin-left: 5rem;" src="{{asset('new-images/upload_placeholder.png')}}" alt="Placeholder">
                    </div>
                    <div class="col-lg-12 pt-30">
                        <div class="upload-plus">
                            <div class="input-file-container">  
                                <input class="input-file" type="file" accept="image/*" name="id_photo_id" id="id_photo_id" />
                                <label tabindex="0" for="id_photo_id" class="input-file-trigger" id="input-file-trigger2">+</label>
                            </div>
                            <p class="file-return" id="abc2"></p>
                        </div>
                        @if($user->id_photo_id_status ==1)
                            <div class="approved">Verified</div>
                        @elseif($user->id_photo_id_status ==0)
                            <div class="panding">Unverified</div>
                        @else
                            <div class="rejected">Rejected</div>
                        @endif
                    </div>
                </div>
            </div>
                    <!---Div uplod doc-->
                        
            <button type="submit" id="submit" class="btn btn-primary Search">Complete</button>
        </form>
    </div>
</section>

<script>
    document.querySelector("html").classList.add('js');

    var fileInput  = document.querySelector( "#id_photo_id" ),  
        button     = document.querySelector( "#input-file-trigger2" ),
        the_return = document.querySelector("#abc2");
            
    button.addEventListener( "keydown", function( event ) {  
        if ( event.keyCode == 13 || event.keyCode == 32 ) {  
            fileInput.focus();  
        }  
    });
    button.addEventListener( "click", function( event ) {
        fileInput.focus();
        return false;
    });  
    fileInput.addEventListener( "change", function( event ) {  
        the_return.innerHTML = this.value;  
    });  

</script>
<script>
    document.querySelector("html").classList.add('js');

    var fileInput  = document.querySelector( "#id_photo" ),  
        button     = document.querySelector( "#input-file-trigger" ),
        the_return = document.querySelector("#abc");
            
    button.addEventListener( "keydown", function( event ) {  
        if ( event.keyCode == 13 || event.keyCode == 32 ) {  
            fileInput.focus();  
        }  
    });
    button.addEventListener( "click", function( event ) {
        fileInput.focus();
        return false;
    });  
    fileInput.addEventListener( "change", function( event ) {  
        the_return.innerHTML = this.value;  
    }); 
</script>
<script>
    document.querySelector("html").classList.add('js');

    var fileInput  = document.querySelector( "#address_photo" ),  
        button     = document.querySelector( "#input-file-trigger1" ),
        the_return = document.querySelector("#abc1");
            
    button.addEventListener( "keydown", function( event ) {  
        if ( event.keyCode == 13 || event.keyCode == 32 ) {  
            fileInput.focus();  
        }  
    });
    button.addEventListener( "click", function( event ) {
        fileInput.focus();
        return false;
    });  
    fileInput.addEventListener( "change", function( event ) {  
        the_return.innerHTML = this.value;  
    }); 

</script>
<script type="text/javascript">
        document.getElementById('id_photo').onchange = function () {

        $("#abc").html(this.value)
    };
</script>
<script type="text/javascript">
        document.getElementById('address_photo').onchange = function () {

        $("#abc1").html(this.value)
    };
</script>
<script type="text/javascript">
        document.getElementById('id_photo_id').onchange = function () {

        $("#abc2").html(this.value)
    };
</script>

<script type="text/javascript">
    function submitForm() {
        document.getElementById('submit').disabled = true;
        // alert('clicked');
        // document.getElementById('step-2').submit();
        document.forms["step-2"].submit();
    }
</script>
    <!--bitcoin Edit Profile Strat--->
@stop