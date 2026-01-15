<?php

namespace App\Http\Controllers\Frontend;

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

    
    public function submitForm(Request $request)
    {
        $request->validate([
            'name'        => 'required|max:191',
            'grade'       => 'required|max:191',
            'mobile'      => 'required|digits:10',
            'email'       => 'required|email|max:191',
            'insterested' => 'required|max:191',
            'gender'      => 'required|in:male,female', 
           
            'g-recaptcha-response' => ['required', new Recaptcha] 
        ],
        $messages = [
            'name.required'        => 'Please provide your name.',
            'name.max'             => 'Your name cannot exceed 191 characters.',
            'grade.required'       => 'Please provide the grade.',
            'grade.max'            => 'Grade cannot exceed 191 characters.',
            'mobile.required'      => 'Please provide a valid phone number.',
            'mobile.digits'        => 'The phone number must be exactly 10 digits.',
            'email.required'       => 'Please provide an email address.',
            'email.email'          => 'Please provide a valid email address.',
            'email.max'            => 'Your email address cannot exceed 191 characters.',
            'insterested.required' => 'Please mention which course you are interested in.',
            'insterested.max'      => 'Course name cannot exceed 191 characters.',
            'gender.required'      => 'Please select your gender.',
            'gender.in'            => 'Gender must be either Male or Female.',
            
            'g-recaptcha-response.required' => 'Please complete the reCAPTCHA.',
            'g-recaptcha-response' => 'reCAPTCHA verification failed. Please try again.',
        ]);

        try {
            $enquiry = new Enquiry();
            $enquiry->name = $request->input('name');
            $enquiry->grade = $request->input('grade');
            $enquiry->mobile = $request->input('mobile');
            $enquiry->email = $request->input('email');
            $enquiry->insterested = $request->input('insterested');
            $enquiry->gender = $request->input('gender');
           
            $enquiry->save();

            Session::flash('success', 'Your enquiry has been submitted successfully!');
            return redirect()->back();
        } catch (\Exception $e) {
            Session::flash('error', 'Something went wrong. Please try again.');
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
   
    
}
