<?php
 
namespace App\Http\Controllers\Api\V1;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PublicHoliday;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Enum\LogType;
use App\Enum\DataStatus;
  
class PublicHolidayController extends Controller
{
    public function listforce(Request $request)
    {
        return PublicHoliday::all();
    }

    public function list(Request $request, $year, $month = null){
        try{
            if(!$year) throw new Exception("Year cannot be null");

            if($month){
                $ph = PublicHoliday::where('year', $year)->whereMonth('holiday_date', $month)->where('status', DataStatus::Active)->get();
            }
            else{
                $ph= PublicHoliday::where('year', $year)->where('status', DataStatus::Active)->get();
            }

            $this->handleSuccessResponse($request, true, 'List Public Holiday',['data' => $ph]);
        }
        catch(Exception $e){
            $this->handleException($request, $e->getMessage());
        }
        
        return $this->uniformedResponse();
    }

    public function listByRange(Request $request){
         try{
            if($request->start_date == null || $request->end_date == null) throw new Exception("start/end date cannot be empty");
            
            $ph = PublicHoliday::whereBetween('holiday_date',[$request->start_date,$request->end_date])->get();

            $this->handleSuccessResponse($request, true, 'List Public Holiday',['data' => $ph]);
         }
        catch(Exception $e){
            $this->handleException($request, $e->getMessage());
        }

         return $this->uniformedResponse();
    }

    public function insert(Request $request){
        $this->log_enabled = true;

        try{
            DB::beginTransaction();
            $this->validate($request);
            $formData = [
                'year' => $request->year,
                'holiday_date' => $request->holiday_date,
                'holiday_name' => $request->holiday_name,
                'status'=> $request->status,
                'created_by' => Auth::id()
            ];

            $data = PublicHoliday::create($formData);
            DB::commit();

            $this->handleSuccessResponse($request, true, 'Insert New Public Holiday',['data' => $data]);
        }
        catch(ValidationException $e){
            $this->handleException($request, $e->errors(), 422);
        }
        catch(Exception $e){
            $this->handleException($request, $e->getMessage());
        }

        return $this->uniformedResponse();
    }

    public function update(Request $request, $id){
        $this->log_enabled = true;

        try{
            DB::beginTransaction();
            $this->validate($request);
            $update = PublicHoliday::find($id);
            if(!$update) throw new Exception('Record does not exist');
            $formData = [
                'year' => $request->year,
                'holiday_date' => $request->holiday_date,
                'holiday_name' => $request->holiday_name,
                'status'=> $request->status,
                'updated_by' => Auth::id()
            ];

            $update->update($formData);
            DB::commit();
            $this->handleSuccessResponse($request, true, 'Update Public Holiday',['data' => $update]);
        }
        catch(ValidationException $e){
            $this->handleException($request, $e->errors(), 422);
        }
        catch(Exception $e){
            $this->handleException($request, $e->getMessage());
        }

        return $this->uniformedResponse();
    }

    public function delete(Request $request, $id){
        $this->log_enabled = true;

        try{
            DB::beginTransaction();
            $delete = PublicHoliday::find($id);
            if(!$delete){
                throw new Exception('Record does not exist');
            }
            $formData = [
                'status'=> static::$DATA_STATUS_DELETED,
                'updated_by' => Auth::id()
            ];
            $delete->update($formData);
            DB::commit();
            
            $this->handleSuccessResponse($request, true, 'Delete Public Holiday');
        }
        catch(ValidationException $e){
            $this->handleException($request, $e->errors(), 422);
        }
        catch(Exception $e){
            $this->handleException($request, $e->getMessage());
        }

        return $this->uniformedResponse();
    }

    private function validate(Request $request){
        return true;
    }
}