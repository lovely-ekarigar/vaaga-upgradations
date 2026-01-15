@extends('backend.layouts.app')
@section('title', __('labels.backend.teachers.title').' | '.app_name())

@section('content')
    {{ html()->modelForm($teacher, 'PATCH', route('admin.teachers.update', $teacher->id))->class('form-horizontal')->acceptsFiles()->open() }}

    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline">Edit Tutor</h3>
            <div class="float-right">
                <a href="{{ route('admin.teachers.index') }}"
                   class="btn btn-success">View Tutor</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.teachers.fields.first_name'))->class('col-md-2 form-control-label')->for('first_name') }}

                        <div class="col-md-10">
                            {{ html()->text('first_name')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.teachers.fields.first_name'))
                                ->attribute('maxlength', 191)
                                ->required()
                                ->autofocus() }}
                        </div>
                    </div><!--form-group-->
                    <div class="form-group row">
                        {{ html()->label("Middle Name")->class('col-md-2 form-control-label')->for('middle_name') }}

                        <div class="col-md-10">
                            {{ html()->text('middle_name')
                                ->class('form-control')
                                ->value($teacher->middle_name)
                                ->attribute('maxlength', 191) }}
                        </div>
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.teachers.fields.last_name'))->class('col-md-2 form-control-label')->for('last_name') }}

                        <div class="col-md-10">
                            {{ html()->text('last_name')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.teachers.fields.last_name'))
                                ->attribute('maxlength', 191)
                                ->required() }}
                        </div>
                    </div><!--form-group-->
                    <!-- <div class="form-group row">
                        {{ html()->label("Title")->class('col-md-2 form-control-label')->for('title') }}

                        <div class="col-md-10">
                            {{ html()->text('title')
                                ->class('form-control')
                                ->placeholder("Title")
                                ->attribute('maxlength', 191) }}
                        </div>
                    </div> -->
                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.teachers.fields.email'))->class('col-md-2 form-control-label')->for('email') }}

                        <div class="col-md-10">
                            {{ html()->email('email')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.teachers.fields.email'))
                                ->attributes(['maxlength'=> 191,'readonly'=>true])
                                ->required() }}
                        </div>
                    </div><!--form-group-->
                    <div class="form-group row">
                        {{ html()->label('Phone')->class('col-md-2 form-control-label')->for('phone') }}

                        <div class="col-md-10">
                            {{ html()->number('phone')
                                ->class('form-control')
                                ->value($teacher->phone)
                                ->attributes(['maxlength'=> 191,'readonly'=>true])
                                ->required() }}
                        </div>
                    </div><!--form-group-->
                    <div class="form-group row">
                        {{ html()->label("Date of Birth")->class('col-md-2 form-control-label')->for('dob') }}

                        <div class="col-md-10">
                            {{ html()->date('dob')
                                ->class('form-control')
                                ->value($teacher->dob)
                                ->required() }}
                        </div>
                    </div><!--form-group-->
                    <div class="form-group row">
                        {{ html()->label("Country")->class('col-md-2 form-control-label')->for('country') }}

                        <div class="col-md-10">
                            {{ html()->text('country')
                                ->class('form-control')
                                ->value($teacher->country)
                                ->required() }}
                        </div>
                    </div><!--form-group-->
                    <div class="form-group row">
                        {{ html()->label("City")->class('col-md-2 form-control-label')->for('city') }}

                        <div class="col-md-10">
                            {{ html()->text('city')
                                ->class('form-control')
                                ->value($teacher->city)
                                ->required() }}
                        </div>
                    </div><!--form-group-->
                    <div class="form-group row">
                        {{ html()->label("State")->class('col-md-2 form-control-label')->for('state') }}

                        <div class="col-md-10">
                            {{ html()->text('state')
                                ->class('form-control')
                                ->value($teacher->state)
                                ->required() }}
                        </div>
                    </div><!--form-group-->


                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.teachers.fields.password'))->class('col-md-2 form-control-label')->for('password') }}

                        <div class="col-md-10">
                            {{ html()->password('password')
                                ->class('form-control')
                                ->value('')
                                ->placeholder(__('labels.backend.teachers.fields.password'))
}}
                        </div>
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.teachers.fields.image'))->class('col-md-2 form-control-label')->for('image') }}

                        <div class="col-md-10 ">
                            {!! Form::file('image', ['class' => 'form-control d-inline-block', 'placeholder' => '','accept'=>'image/*','style' => ';']) !!}
                            <!-- <span class="ml-2 d-block"> <a href="" class="btn btn-primary">View</a></span> -->
                            <span style="color: #aaa; " class="pt-2">Image extension should be <b>jpg, jpeg, png</b> only</span>
                        </div>

                    </div><!--form-group-->

                    @php
                        $teacherProfile = $teacher->teacherProfile?:'';
                        $payment_details = $teacher->teacherProfile?json_decode($teacher->teacherProfile->payment_details):new stdClass();
                    @endphp

                    <div class="form-group row">
                        <label class="col-md-2 form-control-label" for="image">CV</label>

                        <div class="col-md-10 d-flex align-items-center">
                        {!! Form::file('upload_cv', ['class' => 'form-control d-inline-block', 'placeholder' => '','accept'=>'image/*','style' => 'width:90%;']) !!}

                        <?php if ($teacherProfile->upload_cv): ?>
                            <span class="ml-2"> <a href="{{asset($teacherProfile->upload_cv)}}" target="_blank" class="btn btn-primary" style="">View</a></span>
                            
                     <?php endif; ?>
                   
                        </div>

                    </div><!--form-group-->

                    <div class="form-group row">
                        <label class="col-md-2 form-control-label" for="aadhar_card">Aadhar Card</label>

                        <div class="col-md-10 d-flex align-items-center">
                        {!! Form::file('aadhar_card', ['class' => 'form-control d-inline-block', 'placeholder' => '','accept'=>'image/*','style' => 'width:90%;']) !!}

                        <?php if ($teacherProfile->aadhar_card): ?>
                         <span class="ml-2"> <a href="{{asset($teacherProfile->aadhar_card)}}" target="_blank" class="btn btn-primary" style="">View</a></span>
                
                    <?php endif; ?>
                        </div>

                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 form-control-label" for="pan_card">Pan Card</label>

                        <div class="col-md-10 d-flex align-items-center">
                        {!! Form::file('pan_card', ['class' => 'form-control d-inline-block', 'placeholder' => '','accept'=>'image/*','style' => 'width:90%;']) !!}

                        <?php if ($teacherProfile->pan_card): ?>
                         <span class="ml-2"> <a href="{{asset($teacherProfile->pan_card)}}" target="_blank" class="btn btn-primary" style="">View</a></span>
                
                    <?php endif; ?>
                        </div>

                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 form-control-label" for="photo_id">Photo Id Proof</label>

                        <div class="col-md-10 d-flex align-items-center">
                        {!! Form::file('photo_id_proof', ['class' => 'form-control d-inline-block', 'placeholder' => '','accept'=>'image/*','style' => 'width:90%;']) !!}

                        <?php if ($teacherProfile->photo_id_proof): ?>
                         <span class="ml-2"> <a href="{{asset($teacherProfile->photo_id_proof)}}" target="_blank" class="btn btn-primary" style="">View</a></span>
                
                    <?php endif; ?>
                        </div>

                    </div>

                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.general_settings.user_registration_settings.fields.gender'))->class('col-md-2 form-control-label')->for('gender') }}
                        <div class="col-md-10">
                            <label class="radio-inline mr-3 mb-0">
                                <input type="radio" name="gender" value="male" {{ $teacher->gender == 'male'?'checked':'' }}> {{__('validation.attributes.frontend.male')}}
                            </label>
                            <label class="radio-inline mr-3 mb-0">
                                <input type="radio" name="gender" value="female" {{ $teacher->gender == 'female'?'checked':'' }}> {{__('validation.attributes.frontend.female')}}
                            </label>
                           <!--  <label class="radio-inline mr-3 mb-0">
                                <input type="radio" name="gender" value="other" {{ $teacher->gender == 'other'?'checked':'' }}> {{__('validation.attributes.frontend.other')}}
                            </label> -->
                        </div>
                    </div>

                  

                     <div class="form-group row">
                        {{ html()->label("Highest Qualification")->class('col-md-2 form-control-label')->for('hig_qualification') }}

                        <div class="col-md-10">
                            {{ html()->text('hig_qualification')
                                ->class('form-control')
                                ->value($teacherProfile->hig_qualification)
                                ->required() }}
                        </div>
                    </div><!--form-group-->
                     <div class="form-group row">
                        {{ html()->label("Total Experience")->class('col-md-2 form-control-label')->for('total_exp') }}

                        <div class="col-md-10">
                            {{ html()->text('total_exp')
                                ->class('form-control')
                                ->value($teacherProfile->total_exp)
                                ->required() }}
                        </div>
                    </div><!--form-group-->
                     <div class="form-group row">
                        {{ html()->label("Relevant Experience")->class('col-md-2 form-control-label')->for('relevant_exp') }}

                        <div class="col-md-10">
                            {{ html()->text('relevant_exp')
                                ->class('form-control')
                                ->value($teacherProfile->relevant_exp) }}
                        </div>
                    </div><!--form-group-->
                     <div class="form-group row">
                        {{ html()->label("Subjects you would like to teach")->class('col-md-2 form-control-label')->for('subject_teach') }}

                        <div class="col-md-10">
                            {{ html()->text('subject_teach')
                                ->class('form-control')
                                ->value($teacherProfile->subject_teach)
                                ->required() }}
                        </div>
                    </div><!--form-group-->

                     <div class="form-group row">
                        {{ html()->label("Subject Display")->class('col-md-2 form-control-label')->for('subject_teach') }}

                        <div class="col-md-10">
                            {{ html()->text('subject')
                                ->class('form-control')
                                ->value($teacherProfile->subject) }}
                        </div>
                    </div><!--form-group-->


                     <div class="form-group row">
                        {{ html()->label("Grades you would like to teach")->class('col-md-2 form-control-label')->for('grade_teach') }}

                        <div class="col-md-10">
                            {{ html()->text('grade_teach')
                                ->class('form-control')
                                ->value($teacherProfile->grade_teach)
                                ->required() }}
                        </div>
                    </div><!--form-group-->
                     <div class="form-group row">
                        {{ html()->label("Language proficiency")->class('col-md-2 form-control-label')->for('lang_proficiency') }}

                        <div class="col-md-10">
                            {{ html()->text('lang_proficiency')
                                ->class('form-control')
                                ->value($teacherProfile->lang_proficiency) }}
                        </div>
                    </div><!--form-group-->

                   <!--  <div class="form-group row">
                        <label class="col-md-2 form-control-label" for="base_salary">Base Salary <span style="font-size: 10px;color: #5c6873;">(Per Hour)</span></label>

                        <div class="col-md-10">
                            <input class="form-control" type="text" name="base_salary" id="base_salary" value="{{$teacherProfile->base_salary}}">
                        </div>
                    </div> -->


                    

                    <div class="form-group row">
                        {{ html()->label(__('labels.teacher.facebook_link'))->class('col-md-2 form-control-label')->for('facebook_link') }}

                        <div class="col-md-10">
                            {{ html()->text('facebook_link')
                                            ->class('form-control')
                                            ->value($teacherProfile->facebook_link)
                                            ->placeholder(__('labels.teacher.facebook_link')) }}
                        </div>
                    </div>
                    
                      <div class="form-group row">
                        {{ html()->label("Instagram Link")->class('col-md-2 form-control-label')->for('instagram_link') }}

                        <div class="col-md-10">
                            {{ html()->text('instagram_link')
                                            ->class('form-control')
                                            ->value($teacherProfile->instagram_link)
                                            ->placeholder("Instagram Link") }}
                        </div>
                    </div>


                    <div class="form-group row">
                        {{ html()->label(__('labels.teacher.twitter_link'))->class('col-md-2 form-control-label')->for('twitter_link') }}

                        <div class="col-md-10">
                            {{ html()->text('twitter_link')
                                            ->class('form-control')
                                            ->value($teacherProfile->twitter_link)
                                            ->placeholder(__('labels.teacher.twitter_link')) }}

                        </div>
                    </div>

                    <div class="form-group row">
                        {{ html()->label(__('labels.teacher.linkedin_link'))->class('col-md-2 form-control-label')->for('linkedin_link') }}

                        <div class="col-md-10">
                            {{ html()->text('linkedin_link')
                                            ->class('form-control')
                                            ->value($teacherProfile->linkedin_link)
                                            ->placeholder(__('labels.teacher.linkedin_link')) }}
                        </div>
                    </div>
                    @if($teacherProfile->payment_method)
                    <div class="form-group row">
                        {{ html()->label(__('labels.teacher.payment_details'))->class('col-md-2 form-control-label')->for('payment_details') }}
                        <div class="col-md-10">
                            <select class="form-control" name="payment_method" id="payment_method" required>
                                <option value="bank" {{ $teacherProfile->payment_method == 'bank'?'selected':'' }}>{{ trans('labels.teacher.bank') }}</option>
                                <!-- <option value="paypal" {{ $teacherProfile->payment_method == 'paypal'?'selected':'' }}>{{ trans('labels.teacher.paypal') }}</option> -->
                            </select>
                        </div>

                    </div>
                    @endif
                     @if($payment_details)
                    <div class="bank_details" style="display:{{ $teacher->teacherProfile->payment_method == 'bank'?'':'none' }}">
                        <div class="form-group row">
                            {{ html()->label(__('labels.teacher.bank_details.name'))->class('col-md-2 form-control-label')->for('bank_name') }}
                            <div class="col-md-10">
                                {{ html()->text('bank_name')
                                        ->class('form-control')
                                        ->value($payment_details->bank_name)
                                        ->placeholder(__('labels.teacher.bank_details.name')) }}
                            </div>
                        </div>

                        <div class="form-group row">
                            {{ html()->label(__('labels.teacher.bank_details.ifsc_code'))->class('col-md-2 form-control-label')->for('ifsc_code') }}
                            <div class="col-md-10">
                                {{ html()->text('ifsc_code')
                                        ->class('form-control')
                                        ->value($payment_details->ifsc_code)
                                        ->placeholder(__('labels.teacher.bank_details.ifsc_code')) }}
                            </div>
                        </div>

                        <div class="form-group row">
                            {{ html()->label(__('labels.teacher.bank_details.account'))->class('col-md-2 form-control-label')->for('account_number') }}
                            <div class="col-md-10">
                                {{ html()->text('account_number')
                                        ->class('form-control')
                                        ->value($payment_details->account_number)
                                        ->placeholder(__('labels.teacher.bank_details.account')) }}
                            </div>
                        </div>

                        <div class="form-group row">
                            {{ html()->label(__('labels.teacher.bank_details.holder_name'))->class('col-md-2 form-control-label')->for('account_name') }}
                            <div class="col-md-10">
                                {{ html()->text('account_name')
                                        ->class('form-control')
                                        ->value($payment_details->account_name)
                                        ->placeholder(__('labels.teacher.bank_details.holder_name')) }}
                            </div>
                        </div>
                    </div>

                    
                    @endif

                    <div class="form-group row">
                        {{ html()->label(__('labels.teacher.description'))->class('col-md-2 form-control-label')->for('description') }}

                        <div class="col-md-10">
                            {{ html()->textarea('description')
                                    ->class('form-control')
                                    ->value($teacherProfile->description)
                                    ->placeholder(__('labels.teacher.description')) }}
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.teachers.fields.status'))->class('col-md-2 form-control-label')->for('active') }}
                        <div class="col-md-10">
                            {{ html()->label(html()->checkbox('')->name('active')
                                        ->checked(($teacher->active == 1) ? true : false)->class('switch-input')->value(($teacher->active == 1) ? 1 : 0)

                                    . '<span class="switch-label"></span><span class="switch-handle"></span>')
                                ->class('switch switch-lg switch-3d switch-primary')
                            }}
                        </div>

                    </div>
                     <!-- <div class="form-group row">
                        {{ html()->label("Featured on Home")->class('col-md-2 form-control-label')->for('on_home') }}
                        <div class="col-md-10">
                            <label class="radio-inline mr-3 mb-0">
                                <input type="radio" name="on_home" value="0" {{ $teacher->on_home == '0'?'checked':'' }}> No
                            </label>
                            <label class="radio-inline mr-3 mb-0">
                                <input type="radio" name="on_home" value="1" {{ $teacher->on_home == '1'?'checked':'' }}> Yes
                            </label>
                           
                        </div>
                    </div> -->
                    
                    
                   
                    


                    <div class="form-group row justify-content-center">
                        <div class="col-4">
                            {{ form_cancel(route('admin.teachers.index'), __('buttons.general.cancel')) }}
                            {{ form_submit(__('buttons.general.crud.update')) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    {{ html()->closeModelForm() }}
@endsection
@push('after-scripts')
    <script>
        $(document).on('change', '#payment_method', function(){
            if($(this).val() === 'bank'){
                $('.paypal_details').hide();
                $('.bank_details').show();
            }else{
                $('.paypal_details').show();
                $('.bank_details').hide();
            }
        });
    </script>
@endpush