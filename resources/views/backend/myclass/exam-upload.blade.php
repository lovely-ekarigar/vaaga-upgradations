@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title',' Upload Subjective Exam'.' | '.app_name())

@section('content')
<style type="text/css">
  form{
    display: inline-block;
  }
</style>
 
    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">{{$batch->name}} Upload Subjective Exam</h3>
          
        </div>
        <div class="card-body">
<form method="POST" action="" enctype="multipart/form-data">
    @csrf
            <div class="row">
               <div class="col-md-4">
                  <label>Title</label>
                        <input type="text" name="title" required value="{{old('title')}}" class="form-control">   
               </div>

               <div class="col-md-4">
                  <label>Exam Date</label>
                        <input type="date" name="date" min="{{date('Y-m-d')}}" required value="{{old('date')}}" class="form-control">   
               </div>


               <div class="col-md-4">
                  
                   <label>Select the file to upload.</label>
                  <input class="form-control" accept="application/msword,application/pdf, image/*" required name="file" type="file">            
               </div>
               <div class="col-md-4">
                  <input name="batch_id" type="hidden" value="{{$batch->id}}">
                  <input class="btn btn-primary" style="margin-top: 18px;" type="submit" value="Upload File">            
               </div>
            </div>
        </form>
          

<br>

<hr>
          <div class="table-responsive">
        
                 <table id="myTable" class="table table-bordered table-striped dt-select ">
                    <thead>
                    <tr>
                        <th>@lang('labels.general.sr_no')</th>
                        <th>Title</th>
                        <th>&nbsp; @lang('strings.backend.general.actions')</th>
                       
                    </tr>
                    </thead>

                   <tbody>
                    @php
                    $sl = 1;
                    @endphp
             @foreach($aiqlist as $q)
<tr>
    <td>{{$sl++}}</td>
    <td>AI ID {{$q->id}}</td>
    <td>
       <button class="btn btn-sm btn-info show-questions"
    data-json='{{$q->question}}'
    data-id="{{$q->id}}">
    View Questions
</button>
    </td>
</tr>
@endforeach

                      
                    </tbody>
                </table>
            </div>

        
           
        </div>
    </div>
    
    
    <div class="modal fade" id="questionModal" tabindex="-1" aria-labelledby="questionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Parsed Questions</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="questionContent">
        <!-- Questions will be populated here -->
      </div>
    </div>
  </div>
</div>

@stop

@push('after-scripts')
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
$('#myTable').DataTable();
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.show-questions').forEach(btn => {
        btn.addEventListener('click', function () {
            const jsonStr = this.getAttribute('data-json');
            const questions = JSON.parse(JSON.stringify(JSON.parse(jsonStr)));
           
            let html = '';

            if (Array.isArray(questions)) {
                questions.forEach((q, index) => {
                    html += `<div class="mb-4">
                        <strong>Q${index + 1}:</strong> ${q.question}<br>`;
                    
                    if (q.options && Array.isArray(q.options)) {
                        html += '<ul>';
                        q.options.forEach((opt, i) => {
                            const isCorrect = i === q.answer;
                            html += `<li${isCorrect ? ' style="color:green;font-weight:bold;"' : ''}>
                                ${String.fromCharCode(65 + i)}. ${opt}
                            </li>`;
                        });
                        html += '</ul>';
                    }

                    html += '</div><hr>';
                });
            } else {
                html = `<p>Invalid question format</p>`;
            }

            document.getElementById('questionContent').innerHTML = html;
            const modal = new bootstrap.Modal(document.getElementById('questionModal'));
            modal.show();
        });
    });
});
    </script>

@endpush