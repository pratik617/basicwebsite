<?php 
namespace App\Helper;

use Illuminate\Database\Eloquent\Helper;

class ResponseMessage
{
	public static function success($msg,$data = array())
	{
		echo json_encode(['status' => 1, 'message' => $msg, 'data' => $data]);
		exit;
	}

	public static function error($msg,$data = array())
	{
		echo json_encode(['status' => 0, 'message' => $msg , 'data' => $data]); 	
		exit;
	}

	public static function unauthorize($msg)
	{
		echo json_encode(['status' => 401, 'message' => $msg , 'data' => array()]);
		exit;
	}
}
?>