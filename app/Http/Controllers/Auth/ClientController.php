<?php
namespace App\Http\Controllers\Auth;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class ClientController extends Controller
{
    public function __construct() {
        $this->middleware('auth:client', ['except' => ['login', 'register']]);
    }

    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (! $token = auth()->guard('client')->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:clients',
            'password' => 'required|string|min:6',
            'photo' => 'required|image|mimes:jpg,png,jpeg',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $client = Client::create(array_merge(
                    $validator->validated(),
                    [
                        'password' => bcrypt($request->password),
                        'photo' => $request->file('photo')->store('clients')
                    ]
                ));
        return response()->json([
            'message' => 'client successfully registered',
            'client' => $client
        ], 201);
    }

    public function logout() {
        auth()->guard('client')->logout();
        return response()->json(['message' => 'client successfully signed out']);
    }

    public function refresh() {
       // return $this->createNewToken(auth()->refresh());
    }

    public function clientProfile() {
        return response()->json(auth()->guard('client')->user());
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
           // 'expires_in' => auth()->factory()->getTTL() * 60,
            'client' => auth()->guard('client')->user()
        ]);
    }
}
