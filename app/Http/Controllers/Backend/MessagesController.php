<?php

namespace App\Http\Controllers\Backend;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;

// Messenger package has been removed - using fallback implementation
// use Messenger;

class MessagesController extends Controller
{
    /**
     * Check if Messenger functionality is available
     * 
     * @return bool
     */
    private function messengerAvailable()
    {
        return class_exists('Messenger') || function_exists('app') && app()->bound('messenger');
    }

    /**
     * Display the messages inbox
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request){
        $thread="";
        $teachers = User::role('teacher')->get()->pluck('name', 'id');

        // Check if user has threads relationship (depends on Messenger package)
        if (method_exists(auth()->user(), 'threads')) {
            auth()->user()->load('threads.messages.sender');

            $unreadThreads = [];
            $threads = [];
            $userThreads = auth()->user()->threads ?? [];
            foreach($userThreads as $item){
                if($item->unreadMessagesCount > 0){
                    $unreadThreads[] = $item;
                }else{
                    $threads[] = $item;
                }
            }
            $threads = Collection::make(array_merge($unreadThreads,$threads));

           if(request()->has('thread') && ($request->thread != null)){
               if(request('thread')){
                   $thread = auth()->user()->threads()
                       ->where('message_threads.id','=',$request->thread)
                       ->first();
                   if ($thread) {
                       auth()->user()->markThreadAsRead($thread->id);
                   }
               }else if($thread == ""){
                   abort(404);
               }
           }
        } else {
            $threads = collect([]);
        }

        $agent = new Agent();
       if($agent->isMobile()){
           $view = 'backend.messages.index-mobile';
       }else{
           $view = 'backend.messages.index-desktop';
       }
        return view($view, [
            'threads' => $threads,
            'teachers' => $teachers,
            'thread' => $thread
        ]);
    }

    /**
     * Send a new message
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(Request $request){
        $this->validate($request,[
           'recipients' => 'required',
           'message' => 'required'
        ],[
           'recipients.required' => 'Please select at least one recipient',
           'message.required' => 'Please input your message'
        ]);

        // Messenger package removed - returning with error message
        if (!$this->messengerAvailable()) {
            return redirect()->back()->withFlashWarning('Messaging functionality is currently unavailable. Please contact the administrator.');
        }

        $message = Messenger::from(auth()->user())->to($request->recipients)->message($request->message)->send();
        return redirect(route('admin.messages').'?thread='.$message->thread_id);
    }

    /**
     * Reply to an existing thread
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reply(Request $request){
        $this->validate($request,[
            'message' => 'required'
        ],[
            'message.required' => 'Please input your message'
        ]);

        // Messenger package removed - returning with error message
        if (!$this->messengerAvailable()) {
            return redirect()->back()->withFlashWarning('Messaging functionality is currently unavailable. Please contact the administrator.');
        }

        $thread = auth()->user()->threads()
            ->where('message_threads.id','=',$request->thread_id)
            ->first();
        $message = Messenger::from(auth()->user())->to($thread)->message($request->message)->send();

        return redirect(route('admin.messages').'?thread='.$message->thread_id)->withFlashSuccess('Message sent successfully');
    }

    /**
     * Get unread message count
     *
     * @param Request $request
     * @return array
     */
    public function getUnreadMessages(Request $request){
        // Check if user has messaging methods available
        if (!method_exists(auth()->user(), 'unreadMessagesCount') || !method_exists(auth()->user(), 'threads')) {
            return ['unreadMessageCount' => 0, 'threads' => []];
        }

        $unreadMessageCount = auth()->user()->unreadMessagesCount ?? 0;
        $unreadThreads = [];
        $threads = auth()->user()->threads ?? [];
        foreach($threads as $item){
            if($item->unreadMessagesCount > 0){
                $data = [
                  'thread_id' => $item->id,
                  'message' => str_limit($item->lastMessage->body ?? '', 35),
                  'unreadMessagesCount' => $item->unreadMessagesCount,
                  'title' => $item->title
                ];
                $unreadThreads[] = $data;
            }
        }
        return ['unreadMessageCount' =>$unreadMessageCount,'threads' => $unreadThreads];
    }
}
