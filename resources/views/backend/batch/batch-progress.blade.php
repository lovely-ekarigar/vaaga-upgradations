@extends('backend.layouts.app')

@section('title', __('Batch Progress').' | '.app_name())

@section('content')
<style type="text/css">
    .bd-bg-info{
            border: 1px solid #17a2b8;
    background-color: #17a2b8;
    color: #fff;
    padding: 1px 6px;
    border-radius: 5px;
    }
    .bd-bg-secondary{
            border: 1px solid #6c757d;
    background-color: #6c757d;
    color: #fff;
    padding: 1px 6px;
    border-radius: 5px;
    }
    .bd-bg-Success{
            border: 1px solid #198754;
    background-color: #198754;
    color: #fff;
    padding: 1px 6px;
    border-radius: 5px;
    }
    .f-12{
        font-size: 12px !important;
    }
</style>

    <div class="card">
        <div class="card-header">

            <h3 class="page-title d-inline">Batch Progress</h3>
            

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <ul class="list-unstyled">
                       @foreach($list as $lesson)
                        <li class="pb-3">
                          <h6 class="pb-2">{{$lesson->title}}</h6> 

                          @foreach($lesson->lesson_lists as $ll)
                          @foreach($lession_complete_list as $lession_complete)
                            @if($ll->id == $lession_complete->lession_id)

                              @if($lession_complete->status == 'completed')
                                <p class="px-3">{{$ll->title}} <span class="mx-5 bd-bg-Success f-12">Completed</span></p>
                              @elseif($lession_complete->status == 'ongoing')
                                <p class="px-3">{{$ll->title}} <span class="mx-5 bd-bg-info f-12">Ongoing</span></p>
                              @else
                                <p class="px-3">{{$ll->title}} <span class="mx-5 bd-bg-secondary f-12">Not Started</span></p>
                              @endif
                            
                            @endif
                            
                            
                          @endforeach

                          @endforeach

                        </li>
                         @endforeach
                     </ul>
                </div>
            </div>
        </div>
    </div>

@stop

@push('after-scripts')
   
@endpush
