<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
// SendsPasswordResetEmails trait removed in Laravel 10 - use Password facade methods instead
// use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

/**
 * Class ForgotPasswordController.
 */
class ForgotPasswordController extends Controller
{
    // SendsPasswordResetEmails trait removed - implement password reset email sending manually

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm() 
    {
        return view('frontend.auth.passwords.email');
    }
}
