<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\SystemLogController;
use App\Enum\LogAttributes;
use App\Enum\LogType;
use Illuminate\Support\Facades\DB;

abstract class Controller
{
    //
    public static $DATA_STATUS_DELETED = 3;
    public static $DATA_STATUS_ACTIVE = 1;
    public static $DATA_STATUS_INACTIVE = 0;

    public static $LEAVE_IN_PROGRESS = 0;
    public static $LEAVE_APPROVED = 1;
    public static $LEAVE_REJECTED = 2;
    
    protected $status_code;

    protected $log_enabled = false;

    protected $message_response = [
        'status' => false,
        'message'=> 'initialize'
    ];

    protected function handleSuccessResponse(Request $request, $status, $message, $data = [], $status_code = 200){
        $this->message_response['status'] = $status;
        $this->message_response['message'] = $message;

        foreach($data as $key => $value){
            if (!in_array($key, ['status', 'message'])) {
                $this->message_response[$key] = $value;
            }
        }

        $this->status_code = $status_code;

        if($this->log_enabled)
            $this->log($request, $message, LogType::Success);
    }

    protected function handleException(Request $request, $message, $statusCode = 500, $logType = LogType::Failed)
    {
        if (DB::transactionLevel() > 0) {
            DB::rollBack();
        }

        $this->message_response['status'] = false;
        $this->message_response['message'] = $message;
        $this->status_code = $statusCode;

        if($this->log_enabled)
            $this->log($request, is_array($message) ? json_encode($message) : $message, $logType);
    }

    protected function uniformedResponse(){
        return response()->json($this->message_response,$this->status_code);
    }

    

    protected function log(Request $request, string $log_action, LogType $log_type): void {
        $request->attributes->set(LogAttributes::LogEnabled->value, $this->log_enabled ?? false);
        $request->attributes->set(LogAttributes::LogAction->value, $log_action);
        $request->attributes->set(LogAttributes::LogType->value, $log_type->value);
    }

}
