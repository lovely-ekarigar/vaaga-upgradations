<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Auth;
use Illuminate\Support\Str;
use App\Models\VaagTeam;

class TeamController extends Controller
{
    public function ourTeams()
    {
         $teams = VaagTeam::orderBy("id","asc")->get();
        return view('frontend.team.list',compact('teams'));
    }
    
    public function teamDeatails($slug)
    {
         $team = VaagTeam::where('slug',$slug)->first();
        return view('frontend.team.details',compact('team'));
    }
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request )
    {
        $teams = VaagTeam::orderBy("id","desc");
        $teams = $teams->paginate(10);
        
         $team = VaagTeam::find($request->del);
            if($team){
                $team->delete();
                return redirect()->back()->withFlashSuccess("Team deleted successfully");
            }
        
       return view('backend.team.list',compact('teams'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.team.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $this->validate($request,[
            'name' => 'required|max:450',

        ],[
                'name.required' => 'Kindly Enter Team Name',

        ]);


        $co = new VaagTeam();
        $co->name=$request->name;
        $co->designation=$request->designation;
        $co->facebook=$request->facebook;
        $co->facebook=$request->facebook;
        $co->twitter=$request->twitter;
        $co->linkdin=$request->linkedin;
        $co->instagram=$request->instagram;
        $co->youtube=$request->youtube;
        $co->about=$request->about; 
        $co->slug=Str::slug($request->name);

        $image = $request->file('image');
        if($image){
            
        $filename = $image->getClientOriginalName();
        
        $extension = $image->getClientOriginalExtension(); 
        
        $filename = time().'.'.$extension;
        
        $destinationPath = 'upload/teams/';
        
        $image->move($destinationPath, $filename);
        
        $co->image =$destinationPath.$filename;

        }

        $co->save();

        return redirect()->route('admin.team.list')->withFlashSuccess("Team added");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $team=VaagTeam::find($id);
        if(!$team){
            return abort(404);
        }
         return view('backend.team.edit',compact('team'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         // dd($request->all());
        $this->validate($request,[
            'name' => 'required|max:450',

        ],[
                'name.required' => 'Kindly Enter Team Name',

        ]);


        $co = VaagTeam::find($request->id);
        $co->name=$request->name;
        $co->designation=$request->designation;
        $co->facebook=$request->facebook;
        $co->facebook=$request->facebook;
        $co->twitter=$request->twitter;
        $co->linkdin=$request->linkdin;
        $co->instagram=$request->instagram;
        $co->youtube=$request->youtube;
        $co->about=$request->about; 
        $co->slug=$request->slug; 

        $image = $request->file('image');
        if($image){
            
        $filename = $image->getClientOriginalName();
        
        $extension = $image->getClientOriginalExtension(); 
        
        $filename = time().'.'.$extension;
        
        $destinationPath = 'upload/teams/';
        
        $image->move($destinationPath, $filename);
        
        $co->image =$destinationPath.$filename;

        }

        $co->update();

        return redirect()->route('admin.team.list')->withFlashSuccess("Updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}


