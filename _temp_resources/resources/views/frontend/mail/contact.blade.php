<p>@lang('strings.emails.contact.email_body_title')</p>

<p><strong>@lang('validation.attributes.frontend.name'):</strong> {{ $name }}</p>
<p><strong>@lang('validation.attributes.frontend.email'):</strong> {{ $email }}</p>
<p><strong>@lang('validation.attributes.frontend.phone'):</strong>  {{ ($phone == "") ? "N/A" : $phone }}</p>
<p><strong>@lang('validation.attributes.frontend.message'):</strong> {{ $message }}</p>
