<?php
session_start();
require_once './vendor/autoload.php';
include ('db_conn.php');

use Twilio\Rest\Client;


$sid = $configVariables['sid'];
$token = $configVariables['token'];
$from = $configVariables['from'];

$countryCode = $_POST['country_code'];

$phoneNumber = $_POST['phone_number'];


processData($countryCode, $phoneNumber, $connection);

function processData($countryCode, $phoneNumber, $connection) {
    $sql = "SELECT * FROM `users` WHERE `phone_number` = '$phoneNumber' LIMIT 1";
    
    $isPhoneNumberPresent = mysqli_query($connection, $sql) or die(mysqli_error($connection));
      
    if(mysqli_num_rows($isPhoneNumberPresent) > 0) {
        $record = mysqli_fetch_assoc($isPhoneNumberPresent);
        if($record['is_verified'] == '1') {
            echo "Number Already Verified";die;
        } else {
            sendOTP($countryCode, $phoneNumber, $connection);
            header('Location: /verify.php');  
        }
    } else {
        $sql = "INSERT INTO users VALUES(DEFAULT, '$phoneNumber', '0', '0')";
        $isPhoneNumberPresent = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        sendOTP($countryCode, $phoneNumber, $connection);
        header('Location: /verify.php');  
    }
}


function sendOTP($countryCode, $phoneNumber, $connection) {
    try {
        global $sid;
        global $token;
        global $from;
        
        $client = new Client($sid , $token);
        
        $otp = generateOTP();

        $message = $client->messages
                  ->create($countryCode . $phoneNumber, // to
                           array("from" => $from, "body" => "Your One Time Password is " . $otp)
                  );   
              
        $sql = "UPDATE `users` SET `otp`=$otp WHERE `phone_number` = '$phoneNumber'";
        $verified = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        $_SESSION['phoneNumber'] = $phoneNumber;                  
    } catch(\Exception $ex) {
        print_r($ex);die;
    }
    
}


function generateOTP() {
    return rand(1000, 9999);
}