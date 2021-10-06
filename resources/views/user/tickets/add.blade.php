@extends('front.layout.master2')
{!! NoCaptcha::renderJs() !!}
@section('body')
<!--bitcoin blance Strat--->
<section class="bitcoin-blacnce">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 balane">
                <p><span>Create Support Ticket</span></p>
            </div>
        </div>
    </div>
</section>

<!--bitcoin blance Strat--->


<!--Bitcoin Create Add Form-->
<section class="pt-60 create-support">
    <div class="container">
        <div class="row">
        <form action="{{route('ticket.store')}}" enctype="multipart/form-data" method="post">
        @csrf
        @if (count($errors) > 0)
            <div class="col-md-12">
                <div class="alert alert-danger alert-dismissible">
                    <!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> -->
                    <strong class="col-md-12"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Alert!</strong>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </div>
            </div>
        @endif
            <div class="form-div pb-30">
                <div class="form-group col-md-12">
                        <span for="exampleInputEmail1" class="Select-control">Subject Name:</span>
                            <input type="text" class="form-control change-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Subject Name" value="{{ old('subject') }}" required name="subject" />
                    </div>
                <div class="form-group col-md-12">
                    <span for="comment" class="Select-control">Message: </span>
                    <textarea class="form-control input-control1 reporting" rows="5" name="detail" id="comment" placeholder="Write Here Your Message...">{!! old('detail') !!}</textarea>
                </div>
                
                    <div class="form-group col-md-12">
                    <span for="issue" class="Select-control">Issue: </span>
                    <select required id="issue" name="issue" class="form-control input-control reporting">
                        <option disabled value="" selected>Select Issue</option>
                        <option value="Reporting a fraud user">Reporting a fraud user</option>
                        <option value="Reporting a fraud trade">Reporting a fraud trade</option>
                        <option value="My account is hacked">My account is hacked</option>
                        <option value="Account related problem">Account related problem</option>
                        <option value="Reporting a bug">Reporting a bug</option>
                        <option value="Verification problem">Verification problem</option>
                        <option value="Feature request">Feature request</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                    <div class="form-group col-md-12">
                    <span for="inputState" class="Select-control">ReplyTo: </span>
                    <input class="form-control input-control" type="email"  value="{{ old('replyto') }}" required name="replyto" placeholder="Email">
                </div>
                <div class="form-group m-4">
                {!! app('captcha')->display() !!}
                </div>
                    <div class="row pt-30">
                        <div class="col-lg-8 choose-file">
                            <input type="file" name="files" id="file" accept="image/*"/>
                            <label for="file">Choose File</label>
                            <span><span class="upload">Upload Document </span>(PNG , JPG and JPEG files only, take a screenshot if necessary)</span>
                        </div>
                        
                        <div class="col-lg-3 pt-20">
                            <button type="submit" class="btn btn-primary send">Post</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection