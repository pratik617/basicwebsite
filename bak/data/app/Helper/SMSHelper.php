<?php 
namespace App\Helper;

use Illuminate\Database\Eloquent\Helper;
use AWS;
use App\Helper\RandomHelper;
use App\Helper\Exceptions;

class SMSHelper
{
	public static function sendOTP($country_code="",$number="")
	{
		try {
			if($country_code=="" || $number=="") {
				return "false";
			} else{
				$sms = AWS::createClient('sns');
				$otp = RandomHelper::randomOTP();
				$message = " is the one time password (OTP) for Rideapp. This is usable once time and PLEASE DO NOT SHARE WITH ANYONE.";
		        $sendSMS = $sms->publish([
		            'Message' => $otp.$message,
		            'PhoneNumber' => $country_code.$number,    
		            'MessageAttributes' => [
		                'AWS.SNS.SMS.SMSType'  => [
		                    'DataType'    => 'String',
		                    'StringValue' => 'Transactional',
		                 ]
		             ],
		        ]);
		        if($sendSMS){
			        $data['otp'] = $otp;
			        $data['mobile'] = $country_code.$number;
			        return $data;
		        } else{
		        	return "false";
		        }
			}
			exit;

		} catch (Exception $e) {
			Exceptions::exception($e);	
		}
	}

	public static function driverRegister($data,$password)
	{
		try {
			$sms = AWS::createClient('sns');
			$message = "This is to inform you that, Your account with RideApp Driver has been created successfully. Your Password : ".$password."  PLEASE DO NOT SHARE WITH ANYONE.";	
			$sendSMS = $sms->publish([
	            'Message' => $message,
	            'PhoneNumber' => $data['country_code'].$data['contact_no'],    
	            'MessageAttributes' => [
	                'AWS.SNS.SMS.SMSType'  => [
	                    'DataType'    => 'String',
	                    'StringValue' => 'Transactional',
	                 ]
	             ],
	        ]);
	        if($sendSMS){
		        return "true";
	        } else{
	        	return "false";
	        }
		} catch (Exception $e) {
			Exceptions::exception($e);	
		}
	}

	public static function sendSOSLink($country_code="",$number="",$link="",$customer_id="")
	{
		try {
			if($country_code=="" || $number=="" || $link=="") {
				return "false";
			} else{
				$sms = AWS::createClient('sns');
				$link = $link;
				$message = " SOS Link ";
		        $sendSMS = $sms->publish([
		            'Message' => $message.$link,
		            'PhoneNumber' => $country_code.$number,    
		            'MessageAttributes' => [
		                'AWS.SNS.SMS.SMSType'  => [
		                    'DataType'    => 'String',
		                    'StringValue' => 'Transactional',
		                 ]
		             ],
		        ]);
		        if($sendSMS){
			        return "true";
		        } else{
		        	return "false";
		        }
			}
			exit;
		} catch (Exception $e) {
			Exceptions::exception($e);
		}
	}
}
?>