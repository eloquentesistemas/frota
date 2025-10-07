<?php

namespace App\TollBox;

use Illuminate\Support\Facades\Log;

class ExceptionLogSchema
{

    public static function error(\Exception $e): void
    {
        Log::error("code:".$e->getCode()."message".$e->getMessage()."file:".$e->getFile()."line:".$e->getLine());
    }
}
