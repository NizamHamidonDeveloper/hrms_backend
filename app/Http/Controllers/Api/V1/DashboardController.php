<?php
 
namespace App\Http\Controllers\Api\V1;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\App;
use Exception;
  
  
class DashboardController extends Controller
{
    public function redirectDashboard(Request $request, $type){
        try{
            if($type == null || $type == "") throw new exception("dashboard type not included");

            switch($type){
                case "employee":
                    return $this->employeeDashboard($request);
                    break;
                case "manager":
                    return $this->managerDashboard($request);
                    break;
                case "officer":
                    return $this->officerDashboard($request);
                    break;
                case "hr":
                    return $this->hrDashboard($request);
                    break;
                default:
                    return $this->employeeDashboard($request);
                    break;
            }
        }
        catch(Exception $e){

        } 
    }

    private function employeeDashboard(Request $request){

    }

    private function managerDashboard(Request $request){

    }

    private function officerDashboard(Request $request){

    }

    private function hrDashboard(Request $request){

    }
}