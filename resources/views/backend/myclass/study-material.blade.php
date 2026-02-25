@extends('frontend.layout.sub-master')
@section('title')
<title>Study Material | {{env('APP_NAME')}}</title>
@stop

@section('content')

@include("frontend.include.user-menu")
</div>
<div class="col-lg-8 col-xl-9">
    <div class="profile-content-area my-6 card card-body">
        <div class="border-bottom mb-6 pb-6">
            <h3 class="mb-4">Study Material</h3>

            <ul class="list-unstyled">
                @foreach($list as $lesson)
                    <li class="pb-3">
                        <!-- <span class="d-block fw-600">{{ $lesson->title }}</span> -->

                        @foreach($lesson->lesson_lists as $ll)

                            @php
                                $completion = $lession_complete_list->firstWhere('lession_id', $ll->id);
                            @endphp

                            <div class="d-flex justify-content-between align-items-center px-4 py-2 border-bottom rounded mb-2">
                                <div>
                                    <span class="d-block fw-600"> {{ $ll->title }}</span>

                                    @if($completion)
                                        @if($completion->status == 'completed')
                                            <span class="badge text-bg-success mx-2">Completed</span>
                                        @elseif($completion->status == 'ongoing')
                                            <span class="badge text-bg-info mx-2">Ongoing</span>
                                        @else
                                            <span class="badge text-bg-warning mx-2">Not Started</span>
                                        @endif
                                    @else
                                        <span class="badge text-bg-warning mx-2">Not Started</span>
                                    @endif
                                </div>

                              @php
    $disabled = true;
    if($completion && $completion->status != 'not_started'){
        $disabled = false;
    }
@endphp

<a href="{{ route('view-material', ['lesson_id' => $ll->id, 'batch_id' => $batch_list->id]) }}"
   class="btn btn-sm btn-primary {{ $disabled ? 'disabled' : '' }}"
   {{ $disabled ? 'aria-disabled="true" tabindex="-1"' : '' }}>
    View Study Material
</a>
                            </div>

                        @endforeach

                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

</section>
</main>

@stop
