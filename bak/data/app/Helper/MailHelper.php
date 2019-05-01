<?php 
namespace App\Helper;

use Illuminate\Database\Eloquent\Helper;
use App\Helper\RandomHelper;
use App\Helper\Exceptions;
use Mail;

class MailHelper
{
	public static function sendOTP($data="",$otp="")
	{
		try {
			if($data=="" || $otp=="") {
				return "false";
			} else{

				$sendMail = Mail::send('mail.register.send_otp',['data'=>$data,'otp'=>$otp] , function($message) use ($data) {
			        $message->to($data['email'], $data['name'])->subject('RideApp Verification Code');
			        $message->from('shineinfosoft27@gmail.com','RideApp');
			    });
		        if($sendMail){
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

	public static function sendChangeOTP($data="",$new_email="",$otp="")
	{
		try {
			if($data=="" || $otp=="" || $new_email=="") {
				return "false";
			} else{

				$sendMail = Mail::send('mail.change.mail',['data'=>$data,'otp'=>$otp] , function($message) use ($data,$new_email) {
			        $message->to($new_email, $data['name'])->subject('RideApp Verification Code');
			        $message->from('shineinfosoft27@gmail.com','RideApp');
			    });
		        if($sendMail==""){
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

	public static function driverRegister($data,$password)
	{
		try {
			$sendMail = Mail::send('mail.register.driver_register',['data'=>$data,'password'=>$password] , function($message) use ($data) {
		        $message->to($data['email'], $data['name'])->subject('RideApp Driver Registration.');
		        $message->from('shineinfosoft27@gmail.com','RideApp');
		    });
			if($sendMail){
		        return "true";
	        } else{
	        	return "false";
	        }
		} catch (Exception $e) {
			Exceptions::exception($e);
		}
	}

	public static function forgotCustomer($customer,$email,$token)
	{
		try {
			if($email!="" && $token!=""){
				Mail::send('mail.forgot.customer',['data'=>$customer,'email'=>$email,'token'=>$token],
					function($message) use($customer) {
					$message->to($customer->email,$customer->name)
							->subject('Rideapp Password Reset Link')
							->from('shineinfosoft27@gmail.com','RideApp');
				});
				return true;
			} else{
				return false;
			}
		} catch (Exception $e) {
			Exceptions::exception($e);
		}
	}
	public static function forgotDriver($driver,$email,$token)
	{
		try {
			if($email!="" && $token!=""){
				Mail::send('mail.forgot.driver',['data'=>$driver,'email'=>$email,'token'=>$token],
					function($message) use($driver) {
					$message->to($driver->email,$driver->name)
							->subject('Rideapp Password Reset Link')
							->from('shineinfosoft27@gmail.com','RideApp');
				});
				return true;
			} else{
				return false;
			}
		} catch (Exception $e) {
			Exceptions::exception($e);
		}
	}

	public static function forgotAdmin($admin,$email,$token)
	{
		try {
			if($email!="" && $token!=""){
				Mail::send('mail.forgot.admin',['data'=>$admin,'email'=>$email,'token'=>$token],
					function($message) use($admin) {
					$message->to($admin->email,$admin->firstname." ".$admin->lastname)
							->subject('Rideapp Password Reset Link')
							->from('shineinfosoft27@gmail.com','RideApp');
				});
				return true;
			} else{
				return false;
			}
		} catch (Exception $e) {
			Exceptions::exception($e);
		}
	}
}
?>