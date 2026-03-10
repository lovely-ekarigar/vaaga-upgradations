{{ html()->modelForm($user, 'PATCH', route('admin.profile.update'))->class('form-horizontal')->attribute('enctype', 'multipart/form-data')->open() }}
<div class="row">
    <div class="col">
        <div class="form-group">
            {{ html()->label(__('validation.attributes.frontend.avatar'))->for('avatar') }}

            <div>
                <input type="radio" name="avatar_type"
                       value="gravatar" {{ $user->avatar_type == 'gravatar' ? 'checked' : '' }} /> {{__('validation.attributes.frontend.gravatar')}}
                &nbsp;&nbsp;
                <input type="radio" name="avatar_type"
                       value="storage" {{ $user->avatar_type == 'storage' ? 'checked' : '' }} /> {{__('validation.attributes.frontend.upload')}}
  <span style="color: #aaa;" class="pt-2">Image extension should be <b>jpg, jpeg, png</b> only</span>
                @foreach($user->providers as $provider)
                    @if(strlen($provider->avatar))
                        <input type="radio" name="avatar_type"
                               value="{{ $provider->provider }}" {{ $user->avatar_type == $provider->provider ? 'checked' : '' }} /> {{ ucfirst($provider->provider) }}
                    @endif
                @endforeach
            </div>
        </div><!--form-group-->

        <div class="form-group hidden" id="avatar_location">
           

            <input class="form-control" type="file" name="avatar_location" id="avatar_location" accept="image/*">
        </div><!--form-group-->

    </div><!--col-->
</div><!--row-->

<div class="row">
    <div class="col">
        <div class="form-group">
            {{ html()->label(__('validation.attributes.frontend.first_name'))->for('first_name') }}

            {{ html()->text('first_name')
                ->class('form-control')
                ->placeholder(__('validation.attributes.frontend.first_name'))
                ->attribute('maxlength', 191)
                ->required()
                ->autofocus() }}
        </div><!--form-group-->
    </div><!--col-->
</div><!--row-->

<div class="row">
    <div class="col">
        <div class="form-group">
        <label for="middle_name">Middle Name</label>

            {{ html()->text('middle_name')
                ->class('form-control')
                ->placeholder('Middle Name')
                ->attribute('maxlength', 191)
                ->required()
                ->autofocus() }}
        </div><!--form-group-->
    </div><!--col-->
</div><!--row-->

<!-- <div class="row">
    <div class="col">
        <div class="form-group">
           <label for="middle_name">Middle Name</label>
           <input class="form-control" type="text" name="middle_name" id="middle_name" value="{{$user->middle_name}}" >
        </div>
        
    </div>
    
</div> -->


<div class="row">
    <div class="col">
        <div class="form-group">
            {{ html()->label(__('validation.attributes.frontend.last_name'))->for('last_name') }}

            {{ html()->text('last_name')
                ->class('form-control')
                ->placeholder(__('validation.attributes.frontend.last_name'))
                ->attribute('maxlength', 191)
                ->required() }}
        </div><!--form-group-->
    </div><!--col-->
