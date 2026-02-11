@extends('backend.layouts.app')



@section('content')

<div class="card">
    <div class="card-header">

        <h3 class="page-title d-inline">Home Video</h3>
        <div class="container">
            <div class="row pt-5">
                <label for="">Home Video Link</label>
                <form action="{{route('admin.updateLink')}}" method="POST" class="w-100">
                @csrf
                <input type="hidden" name="id" value="{{$link->id}}">
                    <textarea name="link" id="" cols="" rows="3" class="w-100"> {{$link->link}}</textarea>
                    
                    <button type="submit" class="btn btn-primary mt-3 mx-atuo">Update</button>
                </form>
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
    </script>
    @endpush