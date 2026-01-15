<?php

namespace App\Http\Controllers\Backend\Auth\User;

use App\Http\Controllers\Controller;
use App\Repositories\Frontend\Auth\UserRepository;
use App\Http\Requests\Frontend\User\UpdateProfileRequest;

/**
 * Class ProfileController.
 */
class ProfileController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * ProfileController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param UpdateProfileRequest $request
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     */
    public function update(UpdateProfileRequest $request)
    {
        
        $fieldsList = [];
        if(config('registration_fields') != NULL){
            $fields = json_decode(config('registration_fields'));

            foreach ($fields  as $field){
                $fieldsList[] =  ''.$field->name;
            }
        }
        $output = $this->userRepository->update(
            $request->user()->id,
            $request->only('first_name','middle_name', 'last_name','dob', 'phone', 'gender', 'address', 'city', 'pincode', 'state', 'country', 'avatar_type', 'avatar_location'),
            $request->has('avatar_location') ? $request->file('avatar_location') : false,


        );
        if($request->user()->hasRole('teacher')){
            $payment_details = [
                'bank_name'         => request()->payment_method == 'bank'?request()->bank_name:'',
                'ifsc_code'         => request()->payment_method == 'bank'?request()->ifsc_code:'',
                'account_number'    => request()->payment_method == 'bank'?request()->account_number:'',
                'account_name'      => request()->payment_method == 'bank'?request()->account_name:'',
                'paypal_email'      => request()->payment_method == 'paypal'?request()->paypal_email:'',
            ];

            $data = [
                'facebook_link'     => request()->facebook_link,
                'twitter_link'      => request()->twitter_link,
                'linkedin_link'     => request()->linkedin_link,
                'instagram_link'     => request()->instagram_link,
                'payment_method'    => request()->payment_method,
                'payment_details'   => json_encode($payment_details),
                'description'       => request()->description,
                
                // 'photo_id_proof'       => request()->photo_id_proof,
                // 'pan_card'       => request()->pan_card,
                // 'aadhar_card'       => request()->aadhar_card,
                
            ];
             if ($request->has('upload_cv')) {
            $aadharp=time().".".$request->upload_cv->getClientOriginalExtension();
             $request->upload_cv->move(public_path('storage/upload_cv'), $aadharp);
           
            $data["upload_cv"] =  'storage/upload_cv/'.$aadharp;

        }
             if ($request->has('aadhar_card')) {
            $aadharp=time().".".$request->aadhar_card->getClientOriginalExtension();
             $request->aadhar_card->move(public_path('storage/aadhar_card'), $aadharp);
           
            $data["aadhar_card"] =  'storage/aadhar_card/'.$aadharp;

        }

        if ($request->has('pan_card')) {
            $panp=time().".".$request->pan_card->getClientOriginalExtension();
             $request->pan_card->move(public_path('storage/pan_card'), $panp);
           
            $data["pan_card"] = 'storage/pan_card/'.$panp;
        }

        if ($request->has('photo_id_proof')) {
            $idproofp=time().".".$request->photo_id_proof->getClientOriginalExtension();
             $request->photo_id_proof->move(public_path('storage/photo_id_proof'), $idproofp);
           
            $data["photo_id_proof"] = 'storage/photo_id_proof/'.$idproofp;
        }


       // dd($data);


            $request->user()->teacherProfile->update($data);
        }
        // E-mail address was updated, user has to reconfirm
        if (is_array($output) && $output['email_changed']) {
            auth()->logout();

            return redirect()->route('frontend.auth.login')->withFlashInfo(__('strings.frontend.user.email_changed_notice'));
        }

        return redirect()->route('admin.account')->withFlashSuccess(__('strings.frontend.user.profile_updated'));
    }
}
