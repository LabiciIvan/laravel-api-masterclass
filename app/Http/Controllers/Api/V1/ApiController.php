<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function include(string $relationship): bool {
        $param = request()->query('include');

        if (!isset($param)) {
            return false;
        }


        // Handle relatioships passed in and separated by comma ",".
        $includeValues = explode(',', strtolower($param));

        return in_array(strtolower($relationship), $includeValues);
    }
}
