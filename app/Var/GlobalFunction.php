<?php

namespace App\Var;

class GlobalFunction {
	
	public static function format_nokp($nokp) 
	{
		if (strlen($nokp) == 12 AND is_numeric($nokp)){
			$nokp1 = substr($nokp, 0, 6);
			$nokp2 = substr($nokp, 6, 2);
			$nokp3 = substr($nokp, 8, 4);

			$converted_nokp = "$nokp1-$nokp2-$nokp3";
			return $converted_nokp;
		}
		else {
			return $nokp;
		}
	}

}
