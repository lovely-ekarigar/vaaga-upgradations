<?php 

use App\Models\SubjectImport;
?>

@extends('backend.layouts.app')

@section('title')
Import Questions | {{ env('APP_NAME') }}
@stop

@section('content')
<div class="page-wrapper">
    <div class="page-content">

        @include('admin.includes.message')

        <div class="card shadow-sm border-0">
            <div class="card-header">
                <h5 class="mb-0 text-black">Import Questions</h5>
            </div>

            <div class="card-body">
                <form action="" method="POST">
                    @csrf
                    <div class="row g-3">

                        
                        <!-- Select Exam -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Select Subject(From exam portal)</label>
                            <select name="subject_id" class="form-control form-select" required>
                                <option value="">-- Select Exam --</option>
                                @foreach($subjects ?? [] as $subject)
                                
                                <?php $impx = SubjectImport::where("subject_id",$subject->id)->first(); ?>
                                    <option value="{{ $subject->id }}">{{ $subject->name }}({{$subject->questions_count}} Questions)  @if($impx) - [Imported] @endif</option>
                                @endforeach
                            </select>
                        </div><!-- Select Course -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Select Course(Live Courses)</label>
                            <select name="course_id" class="form-control form-select" required>
                                <option value="">-- Select Course --</option>
                                 @foreach($courses ?? [] as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->title }}</option>
                                @endforeach
                            </select>
                        </div>
  <!-- Select Course -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Difficulty Level</label>
                            <select name="difficuly" class="form-control form-select" required>
                                <option value="easy">Easy</option>
                                      <option value="medium">Medium</option>
                                            <option value="hard">Hard</option>
                               
                            </select>
                        </div>
                        <!-- Submit -->
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-upload"></i> Import
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@stop
