Your Demo request has been recived

@lang('validation.attributes.frontend.name'): {{ $request->name }}
@lang('validation.attributes.frontend.email'): {{ $request->email }}
@lang('validation.attributes.frontend.phone'): {{ ($request->phone == "") ? "N/A" : $request->phone }}
@lang('validation.attributes.frontend.message'):  We have received demo request. Our team will get in touch with you shortly.