</div><!--row-->
@if($user->hasRole('teacher'))
    @php
        $teacherProfile = $user->teacherProfile?:'';
        $payment_details = $user->teacherProfile?json_decode($user->teacherProfile->payment_details):optional();
    @endphp
    <div class="row">
        <div class="col">
            <div class="form-group">
                {{ html()->label(__('labels.backend.general_settings.user_registration_settings.fields.gender'))->for('gender') }}
                <div class="">
                    <label class="radio-inline mr-3 mb-0">
                        <input type="radio" name="gender" value="male" {{ $user->gender == 'male'?'checked':'' }}> {{__('validation.attributes.frontend.male')}}
                    </label>
                    <label class="radio-inline mr-3 mb-0">
                        <input type="radio" name="gender" value="female" {{ $user->gender == 'female'?'checked':'' }}> {{__('validation.attributes.frontend.female')}}
                    </label>
                    <label class="radio-inline mr-3 mb-0">
                        <input type="radio" name="gender" value="other" {{ $user->gender == 'other'?'checked':'' }}> {{__('validation.attributes.frontend.other')}}
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group">
                {{ html()->label(__('labels.teacher.facebook_link'))->for('facebook_link') }}

                {{ html()->text('facebook_link')
                    ->class('form-control')
                    ->value($teacherProfile->facebook_link)
                    ->placeholder(__('labels.teacher.facebook_link'))
                }}
            </div><!--form-group-->
        </div><!--col-->
    </div><!--row-->

    <div class="row">
        <div class="col">
            <div class="form-group">
                {{ html()->label(__('labels.teacher.twitter_link'))->for('twitter_link') }}

                {{ html()->text('twitter_link')
                    ->class('form-control')
                    ->value($teacherProfile->twitter_link)
                    ->placeholder(__('labels.teacher.twitter_link'))
                }}
            </div><!--form-group-->
        </div><!--col-->
    </div><!--row-->

    <div class="row">
        <div class="col">
            <div class="form-group">
                {{ html()->label(__('labels.teacher.linkedin_link'))->for('linkedin_link') }}

                {{ html()->text('linkedin_link')
                    ->class('form-control')
                    ->value($teacherProfile->linkedin_link)
                    ->placeholder(__('labels.teacher.linkedin_link'))
                }}
            </div><!--form-group-->
        </div><!--col-->
    </div><!--row-->

    <div class="row">
        <div class="col">
            <div class="form-group">
                {{ html()->label(__('Instagram Link'))->for('instagram_link') }}

                {{ html()->text('instagram_link')
                    ->class('form-control')
                    ->value($teacherProfile->instagram_link)
                    ->placeholder("instagram link")
                }}
            </div><!--form-group-->
        </div><!--col-->
    </div><!--row-->

    <div class="row">
        <div class="col">
            <div class="form-group">
                {{ html()->label(__('labels.teacher.payment_details'))->for('payment_details') }}
                <select class="form-control" name="payment_method" id="payment_method" required>
                    <option value=" " disabled selected>Select Payment</option>
                    <option value="bank" {{ $teacherProfile->payment_method == 'bank'?'selected':'' }}>{{ trans('labels.teacher.bank') }}</option>
                    <!-- <option value="paypal" {{ $teacherProfile->payment_method == 'paypal'?'selected':'' }}>{{ trans('labels.teacher.paypal') }}</option> -->
                </select>
            </div><!--form-group-->
        </div><!--col-->
    </div><!--row-->
    <div class="bank_details" style="display:{{ $user->teacherProfile->payment_method == 'bank'?'':'none' }}">

        <div class="row">
            <div class="col">
                <div class="form-group">
                    {{ html()->label(__('labels.teacher.bank_details.name'))->for('bank_name') }}

                    {{ html()->text('bank_name')
                        ->class('form-control')
                        ->value($payment_details?$payment_details->bank_name:'')
                        ->placeholder(__('labels.teacher.bank_details.name'))
                    }}
                </div><!--form-group-->
            </div><!--col-->
        </div><!--row-->

        <div class="row">
            <div class="col">
                <div class="form-group">
                    {{ html()->label(__('labels.teacher.bank_details.ifsc_code'))->for('ifsc_code') }}

                    {{ html()->text('ifsc_code')
                        ->class('form-control')
                        ->value($payment_details?$payment_details->ifsc_code:'')
                        ->placeholder(__('labels.teacher.bank_details.ifsc_code'))
                    }}
                </div><!--form-group-->
            </div><!--col-->
        </div><!--row-->

        <div class="row">
            <div class="col">
                <div class="form-group">
                    {{ html()->label(__('labels.teacher.bank_details.account'))->for('account_number') }}

                    {{ html()->text('account_number')
                        ->class('form-control')
                        ->value($payment_details?$payment_details->account_number:'')
                        ->placeholder(__('labels.teacher.bank_details.account'))
                    }}
                </div><!--form-group-->
            </div><!--col-->
        </div><!--row-->

        <div class="row">
            <div class="col">
                <div class="form-group">
                    {{ html()->label(__('labels.teacher.bank_details.holder_name'))->for('account_name') }}

                    {{ html()->text('account_name')
                        ->class('form-control')
                        ->value($payment_details?$payment_details->account_name:'')
                        ->placeholder(__('labels.teacher.bank_details.holder_name'))
                    }}
                </div><!--form-group-->
            </div><!--col-->
        </div><!--row-->

    </div>

   
    <div class="row">
        <div class="col">
            <div class="form-group">
                {{ html()->label(__('labels.teacher.description'))->for('description') }}

                {{ html()->textarea('description')
                    ->class('form-control')
                    ->value($teacherProfile->description)
                    ->placeholder(__('labels.teacher.description'))
                }}
            </div><!--form-group-->
        </div><!--col-->
    </div><!--row-->

    <div class="row">
    <div class="col">
        <div class="form-group">
        <label for="middle_name">Country</label>

            {{ html()->text('country')
                ->class('form-control')
                ->placeholder(__('validation.attributes.frontend.country'))
                ->attribute('maxlength', 191)
                ->required()
                ->autofocus() }}
        </div><!--form-group-->
    </div><!--col-->
