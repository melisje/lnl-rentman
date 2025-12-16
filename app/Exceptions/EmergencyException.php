<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class EmergencyException extends Exception
{
    /**
     * Report the exception.
     */
    public function report(): void
    {
        Log::emergency($this->getMessage());  //, ['exception' => $this]);
    }
}
