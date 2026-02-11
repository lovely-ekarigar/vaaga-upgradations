<style>
    .modal-content {
        border-radius: 10px;
        overflow: hidden;
    }

    .modal-content .close {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 10;
        background: none;
        border: none;
        font-size: 1.5rem;
    }

    .image-container {
        background: #f9f9f9;
        text-align: center;
        position: relative;
    }

    .image-container img {
        max-width: 100%;
        height: auto;
        object-fit: cover;
        /*clip-path: circle(50% at center);*/
    }

    .form-control {
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .btn-primary {
        /*background-color: #ff7043;*/
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
    }

</style>
@if(!auth()->check())

   <div class="modal fade" id="enquiryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="row no-gutters">
                <!-- Left Side with Image -->
                

                <!-- Right Side with Form -->
                <div class="col-md-12 p-4">
                    <h3 class="text-center font-weight-bold">Enquiry Form</h3>
                    <!-- <p class="text-center">How Can We Nurture Your Child’s Brilliance?</p> -->
                    <form id="registerForm" class="contact_form" action="{{route('home.submitForm')}}" method="POST">
                        @csrf
                        @if ($errors->any())
                                 <div class="alert alert-danger" role="alert">
                             @foreach ($errors->all() as $error)
                                     {{$error}}
                             @endforeach
                                     </div>
                         @endif
                        
                        <div class="row">
                                <div class="contact-info mb-2 col-md-6">
                                    <div class="form-group">
                                        <label>Name</label><span class="text-danger">*</span>
                                        <input type="text" name="name" value="{{old('name')}}" class="form-control" maxlength="191" required >
                                    </div>
                                </div>
                                <div class="contact-info mb-2 col-md-6">
                                    <div class="form-group">
                                        <label>Grade</label><span class="text-danger">*</span>
                                        <input type="text" name="grade" value="{{old('grade')}}" class="form-control" maxlength="191" required >
                                    </div>
                                </div>
                                <div class="contact-info mb-2 col-md-6">
                                    <div class="form-group">
                                        <label>Phone number</label><span class="text-danger">*</span>
                                        <input type="tel" name="mobile" class="form-control" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" maxlength="10" pattern="\d{10}" required value="{{old('mobile')}}" >
                                    </div>
                                </div>
                                <div class="contact-info mb-2 col-md-6">
                                    <div class="form-group">
                                        <label>Email id </label><span class="text-danger">*</span>
                                        <input type="email" name="email" value="{{old('email')}}" class="form-control" maxlength="191" required >
                                    </div>
                                </div>
                                <div class="contact-info mb-2 col-md-6">
                                    <div class="form-group">
                                        <label>Interested for (which olympiad)</label><span class="text-danger">*</span>
                                        <input type="text" name="insterested" value="{{old('insterested')}}" class="form-control" maxlength="191" required >
                                    </div>
                                </div>
                                <div class="contact-info mb-2 col-md-6">
                                    <div class="mb-3">
                                        <label>Gender</label><span class="text-danger">*</span>
                                        <div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gender" id="genderMale" value="male" required="">
                                                <label class="form-check-label" for="genderMale">Male</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio"  name="gender" id="genderFemale" value="female" required="">
                                                <label class="form-check-label" for="genderFemale">Female</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="contact-info mb-2  w-50 pt-4 ">
                                    <div class="nws-button  white text-capitalize ">
                                        <div class="g-recaptcha mb-3" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>

                                    </div>
                                </div>
                            </div>
                        <div class="">
                            <button type="submit" class="btn btn-primary btn-block">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endif

@push('after-scripts')
   <script src="https://www.google.com/recaptcha/api.js" async defer></script>


@endpush
