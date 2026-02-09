@extends('backend.layouts.app')

@section('title')
Mock Series Management | {{ env('APP_NAME') }}
@stop

@section('content')
<style>
    .dropdown-menu {
        max-height: 300px;
        overflow-y: auto;
    }
    .dropdown-item.active {
        background-color: #007bff;
        color: white;
    }
    .badge .fas.fa-times {
        cursor: pointer;
        opacity: 0.8;
    }
    .badge .fas.fa-times:hover {
        opacity: 1;
    }
</style>

<div class="page-wrapper">
    <div class="page-content">
        @include('admin.includes.message')

        <div class="card shadow-sm border-0">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-black">Chapters for {{$subject->name}}</h5>
                    <div class="d-flex gap-2">
                       <a href="{{route('mockseries.subjects',[$mock->id])}}" class="btn btn-secondary btn-sm">Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
            <form method="post" action="{{route('mockseries.chapters.store',[$subject->id])}}">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Select Chapters</label>
                    <select name="chapters[]" multiple="" class="select2 form-control">
                        @foreach($chapters as $c)
                            <option value="{{$c->id}}" @if(in_array($c->id,$assigned)) selected @endif >{{$c->title}}</option>
                        @endforeach
                    </select>
                </div>
                
                <input type="submit" class="btn btn-primary mt-3" value="Submit" />
                
            </form>
            </div>
        </div>
    </div>
</div>

@stop

@section('page_js')
<script>
$(document).ready(function () {
    $('.select2').select2({
        placeholder: 'Select chapters',
        allowClear: true,
        width: '100%'
    });
});
</script>
@stop
