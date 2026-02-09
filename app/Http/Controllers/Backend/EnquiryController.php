<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Frontend\Contact\SendContact;
use Illuminate\Support\Facades\Session;

use App\Rules\Recaptcha;
use Validator;

class EnquiryController extends Controller
{
    public function index(Request $request)
    {
        if($request->del)
        {
            $enquiry = Enquiry::findorFail($request->del);
            if($enquiry)
            {
                $enquiry->delete();
                 Session::flash('success', 'Your enquiry has been deleted successfully!');
            return redirect()->back();
            } else {
            return redirect()->back()->withErrors('error', 'Something went wrong. Please try again.');
            }
        }
        $enquiries = Enquiry::orderBy('id','desc')->get();

        return view('backend.enquiries.index', compact('enquiries'));
    }

    /**
     * Show the form for editing an enquiry
     */
    public function edit($id)
    {
        $enquiry = Enquiry::findOrFail($id);
        return view('backend.enquiries.edit', compact('enquiry'));
    }

    /**
     * Store a new enquiry
     */
    public function store(Request $request)
    {
        // TODO: Implement enquiry storage
        return redirect()->back()->withFlashSuccess('Enquiry stored');
    }
}
