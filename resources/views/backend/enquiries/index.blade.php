@extends('backend.layouts.app')

@section('title', __('Training') . ' | ' . app_name())

@section('content')

    <div class="card">
        <div class="card-header">

            <h3 class="page-title d-inline">Enquiries</h3>
            <div class="float-right">
               
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">

                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>

                                    <th>@lang('labels.general.sr_no')</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Eamil</th>
                                    <th>Grade</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                                @php
                                $sl = 1;
                                @endphp
                                @foreach($enquiries as $enquiry)
                                <tr>
                                    <td>{{$sl++}}</td>
                                    <td>{{$enquiry->name}}</td>
                                    <td>{{$enquiry->mobile}}</td>
                                    <td>{{$enquiry->email}}</td>
                                    <td>{{$enquiry->grade}}</td>
                                    <td>
                                        <a href="javascript:void(0)" class="btn btn-primary openModal"
                                        data-id="{{$enquiry->id}}"
                                        data-name="{{$enquiry->name}}"
                                        data-mobile="{{$enquiry->mobile}}"
                                        data-email="{{$enquiry->email}}"
                                        data-grade="{{$enquiry->grade}}"
                                        data-insterested="{{$enquiry->insterested}}"
                                        data-gender="{{$enquiry->gender}}"
                                        >View</a>
                                        <a href="{{route('admin.endquiryEdit', $enquiry->id)}}" class="btn btn-info btn-xs">View Details</a>
                                        <a href="?del={{$enquiry->id}}" onclick="return confirm('Are you sure you want to delete Enquiry Data?');" class="btn btn-xs btn-danger mb-1">
                                            <i class="fa fa-trash"></i>
                                          </a>
                                    </td>
                                </tr>
                                @endforeach

                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="trainigModal" role="dialog" aria-labelledby="trainigModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="trainigModalLabel">Enquiry ref: <span id="enquryId"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            
                <div class="modal-body">
                    <div class="row">
    <div class="contact-info mb-2 col-md-4">
        <div class="form-group">
            <label for="name">Name</label><span class="text-danger">*</span>
            <input type="text" name="name" id="name" value="{{old('name')}}" class="form-control" maxlength="191" required>
        </div>
    </div>

    <div class="contact-info mb-2 col-md-4">
        <div class="form-group">
            <label for="grade">Grade</label><span class="text-danger">*</span>
            <input type="text" name="grade" id="grade" value="{{old('grade')}}" class="form-control" maxlength="191" required>
        </div>
    </div>

    <div class="contact-info mb-2 col-md-4">
        <div class="form-group">
            <label for="mobile">Phone number</label><span class="text-danger">*</span>
            <input type="tel" name="mobile" id="mobile" class="form-control" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" maxlength="10" pattern="\d{10}" required value="{{old('mobile')}}">
        </div>
    </div>

    <div class="contact-info mb-2 col-md-4">
        <div class="form-group">
            <label for="email">Email id</label><span class="text-danger">*</span>
            <input type="email" name="email" id="email" value="{{old('email')}}" class="form-control" maxlength="191" required>
        </div>
    </div>

    <div class="contact-info mb-2 col-md-4">
        <div class="form-group">
            <label for="insterested">Interested for (which course)</label><span class="text-danger">*</span>
            <input type="text" name="insterested" id="insterested" value="{{old('insterested')}}" class="form-control" maxlength="191" required>
        </div>
    </div>

    <div class="contact-info mb-2 col-md-4">
        <div class="mb-3">
            <label>Gender</label><span class="text-danger">*</span>
            <div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="genderMale" value="male" required>
                    <label class="form-check-label" for="genderMale">Male</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="female" required>
                    <label class="form-check-label" for="genderFemale">Female</label>
                </div>
            </div>
        </div>
    </div>

</div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary closeModal">Close</button>
                </div>
            
        </div>
    </div>
</div>


@stop

@push('after-scripts')
    <script type="text/javascript">
        $('#myTable').DataTable();

        $("#select2insidemodal").select2({
            dropdownParent: $("#exampleModal")
        });
       $(document).on('click', '.openModal', function() {
    console.log('View button clicked'); // Debug log
    
    $('#enquryId').text($(this).data('id'));
    $('#name').val($(this).data('name'));
    $('#grade').val($(this).data('grade'));
    $('#mobile').val($(this).data('mobile'));
    $('#email').val($(this).data('email'));
    $('#insterested').val($(this).data('insterested'));
    $('#genderMale').prop('checked', $(this).data('gender') === 'male');
    $('#genderFemale').prop('checked', $(this).data('gender') === 'female');

    // Show the modal
    $('#trainigModal').modal('show');
    console.log('Modal should be showing now'); // Debug log
});
$(document).on('click','.closeModal', function(){
    $('#trainigModal').modal('hide');
});
    </script>
@endpush
