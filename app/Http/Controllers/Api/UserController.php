<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public $helper;
    public $model;
    public $guard ;
    public function __construct()
    {
        $this->guard = 'api';
        $this->helper = new Helper();
        $this->model = new User();
    }


    public function update(Request $request)
    {
        $update = $this->model->findOrFail($request->id);

        $rules =
            [
                'id' => 'required|exists:users,id',
                'name' => 'required',
                'phone' => 'required|unique:users,phone,'.$update->id,
                'username' => 'required|unique:users,username,'.$update->id,
                'password' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg|max:2048'
            ];

        $data = validator()->make($request->all(), $rules);

        if ($data->fails()) {

            return $this->helper->responseJson(0, $data->errors()->first());
        }

        if ($request->hasFile('image')) {
            $filename = public_path('storage/'.$update->image);

            if (file_exists($filename)) {
               unlink($filename);
            }
            $filename = hash_hmac('sha256', hash_hmac('sha256', preg_replace('/\\.[^.\\s]{3,4}$/', '', $request->image), false), false);
            $file_name = $request->image->getClientOriginalName();
            $extn = $request->image->getClientOriginalExtension();
            $mime = $request->image->getClientMimeType();
            $final = $filename . '.' . $extn;
            $file = $request->image->storeAs('', $final, 'public');
            $update->image = $file;


            $update->save();
        }
        $update->update($request->except('image'));

        return $this->helper->responseJson(1, 'تم التحديث بنجاح',$update);

    }

   
}
