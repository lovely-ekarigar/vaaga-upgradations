<?php

namespace App\Models;


class Elearn 
{
    

   public function eClass($req,$params){
$fields_string = http_build_query($params);
//$url = 'http://preferwork.com/api/get/Uyn4YZLPaEqnRBd7g9dP3vztS6fk55N/'.$req.'?'.$fields_string;

$key="53iZbj6YMUJsKNhgoXC3kn1o2Vg1fBV5gQyqFiCgl0";
$fields_string = http_build_query($params);
$shstr=$req.$fields_string.$key;
$csx=sha1($shstr);

$url = 'https://manager.bigbluemeeting.com/bigbluebutton/api/'.$req.'?'.$fields_string."&checksum=".$csx;
//dd($url);
    $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $url);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch); 
        // dd($output);
        $new = simplexml_load_string($output); 
return (array)$new;
       
}

public function eClassJson($req,$params){
$fields_string = http_build_query($params);
//$url = 'http://preferwork.com/api/get/Uyn4YZLPaEqnRBd7g9dP3vztS6fk55N/'.$req.'?'.$fields_string;

$key="53iZbj6YMUJsKNhgoXC3kn1o2Vg1fBV5gQyqFiCgl0";
$fields_string = http_build_query($params);
$shstr=$req.$fields_string.$key;
$csx=sha1($shstr);

$url = 'https://manager.bigbluemeeting.com/bigbluebutton/api/'.$req.'?'.$fields_string."&checksum=".$csx;
//dd($url);
    $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $url);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch); 
        
        $new = json_decode($output,true); 
return $new;
       
}

public function getLaunch($params){
$fields_string = http_build_query($params);
//$url = 'http://preferwork.com/api/getLaunch/Uyn4YZLPaEqnRBd7g9dP3vztS6fk55N/?'.$fields_string;


$out=array();
   
          
                $req="join";
        $key="53iZbj6YMUJsKNhgoXC3kn1o2Vg1fBV5gQyqFiCgl0";

$shstr=$req.$fields_string.$key;
$csx=sha1($shstr);

$url = 'https://manager.bigbluemeeting.com/bigbluebutton/api/'.$req.'?'.$fields_string."&checksum=".$csx;
//   $ch = curl_init();

//          // set url
//          curl_setopt($ch, CURLOPT_URL, $url);

//          //return the transfer as a string
//          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

//          // $output contains the output string
//          $output = curl_exec($ch);

//          // close curl resource to free up system resources
//          curl_close($ch); 
//          $new = simplexml_load_string($output); 
         
          $out=array("status"=>true,"url"=>$url);
  
//     dd($url);
    
return $out;
}

public function sendROTP($otp,$phone){



$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://control.msg91.com/api/v5/flow',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
  "template_id": "64bc0cc6d6fc0517d05b6562",
  "short_url": "0",
  "realTimeResponse": "0", 
  "recipients": [
    {
      "mobiles": "91'.trim($phone).'",
      "otp": "'.$otp.'"
    }
  ]
}',
  CURLOPT_HTTPHEADER => array(
    'accept: application/json',
    'authkey: 401284ApGWjkfa66b6263aP1',
    'content-type: application/json',
    'Cookie: PHPSESSID=8cn2h72iit8b9nsnquaurmvpe4'
  ),
));

$response = curl_exec($curl);

curl_close($curl);

return $response;


}

public function tutorActivationSMS($name,$phone){




$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://control.msg91.com/api/v5/flow',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
  "template_id": "64b81604d6fc055525209083",
  "short_url": "0",
  "realTimeResponse": "0", 
  "recipients": [
    {
      "mobiles": "91'.trim($phone).'",
      "name": "'.$name.'"
    }
  ]
}',
  CURLOPT_HTTPHEADER => array(
    'accept: application/json',
    'authkey: 401284ApGWjkfa66b6263aP1',
    'content-type: application/json',
    'Cookie: PHPSESSID=8cn2h72iit8b9nsnquaurmvpe4'
  ),
));

$response = curl_exec($curl);

curl_close($curl);

return $response;


}

public function paymentStudentSMS($name,$phone,$amount){







$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://control.msg91.com/api/v5/flow',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
  "template_id": "64babc60d6fc050d3f625112",
  "short_url": "0",
  "realTimeResponse": "0", 
  "recipients": [
    {
      "mobiles": "91'.trim($phone).'",
      "name": "'.$name.'",
       "number": "'.$amount.'"
    }
  ]
}',
  CURLOPT_HTTPHEADER => array(
    'accept: application/json',
    'authkey: 401284ApGWjkfa66b6263aP1',
    'content-type: application/json',
    'Cookie: PHPSESSID=8cn2h72iit8b9nsnquaurmvpe4'
  ),
));

$response = curl_exec($curl);

curl_close($curl);

return $response;


}

public function demoStudentSMS($name,$phone){






$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://control.msg91.com/api/v5/flow',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
  "template_id": "64b816d9d6fc053cf002bf72",
  "short_url": "0",
  "realTimeResponse": "0", 
  "recipients": [
    {
      "mobiles": "91'.trim($phone).'",
      "name": "'.$name.'"
    }
  ]
}',
  CURLOPT_HTTPHEADER => array(
    'accept: application/json',
    'authkey: 401284ApGWjkfa66b6263aP1',
    'content-type: application/json',
    'Cookie: PHPSESSID=8cn2h72iit8b9nsnquaurmvpe4'
  ),
));

$response = curl_exec($curl);

curl_close($curl);

return $response;

}

public function teacherRSMS($name,$phone){



$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://control.msg91.com/api/v5/flow',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
  "template_id": "64babcf7d6fc057f7f0a4252",
  "short_url": "0",
  "realTimeResponse": "0", 
  "recipients": [
    {
      "mobiles": "91'.trim($phone).'",
      "name": "'.$name.'"
    }
  ]
}',
  CURLOPT_HTTPHEADER => array(
    'accept: application/json',
    'authkey: 401284ApGWjkfa66b6263aP1',
    'content-type: application/json',
    'Cookie: PHPSESSID=8cn2h72iit8b9nsnquaurmvpe4'
  ),
));

$response = curl_exec($curl);

curl_close($curl);

return $response;

}

public function studentRSMS($name,$phone){



$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://control.msg91.com/api/v5/flow',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
  "template_id": "64babd6ed6fc0513b953a582",
  "short_url": "0",
  "realTimeResponse": "0", 
  "recipients": [
    {
      "mobiles": "91'.trim($phone).'",
      "name": "'.$name.'"
    }
  ]
}',
  CURLOPT_HTTPHEADER => array(
    'accept: application/json',
    'authkey: 401284ApGWjkfa66b6263aP1',
    'content-type: application/json',
    'Cookie: PHPSESSID=8cn2h72iit8b9nsnquaurmvpe4'
  ),
));

$response = curl_exec($curl);

curl_close($curl);

return $response;


}



}
