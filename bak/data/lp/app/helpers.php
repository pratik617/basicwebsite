<?php

use Carbon\Carbon;

if (! function_exists('formatDate')) {
    function formatDate($value) {
		return Carbon::createFromFormat('d/m/Y',$value)->format('M d, Y');
    }
}
