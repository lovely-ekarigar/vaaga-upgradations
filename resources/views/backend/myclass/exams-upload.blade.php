@extends('frontend.layout.sub-master')
@section('title')
<title>Subjective Exam upload for {{$batch->name}} } | {{env('APP_NAME')}}</title>
@stop
@section('content')

    @include("frontend.include.user-menu")
        </div>
        <div class="col-lg-8 col-xl-9">
          <div class="profile-content-area my-6 card card-body">
            <div class=" mb-6 pb-6">
              <h3 class="mb-2">@lang('strings.backend.dashboard.welcome') {{ $logged_in_user->name }}!</h3>
              <h6 class="text-body fw-500 mb-3">Subjective Exam for {{$batch->name}} </h6>
              <h6 class="text-body fw-500 mb-3">Subjective Exam Name:    {{$assig->title}} </h6>
                <div class="row"> 
                	<div class="col-md-2"></div>
                	<div class="col-md-8">
                		@if(!$upload)
                	<form  method="post" enctype="multipart/form-data">
                		@csrf

                		<input type="file" name="file" required class="form-control form-file " accept=".docx,.pdf">
                		<label class="mb-3">Only .pdf and .docx file allowed. File should be less than 10MB</label>

                		<input type="submit" name="submit" value="Upload Subjective Exam" class="btn btn-sm btn-primary">


                	</form>
                	@else

                	<h5>Your subjective exam has been uploaded at {{date('d M Y',strtotime($upload->created_at))}}</h5>

                	@endif
                </div>
                   
                </div>
            </div>
          
           
           
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End Section -->
</main>

@stop