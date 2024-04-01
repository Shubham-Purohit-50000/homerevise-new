<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Hash;
use Exception;
use Twilio\Rest\Client;
use App\Models\Activation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
   
class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'phone' => ['required', 'numeric', 'unique:users,phone'],
            'activation_key' => 'required|exists:activations,activation_key',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(), 403);
        }

        $input = $request->all();
        //return response()->json($phone_number);
        
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);

        $activation = Activation::where('activation_key', $request->activation_key)->first();
        if($activation->user_id == null){
            $activation->user_id = $user->id;
            $activation->activation_time = Carbon::now();
            $activation->expiry_date = Carbon::now()->addMonths($activation->course->duration);
            $activation->save();
        }else{
            return $this->sendError('Unauthorised.', ['error' => 'This activation key is already in use.'], 401);
        }

        $success['name'] =  $input['name'];
        return $this->sendResponse($success, 'Registration successfully.');
    }

    protected function username()
    {
        $loginField = request()->input('login_field');
        $field = filter_var($loginField, FILTER_VALIDATE_EMAIL) ? 'email' : (is_numeric($loginField) ? 'phone' : 'name');
        request()->merge([$field => $loginField]);
        return $field;
    }
   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login_field' => 'required',
            'password' => 'required',
            'device_id' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(), 403);
        }

        $loginField = $request->input('login_field');
        $user = User::where($this->username(), $loginField)->first(); // Fetch the user by the login field

        if (!$user) {
            return $this->sendError('Unauthorised.', ['error' => 'User not found.'], 401);
        }

        // Check if the user's status is 1 (active)
        if ($user->status != 1) {
            return $this->sendError('Unauthorised.', ['error' => 'Your account is deactivated.'], 401);
        }

        $credentials = [
            $this->username() => $loginField,
            'password' => $request->input('password'),
        ];
    
        if (auth()->attempt($credentials)) {
            $user = auth()->user();
    
            if (!$user->device_id) {
                $device_id = $request->input('device_id');
                $user->update(['device_id' => $device_id]);
            } elseif ($user->device_id !== $request->input('device_id')) {
                auth()->logout();
                return $this->sendError('Unauthorised.', ['error' => 'Device ID is already in use.'], 401);
            }
    
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['name'] =  $user->name;
            return $this->sendResponse($success, 'User login successfully.');
        }
    
        // Authentication failed
        return $this->sendError('Unauthorised.', ['error' => 'Invalid login credentials.'], 401);
    }


    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => [
                'nullable','email',
                Rule::unique('users', 'email')->ignore(auth()->id()),
            ],
            'phone' => [
                'nullable',
                Rule::unique('users', 'phone')->ignore(auth()->id()),
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the allowed file types and maximum size as needed.
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 403);
        }

        $user = auth()->user();
        $input = $request->all();

        // Handle profile picture upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('profile_pictures', 'public');
            // Save the image path in the user's database record
            $input['image'] = $imagePath;
        }
        if($request->has('password') and $request->password != null){
            $input['password'] = Hash::make($input['password']);
        }

        $user->update($input);

        $success['name'] =  $user->name;
        return $this->sendResponse($success, 'Profile updated successfully.');
    }

    public function logout(Request $request)
    {
        // Revoke the user's current token
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function de_register_device(Request $request)
    {
        auth()->user()->update(['device_id' => null]);

        return response()->json(['message' => 'Device deregistered successfully']);
    }

     
    
}