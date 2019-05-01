<?php 
namespace App\Helper;

use Illuminate\Database\Eloquent\Helper;
use App\Model\Exception;

class Exceptions
{
	public static function exception($ex)
	{
		$data = ([	
				'error' => $ex,
				'created_at' => date('Y-m-d H:i:s')
			]);
		
		$create = Exception::insert($data);
		if($create) {
			echo $ex;
		}
	}
}


?>