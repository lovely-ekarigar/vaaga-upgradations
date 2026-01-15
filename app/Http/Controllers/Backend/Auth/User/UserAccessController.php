<?php

namespace App\Http\Controllers\Backend\Auth\User;

use App\Models\Auth\User;
use App\Helpers\Auth\Auth;
use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Auth\User\ManageUserRequest;
use Illuminate\Support\Facades\Log;

class UserAccessController extends Controller
{
    public function loginAs(ManageUserRequest $request, User $user)
    {
        // Debug current session
        Log::debug('Current session data:', session()->all());
        
        // Prevent login as self
        if ($request->user()->id === $user->id) {
            Log::error('Attempt to login as self', ['user_id' => $user->id]);
            throw new GeneralException('You cannot login as yourself.');
        }

        // If already logged in as someone else
        if (session()->has('admin_user_id') && session()->has('temp_user_id')) {
            if (session()->get('admin_user_id') === $user->id) {
                Log::error('Attempt to login as original admin user', ['user_id' => $user->id]);
                throw new GeneralException('Cannot login as your original admin account.');
            }

            // Update temp user session
            session(['temp_user_id' => $user->id]);
            Log::debug('Updated temp user session', ['temp_user_id' => $user->id]);
        } else {
            // Create new admin session
            session([
                'admin_user_id' => $request->user()->id,
                'admin_user_name' => $request->user()->full_name,
                'temp_user_id' => $user->id
            ]);
            Log::debug('Created new admin session', [
                'admin_user_id' => $request->user()->id,
                'temp_user_id' => $user->id
            ]);
        }

        try {
            // Log in the target user
            auth()->loginUsingId($user->id);
            Log::info('Successfully logged in as user', ['user_id' => $user->id]);
            
            // Verify login worked
            if (!auth()->check() || auth()->id() !== $user->id) {
                throw new GeneralException('Failed to authenticate as target user.');
            }
            
            return redirect()->route($this->getHomeRoute());
        } catch (\Exception $e) {
            Log::error('Login As failed', [
                'error' => $e->getMessage(),
                'admin_id' => $request->user()->id,
                'target_id' => $user->id
            ]);
            throw new GeneralException('Failed to login as user: ' . $e->getMessage());
        }
    }

    protected function getHomeRoute()
    {
        // Fallback to default route if home_route() doesn't exist
        if (function_exists('home_route')) {
            return home_route();
        }
        return 'frontend.index'; // Adjust to your actual frontend route
    }
}