<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public $helper;
    public $model;
    public $guard ;
    public function __construct()
    {
        $this->guard = 'users';
        $this->helper = new Helper();
        $this->model = new Driver();
    }

    public function index(Request $request)
    {

            if($request->id){
                $driver = $this->model->findOrFail($request->id);
            }else{
                $driver = $this->model->get();
            }
        return $this->helper->responseJson(1,$driver);
    }

    

    public function AddRate(Request $request)
    {

        $rules =
        [
            'id' => 'required|exists:drivers,id',
            'user_rate' => 'required'
        ];

        $data = validator()->make($request->all(), $rules);

        if ($data->fails()) {

            return $this->helper->responseJson(0, $data->errors()->first());
        }

        $driver = $this->model->findOrFail($request->id);

        $driver->user_rate = $driver->user_rate + $request->user_rate;
        $driver->count_rate = $driver->count_rate + 1;
        if($driver->save()){
            return $this->helper->responseJson(1, 'تم الاضافه بنجاح',$driver->rate);
        }else{
            return $this->helper->responseJson(0, 'حدث خطا',$request->all());

        }
    }


    

    public function update(Request $request)
    {
        $update = $this->model->findOrFail($request->id);

        $rules =
            [
                'id' => 'required|exists:drivers,id',
                'name' => 'required',
                'phone' => 'required|unique:drivers,phone,'.$update->id,
                'username' => 'required|unique:drivers,username,'.$update->id,
                'password' => 'required',
                'car' => 'required',
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
