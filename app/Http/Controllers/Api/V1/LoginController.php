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
use App\Enum\LogType;
use App\Enum\LogAttributes;
  
class LoginController extends Controller
{
    public function login(Request $request)
    {
        $this->log_enabled = true;
        try{
            $user = User::where('username', $request->username)->where('status',static::$DATA_STATUS_ACTIVE)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                throw new Exception('invalid credentials');
            }

            $profile = Profile::where('user_id', $user->id)->where('status', static::$DATA_STATUS_ACTIVE)->first();

            // $token = Auth::user()->createToken('passportToken')->accessToken;
            $token = $this->issueToken($request, $user);
            
            $request->attributes->set(LogAttributes::LogUserId->value, $user->id); // needed since login does not use bearer token.
            $this->handleSuccessResponse($request, true, 'User Logged In',['user' => $user, 'profile' => $profile, 'token' => $token]);

            return $this->uniformedResponse();
        }
        catch(Exception $e){
            $this->handleException($request, $e->getMessage());
        }
    }

    public function issueToken(Request $request, User $user)
    {
        $request->request->add([
            'grant_type' => 'password',
            'client_id' => env('PASSPORT_PASSWORD_CLIENT_ID'),
            'client_secret' => env('PASSPORT_PASSWORD_CLIENT_SECRET'),
            'username' => $user->email,
            'password' => $request->password,
            'scope' => '',
        ]);

        $tokenResponse = App::make(AccessTokenController::class)->issueToken(App::make(ServerRequestInterface::class), App::make(ResponseInterface::class));
        $tokenData = json_decode($tokenResponse->getContent(), true);
        
        return $tokenData;
    }
}