<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public $helper;
    public $model;
    public $model2;
    public $model3;

    public function __construct()
    {
        $this->helper = new Helper();
        $this->model = new User();
        $this->model2 = new Driver();
    }

    public function register(Request $request)
    {

        $rules =
            [
                'name' => 'required',
                'phone' => 'required|unique:users',
                'username' => 'required|unique:users',
                'password' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg|max:2048'

            ];

        $data = validator()->make($request->all(), $rules);

        if ($data->fails()) {

            return $this->helper->responseJson(0, $data->errors()->first());
        }
        $request->merge(['password' => bcrypt($request->password)]);
        $record = $this->model->create($request->all());
    

        $token = $this->model->createToken('android')->accessToken;

        if ($request->hasFile('image')) {
            if ($request->file('image')->getSize() < (5 * 1024 * 1024)) {
                $filename = hash_hmac('sha256', hash_hmac('sha256', preg_replace('/\\.[^.\\s]{3,4}$/', '', $request->image), false), false);
                $file_name = $request->image->getClientOriginalName();
                $extn = $request->image->getClientOriginalExtension();
                $mime = $request->image->getClientMimeType();
                $final = $filename . '.' . $extn;
                $file = $request->image->storeAs('', $final, 'public');
                $record->image = $file;
                $record->save();
            }
        }

        return $this->helper->responseJson(1, 'تم الاضافه بنجاح', ['token' => $token, 'user' => $record]);
    }

    public function registerDriver(Request $request)
    {

        $rules =
            [
                'name' => 'required',
                'phone' => 'required|unique:drivers',
                'username' => 'required|unique:drivers',
                'password' => 'required',
                'car' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg|max:2048'

            ];

        $data = validator()->make($request->all(), $rules);

        if ($data->fails()) {

            return $this->helper->responseJson(0, $data->errors()->first());
        }
        $request->merge(['password' => bcrypt($request->password)]);
        $record = $this->model2->create($request->all());
        

        $token = $this->model2->createToken($this->model2)->accessToken;

        if ($request->hasFile('image')) {
            if ($request->file('image')->getSize() < (5 * 1024 * 1024)) {
                $filename = hash_hmac('sha256', hash_hmac('sha256', preg_replace('/\\.[^.\\s]{3,4}$/', '', $request->image), false), false);
                $file_name = $request->image->getClientOriginalName();
                $extn = $request->image->getClientOriginalExtension();
                $mime = $request->image->getClientMimeType();
                $final = $filename . '.' . $extn;
                $file = $request->image->storeAs('', $final, 'public');
                $record->image = $file;
                $record->save();
            }
        }


        return $this->helper->responseJson(1, 'تم الاضافه بنجاح', ['token' => $token, 'user' => $record]);
    }

    public function login(Request $request)
    {
        $rules =
            [
                'username' => 'required',
                'password' => 'required',

            ];


        $data = validator()->make($request->all(), $rules);

        if ($data->fails()) {

            return $this->helper->responseJson(0, $data->errors()->first());
        }


        $user = $this->model->where(['username' => $request->username])->first();

        //check if user exists
        if ($user) {

            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('android')->accessToken;

                return $this->helper->responseJson(1, 'تم تسجيل الدخول بنجاح', ['token' => $token, 'user' => $user]);
            } else {

                return $this->helper->responseJson(0, 'كلمة المرور غير صحيحة');
            }
        } else {
            return $this->helper->responseJson(0, 'البريد  الذي أدخلته غير صحيح');
        }

        // send pin code to confirm phone
    }


   
    public function loginDriver(Request $request)
    {
        $rules =
            [
                'username' => 'required',
                'password' => 'required',

            ];


        $data = validator()->make($request->all(), $rules);

        if ($data->fails()) {

            return $this->helper->responseJson(0, $data->errors()->first());
        }


        $user = $this->model2->where(['username' => $request->username])->first();

        //check if user exists
        if ($user) {

            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('android')->accessToken;

                return $this->helper->responseJson(1, 'تم تسجيل الدخول بنجاح', ['token' => $token, 'user' => $user]);
            } else {

                return $this->helper->responseJson(0, 'كلمة المرور غير صحيحة');
            }
        } else {
            return $this->helper->responseJson(0, 'البريد  الذي أدخلته غير صحيح');
        }

        // send pin code to confirm phone
    }
}
