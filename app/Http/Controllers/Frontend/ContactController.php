<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use App\Mail\Frontend\Contact\SendContact;
use App\Http\Requests\Frontend\Contact\SendContactRequest;
use Illuminate\Support\Facades\Session;

use App\Rules\Recaptcha;

use App\Mail\Frontend\Demo\ContactRequestEmail;
use Validator;
 
/** 
 * Class ContactController.
 */
class ContactController extends Controller
{

    private $path;

    public function __construct()
    {
        $path = 'frontend';
        if(session()->has('display_type')){
            if(session('display_type') == 'rtl'){
                $path = 'frontend-rtl';
            }else{
                $path = 'frontend';
            }
        }else if(config('app.display_type') == 'rtl'){
            $path = 'frontend-rtl';
        }
        $this->path = $path;
    }


    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $categories = Category::where("parent","0")->get();
        return view('frontend.contact',compact('categories'));
    }

    /**
     * @param SendContactRequest $request
     *
     * @return mixed
     */
    public function send(Request $request)
    {
        // dd($request->all());
        // Define validation rules and messages
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'message' => 'required',
            'g-recaptcha-response' => ['required', new Recaptcha()],
        ], [
            'g-recaptcha-response.required' => 'Please verify that you are not a robot.',
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            Session::flash('flash_error', $validator->errors()->first());
            return redirect()->back()->withInput();
        }
    
        // Create and save the contact entry
        $contact = new Contact();
        $contact->name = $request->name;
        $contact->number = $request->phone;
        $contact->email = $request->email;
        $contact->message = $request->message;
        
        $contact->save();
    
        // Try to send an email
        try { 
           
            Mail::to(env('ADMIN_EMAIL'))->send(new ContactRequestEmail(
                $request->name, 
                $request->email, 
                $request->phone, 
                $request->message,
                // dd( $request->name, $request->email, $request->phone, $request->message)
            ));
            
            // Set success flash message if email sent successfully
            Session::flash('flash_message', 'We have received your contact request. Our team will get in touch with you shortly!');
        } catch (\Exception $e) {
            // Set error flash message if email sending fails
            Session::flash('flash_message', 'An error occurred while sending your message. Please try again later.');
        }
     
        // Redirect back to the previous page
        return redirect()->back();
    }
    
}
