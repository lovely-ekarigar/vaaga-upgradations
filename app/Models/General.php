<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class General 
{
   public function sendOtp($phone,$otp)
{
      
       $in=array(
'otp'=>$otp,
'otpex'=>time()+300
       ); 
	   //print_r($in);
      
     //$udata=$this->edata->getData('addd',array('cid' =>$cid));

//Your authentication key
//$authKey = "31325265646e746563683130301599805612";
//373476667061746e613130301600497308
$authKey = "31325265646e746563683130301599805612";

//Multiple mobiles numbers separated by comma


//Sender ID,While using route4 sender id should be 6 characters long.
$senderId = "MyOFES";

//Your message to send, Add URL encoding here.
$rndno=$otp;

$message = "$rndno is your Login otp for redn.in MyOFES";
//Define route
$route = "06";
//Prepare you post parameters
$postData = array(
'authentic-key' => $authKey,
'number' => "91".$phone,
'message' => $message,
'senderid' => $senderId,
'route' => $route,
'templateid' =>1207163844932366404,
);
//API URL
$url="http://sms.smsindori.com/http-tokenkeyapi.php";
// init the resource
$ch = curl_init();
curl_setopt_array($ch, array(
CURLOPT_URL => $url,
CURLOPT_RETURNTRANSFER => true,
CURLOPT_POST => true,
CURLOPT_POSTFIELDS => $postData
//,CURLOPT_FOLLOWLOCATION => true
));
//Ignore SSL certificate verification
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//get response
 $output = curl_exec($ch);
//Print error if any
if(curl_errno($ch))
{
 'error:' . curl_error($ch);
}
curl_close($ch);
}
    
}
 