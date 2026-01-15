<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\FileUploadTrait;
use App\Locale;
use App\Models\Assessment;
use App\Models\AssessmentQuestion;
use App\Models\AssessmentUser;
use App\Models\Auth\User;
use App\Models\Recording;
use App\Models\StudentTeacherBatch;
use App\Models\OauthClient; 
use Illuminate\Http\Request;
use Validator;
use Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{
 
         public function index()
         {
             $assessments=Assessment::get();
             return view('backend.assessment.list',compact('assessments'));
         }
         
          public function create()
         {
             return view('backend.assessment.create');
         }
         
         public function save(Request $request)
         {
              $validator = Validator::make($request->all(), [

            'name' => 'required',
            'description' => 'required',
        ],
        $msg = [ 
        'name.required' => 'Kindly Enter Assessment Name<br>',

      ]);

        $form=$request->all();
         if ($validator->passes()) {

        $co=new Assessment();
        $co->name=$request->name;
         $co->description=$request->description;
        $co->save();
     Session::flash('flash_message',"Assessment Created Successfully");
        return redirect()->back()->withInput();


    }else{
        $errorString = implode($validator->messages()->all());

            Session::flash('flash_error',$errorString);
            return redirect()->back()->withInput();
        }
    }
    
    
    public function deleteAssessment($id){
    if(!auth()->user()->isAdmin()){
            return abort(403);
        } 

        $b=Assessment::find($id);
            
            $b->delete();

            return redirect('/user/assessment')->withFlashDanger("Assessment has been deleted");


}


public function edit($id)
{
    if(!auth()->user()->isAdmin()){ 
            return abort(403);
        } 
        $assessment=Assessment::where("id",$id)->first();
        
    return view('backend.assessment.edit',compact('assessment'));
}


public function update(Request $request,$id)
{
     $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);

            $as=Assessment::find($id);
            $as->name=$request->name;
  $as->description=$request->description;
            $as->update();

            return redirect()->back()->withFlashSuccess("Assessment has been updated");
}


public function question($id)
{
  
    $assid= $id;
   
    
     return view('backend.assessment.question',compact('assid','id'));
}

public function questionCreate(Request $request,$id)
{
      $validator = Validator::make($request->all(), [

            'question' => 'required',
            'qtime' => 'required',
            'res_file' => 'required|mimes:mp3',
        ],
        $msg = [ 
        'question.required' => 'Kindly Enter Question<br>',
        'qtime.required' => 'Kindly Enter Time<br>',
         'res_file.required' => 'Kindly upload Mp3 file<br>',
          'res_file.mimes' => 'Kindly upload a valid Mp3 file<br>',

      ]);

        $form=$request->all();
         if ($validator->passes()) {
              $qu=new AssessmentQuestion();
               
              if($request->hasFile('res_file')){
 $file = \Illuminate\Support\Facades\Request::file('res_file');
                    $filename = time() . '-' . $file->getClientOriginalName();
                    $size = $file->getSize() / 1024;
                    $path = public_path() . '/storage/uploads/';
                    $file->move($path, $filename);

       $qu->question_file ="/storage/uploads/".$filename ;

          
        }
        

       
        $qu->question=$request->question;
        $qu->qtime=$request->qtime;
        $qu->assessment_id=$request->assessment_id;
        $qu->save();
     Session::flash('flash_message',"Question Added Successfully");
        return redirect()->back()->withInput();


    }else{
        $errorString = implode($validator->messages()->all());

            Session::flash('flash_error',$errorString);
            return redirect()->back()->withInput();
        }
}
   
   public function questionList($id)
   {
       
       $assquestions=AssessmentQuestion::where("assessment_id",$id)->get();
    
       return view('backend.assessment.questionlist',compact('assquestions','id'));
   }
   
   
   public function questionEdit($id)
   {
       if(!auth()->user()->isAdmin()){ 
            return abort(403);
        } 
        $assquestion=AssessmentQuestion::where("id",$id)->first();
       return view('backend.assessment.questionedit',compact('assquestion','id'));
   }
   
   
   public function questionUpdate(Request $request,$id)
   {
       $this->validate($request, [

            'question' => 'required',
            'qtime' => 'required',
            'res_file' => 'mimes:mp3',
        ],
        $msg = [ 
        'question.required' => 'Kindly Enter Question<br>',
        'qtime.required' => 'Kindly Enter Time<br>',
         'res_file.required' => 'Kindly upload Mp3 file<br>',
          'res_file.mimes' => 'Kindly upload a valid Mp3 file<br>',

      ]);
        
        

            $asq=AssessmentQuestion::find($id);
            
                  if($request->hasFile('res_file')){
 $file = \Illuminate\Support\Facades\Request::file('res_file');
                    $filename = time() . '-' . $file->getClientOriginalName();
                    $size = $file->getSize() / 1024;
                    $path = public_path() . '/storage/uploads/';
                    $file->move($path, $filename);

       $asq->question_file = "/storage/uploads/".$filename ;

          
        }
        
            $asq->question=$request->question;
            $asq->qtime=$request->qtime;

            $asq->update();

            return redirect()->back()->withFlashSuccess("Question has been updated");
   }
   
   
   public function deleteAssQuestion($id)
   {
       if(!auth()->user()->isAdmin()){
            return abort(403);
        } 

        $b=AssessmentQuestion::find($id);
            
            $b->delete();

            return redirect()->back()->withFlashDanger("Question has been deleted");
   }
   
   
   public function userList($id)
   {
       $assusers=AssessmentUser::with(["user"])->where("assessment_id",$id)->get();
    //   dd($assusers);
       return view('backend.assessment.userlist',compact('id','assusers'));
   }
    
    
         


}


