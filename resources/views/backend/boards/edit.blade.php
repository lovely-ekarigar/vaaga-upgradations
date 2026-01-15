@extends('backend.layouts.app')
@section('title', __('labels.backend.categories.title').' | '.app_name())

@push('after-styles')
    <link rel="stylesheet" href="{{asset('plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css')}}"/>
@endpush
@section('content')
    {!! Form::model($board, ['method' => 'PUT', 'route' => ['admin.boards.update', $board->id], 'files' => true,]) !!}

    <div class="alert alert-danger d-none" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <div class="error-list">
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline">Edit Board</h3>
            <div class="float-right">
                <a href="{{ route('admin.boards.index') }}"
                   class="btn btn-success">View Board</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row ">
            <div class="col-12 col-lg-4 form-group">
               <!--  {!! Form::label('title', trans('labels.backend.categories.fields.name').' *', ['class' => 'control-label']) !!}
                {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => 'Enter Board Name', 'required' => false]) !!} -->
                <label for="dec" class="control-label">Name</label>
                            <input type="text" class="form-control" value="{{ $board->name}}" name="name">
            </div>

        
 <div class="col-12 col-lg-4 form-group">

                    {!! Form::label('course_image', 'Board Image', ['class' => 'control-label','accept' => 'image/jpeg,image/gif,image/png']) !!}
                    {!! Form::file('course_image', ['class' => 'form-control']) !!}
                   
                    @if ($board->board_image)
                        <a href="{{ asset('storage/uploads/'.$board->board_image) }}" target="_blank"><img
                                    height="50px" src="{{ asset('storage/uploads/'.$board->board_image) }}"
                                    class="mt-1"></a>
                    @endif
                </div>
                 
            <div class="col-12 form-group text-center">

                {!! Form::submit(trans('strings.backend.general.app_save'), ['class' => 'btn mt-auto  btn-danger']) !!}
            </div>
        </div>
        </div>
    </div>
    {{ html()->form()->close() }}
@endsection

@push('after-scripts')
    <script src="{{asset('plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.bundle.min.js')}}"></script>

    <script>
        var icon = 'fas fa-bomb';
        
        $('#icon').iconpicker({
            cols: 10,
            icon: icon,
            iconset: 'fontawesome5',
            labelHeader: '{0} of {1} pages',
            labelFooter: '{0} - {1} of {2} icons',
            placement: 'bottom', // Only in button tag
            rows: 5,
            search: true,
            searchText: 'Search',
            selectedClass: 'btn-success',
            unselectedClass: ''
        });

        $(document).on('change', '#icon_type', function () {

            if ($(this).val() == 1) {
                $('.upload-image-wrapper').parent('.col-12').removeClass('d-none')

                $('.upload-image-wrapper').removeClass('d-none');
                $('.select-icon-wrapper').addClass('d-none')
            } else if ($(this).val() == 2) {
                $('.upload-image-wrapper').parent('.col-12').removeClass('d-none')

                $('.upload-image-wrapper').addClass('d-none');
                $('.select-icon-wrapper').removeClass('d-none')
            } else {
                $('.upload-image-wrapper').parent('.col-12').addClass('d-none')
                $('.upload-image-wrapper').addClass('d-none');
                $('.select-icon-wrapper').addClass('d-none');


            }
        })

    </script>
@endpush


