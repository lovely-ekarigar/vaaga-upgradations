 @extends('frontend.layout.sub-master')
@section('title')
<title>Waiting Area | {{ env('APP_NAME') }}</title>
@stop

@section('content')
@include("frontend.include.user-menu")
</div>

<div class="col-lg-8 col-xl-9">
    <div class="profile-content-area my-6 card card-body">
        <div class="text-center mb-4">
            <h1 class="display-4 font-weight-medium text-primary">Waiting Area</h1>
            <h4 class="text-uppercase text-success">Your class has not started yet</h4>
            <p class="text-muted mb-2">Please wait until your class begins.</p>

           

            <p class="text-muted">You will be automatically redirected when the class starts.</p>

            <a href="/user/dashboard" class="btn btn-outline-secondary mt-3">
                <i class="fa fa-arrow-left"></i> Go Back
            </a>
        </div>
    </div>
</div>
</div>
</section>
</main>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    $(document).ready(function () {
    let id = {{$id}}; 

    setInterval(function () {
        $.ajax({
            url: "{{ route('classes.meetingLink') }}",
            type: 'GET',
            data: { bid: id },
            success: function (response) {
                if (response.success === true && response.url) {
                    window.location.href = response.url;
                }
            },
            error: function (xhr, status, error) {
                console.log('Error:', error);
            }
        });
    }, 10000); // 10 seconds
});

</script>
@stop
