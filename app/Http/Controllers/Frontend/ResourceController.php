<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Blog;
use App\Models\Bundle;
use App\Models\Category;
use App\Models\Config;
use App\Models\Course;
use App\Models\CourseTimeline;
use App\Models\Faq;
use App\Models\Lesson;
use App\Models\Page;
use App\Models\Reason;
use App\Models\Sponsor;
use App\Models\Resource;
use App\Models\ResourceList;
use App\Models\System\Session;
use App\Models\Tag;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Newsletter;
use Auth;
use Illuminate\Support\Facades\Validator;
// Input facade removed in Laravel 5.4+ - use Request instead
// use Illuminate\Support\Facades\Input;
use Arcanedev\NoCaptcha\Rules\CaptchaRule;
use Illuminate\Auth\Events\Registered;
use Hash;
/**
 * Class HomeController.
 */
class ResourceController extends Controller
{

public function index($slug){
$res=Resource::where("slug",$slug)->first();

if($res){
    $reslist=ResourceList::where("type",$res->id)->get();
 return view('resource',compact('res','reslist'));   
}else{
    return redirect("/");
}
       
}


public function admin(Request $request){

if($request->del){

$rx=ResourceList::find($request->del);
$rx->delete();
return redirect()->back()->withFlashSuccess(trans('Resource deleted'));

}

    $res=Resource::all();
    $lists=ResourceList::all();
    $rrs=array();
    foreach($lists as $l){
        $l->tdata=Resource::find($l->type);
        $rrs[]=$l;
    }
    return view('backend.courses.resource', compact('res','rrs'));
}

public function create(){
     $res=Resource::all();
    return view('backend.courses.rcreate', compact('res'));
}

public function save(Request $request){
$res=new ResourceList();
  if($request->hasFile('res_file')){
 $file = \Illuminate\Support\Facades\Request::file('res_file');
                    $filename = time() . '-' . $file->getClientOriginalName();
                    $size = $file->getSize() / 1024;
                    $path = public_path() . '/storage/uploads/';
                    $file->move($path, $filename);

       $res->file = $filename ;

          
        }
        $res->name=$request->name;
        $res->content=$request->description;
        $res->type=$request->parent;
        $res->save();

        return redirect()->back()->withFlashSuccess(trans('Resource Created'));


}

}