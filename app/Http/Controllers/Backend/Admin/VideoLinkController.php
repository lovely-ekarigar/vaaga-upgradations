<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VideoLink;
use App\Models\Training;


class VideoLinkController extends Controller
{
  public function homeVideo()
  {
    $link = VideoLink::first();
    // dd($link);
   
    return view('backend.home-video.index',compact('link'));
  }

  public function updateLink(Request $request)
  {
    //  dd($request);

    $data = VideoLink::find($request->id);
    $data ->link=$request->link;
    $data->update();
    return redirect()->back(); 
  }
  
}

