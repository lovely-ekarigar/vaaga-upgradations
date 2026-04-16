<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

/**
 * Class VerifyCsrfToken.
 */
class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'api/*',
        'payment/*',
        'pay-confirm/*',
        'pay-confirm-test/*',
        'user/upload-image-editorjs', // Editor.js ImageTool upload (auth still required)
        'user/upload-image-ckeditor', // CKEditor 4 image upload (auth still required)
        'login',
        'login/*',
        'userlogin',
        'userlogin/*',
        'admin/login',
        'admin/login/*',
        'register',
        'register/*',
        'logout',
    ];
}