</div><!--row-->



<div class="row">
    <div class="col">
        <label for="cv">CV</label>
        <div class="form-group d-flex align-items-center">
            <input class="form-control" type="file" name="upload_cv" id="cv" value="" style="width:90%; display:inline;">
            
            <?php if ($teacherProfile->upload_cv): ?>
                <span class="ml-2"> <a href="{{asset($teacherProfile->upload_cv)}}" target="_blank" class="btn btn-primary" style="">View</a></span>
                <?php else: ?>
                    <span class="ml-2"> <a href="#" target="_blank" class="btn btn-primary" style="display: none;">View</a></span>
            <?php endif; ?>
        </div>
    </div>
</div>

    <div class="row">
        <div class="col">
            <label for="aadhar">Aadhar Card</label>
            <div class="form-group d-flex align-items-center">
                <input class="form-control" type="file" name="aadhar_card" id="aadhar" value="{{asset($teacherProfile->aadhar_card)}}" style="width:90%; display:inline;">
                <?php if ($teacherProfile->aadhar_card): ?>
                <span class="ml-2"> <a href="{{asset($teacherProfile->aadhar_card)}}" target="_blank" class="btn btn-primary" style="">View</a></span>
                <?php else: ?>
                    <span class="ml-2"> <a href="#" target="_blank" class="btn btn-primary" style="display: none;">View</a></span>
            <?php endif; ?>
           </div><!--form-group-->
        </div><!--col-->
    </div><!--row-->


    <div class="row">
        <div class="col">
            <label for="pan_card">Pan Card</label>
            <div class="form-group d-flex align-items-center">
                <input class="form-control" type="file" name="pan_card" id="pan_card" value="" style="width:90%; display:inline;">
                <?php if ($teacherProfile->pan_card): ?>
                <span class="ml-2"> <a href="{{asset($teacherProfile->pan_card)}}" target="_blank" class="btn btn-primary" style="">View</a></span>
                <?php else: ?>
                    <span class="ml-2"> <a href="#" target="_blank" class="btn btn-primary" style="display: none;">View</a></span>
            <?php endif; ?>

           </div><!--form-group-->
        </div><!--col-->
    </div><!--row-->

    <div class="row">
        <div class="col">
            <label for="photo_id">Photo ID Proof</label>
            <div class="form-group d-flex align-items-center">
                <input class="form-control" type="file" name="photo_id_proof" id="photo_id" value="" style="width:90%; display:inline;">
                <?php if ($teacherProfile->photo_id_proof): ?>
                <span class="ml-2"> <a href="{{asset($teacherProfile->photo_id_proof)}}" target="_blank" class="btn btn-primary" style="">View</a></span>
                <?php else: ?>
                    <span class="ml-2"> <a href="#" target="_blank" class="btn btn-primary" style="display: none;">View</a></span>
            <?php endif; ?>

           </div><!--form-group-->
        </div><!--col-->
    </div><!--row-->



