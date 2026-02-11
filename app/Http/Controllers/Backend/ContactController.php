<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class ContactController extends Controller
{
    /**
     * Display a listing of Category.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = Contact::all();

        return view('backend.contacts.index', compact('contacts'));
    }

    /**
     * Display a listing of Courses via ajax DataTable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request)
    {
        $contacts = "";
        $contacts = Contact::orderBy('created_at', 'desc')->get();


        return DataTables::of($contacts)
            ->addIndexColumn()
            ->editColumn('created_at', function ($q) {
               return $q->created_at->format('d M, Y | H:i A');
            })
            ->editColumn('number', function ($q) {
                if($q->number == ""){
                    return "N/A";
                }else{
                    return $q->number;
                }
            })
            ->make();
    }

     public function achievement()
    {
        $achievement = Achievement::where('id','1')->first();
      return view('backend.achievement.index',compact('achievement'));
    }

    public function achievementUpdate(Request $request)
    {
          $achievement = Achievement::find($request->achievement_id);
          $achievement->courses_offered = $request->courses_offered;
          $achievement->happy_students = $request->happy_students;
          $achievement->expert_tutor = $request->expert_tutor;
          $achievement->hours_taught = $request->hours_taught;
          $achievement->update();

          return redirect()->back()->withFlashSuccess(trans('alerts.backend.general.updated'));
    }

    /**
     * Show the form for creating new Contact.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.contacts.create');
    }

    /**
     * Store a newly created Contact in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'number' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        Contact::create($request->all());

        return redirect()->route('admin.contact-requests.index')->withFlashSuccess(trans('alerts.backend.general.created'));
    }

    /**
     * Display the specified Contact.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contact = Contact::findOrFail($id);

        return view('backend.contacts.show', compact('contact'));
    }

    /**
     * Show the form for editing Contact.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contact = Contact::findOrFail($id);

        return view('backend.contacts.edit', compact('contact'));
    }

    /**
     * Update Contact in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'number' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        $contact = Contact::findOrFail($id);
        $contact->update($request->all());

        return redirect()->route('admin.contact-requests.index')->withFlashSuccess(trans('alerts.backend.general.updated'));
    }

    /**
     * Remove Contact from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return redirect()->route('admin.contact-requests.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }
}