@endif
@if ($user->canChangeEmail())
    <div class="row">
        <div class="col">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> @lang('strings.frontend.user.change_email_notice')
            </div>

            <div class="form-group">
                {{ html()->label(__('validation.attributes.frontend.email'))->for('email') }}

                {{ html()->email('email')
                    ->class('form-control')
                    ->placeholder(__('validation.attributes.frontend.email'))
                    ->attribute('maxlength', 191)
                    ->required() }}
            </div><!--form-group-->
        </div><!--col-->
    </div><!--row-->
@endif
@if(config('registration_fields') != NULL)
    @php
        $fields = json_decode(config('registration_fields'));
        $inputs = ['text','number','date'];
    @endphp


    @foreach($fields as $item)
        <div class="row">
            <div class="col">
                <div class="form-group">
                    @if(in_array($item->type,$inputs))
                        {{ html()->label(__('labels.backend.general_settings.user_registration_settings.fields.'.$item->name))->for('last_name') }}

                        <input  class="form-control mb-0" @if($item->name=='phone') maxlength="10" minlength="10" type="text" @else type="{{$item->type}}"  @endif value="{{$user[$item->name]}}"
                               name="{{$item->name}}"
                               placeholder="{{__('labels.backend.general_settings.user_registration_settings.fields.'.$item->name)}}">
                    @elseif($item->type == 'gender')
                        <label class="radio-inline mr-3 mb-0">
                            <input type="radio" @if($user[$item->name] == 'male') checked
                                   @endif name="{{$item->name}}"
                                   value="male"> {{__('validation.attributes.frontend.male')}}
                        </label>
                        <label class="radio-inline mr-3 mb-0">
                            <input type="radio" @if($user[$item->name] == 'female') checked
                                   @endif  name="{{$item->name}}"
                                   value="female"> {{__('validation.attributes.frontend.female')}}
                        </label>
                        <label class="radio-inline mr-3 mb-0">
                            <input type="radio" @if($user[$item->name] == 'other') checked
                                   @endif  name="{{$item->name}}"
                                   value="other"> {{__('validation.attributes.frontend.other')}}
                        </label>
                    @elseif($item->type == 'textarea')
                        <textarea name="{{$item->name}}"
                                  placeholder="{{__('labels.backend.general_settings.user_registration_settings.fields.'.$item->name)}}"
                                  class="form-control mb-0">{{$user[$item->name]}}</textarea>
                    @endif
                </div>
            </div>
        </div>
    @endforeach

@endif
<div class="row">
    <div class="col">
        <div class="form-group mb-0 clearfix">
            {{ form_submit(__('labels.general.buttons.update')) }}
        </div><!--form-group-->
    </div><!--col-->
</div><!--row-->
{{ html()->closeModelForm() }}

@push('after-scripts')
    <script>
        $(function () {


            $("input[name='phone']").on("keyup",function(e){
                console.log($(this).val())
                var ph = "'"+$(this).val()+"'";
                console.log(ph.length)
                if(ph.length>12){
                    e.preventDefault();
                    return false;
                }
            })

            var avatar_location = $("#avatar_location");

            if ($('input[name=avatar_type]:checked').val() === 'storage') {
                avatar_location.show();
            } else {
                avatar_location.hide();
            }

            $('input[name=avatar_type]').change(function () {
                if ($(this).val() === 'storage') {
                    avatar_location.show();
                } else {
                    avatar_location.hide();
                }
            });
        });
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
    <script>
    const phoneInput = document.getElementById('phoneInput');

    phoneInput.addEventListener('input', function () {
        const phoneNumber = phoneInput.value.replace(/\D/g, '');

        if (phoneNumber.length > 10) {
            phoneInput.value = phoneNumber.slice(0, 10);
        }
    });
</script>
@endpush
