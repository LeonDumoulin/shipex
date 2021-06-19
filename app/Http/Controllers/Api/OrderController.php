<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public $helper;
    public $model;
    public $guard ;
    public function __construct()
    {
        $this->guard = 'api';
        $this->helper = new Helper();
        $this->model = new Order();
    }

    public function makeOrder(Request $request)
    {

        $rules =[
            'name' => 'required',
            'from_address' => 'required',
            'to_address' => 'required',
            'user_id' => 'required|exists:users,id',
            'client_phone' => 'required',
            'client_name' => 'required',
            'size' => 'required',
            'weight' => 'required'        ];
        $data = validator()->make($request->all(), $rules);

        if ($data->fails()) {

            return $this->helper->responseJson(0, $data->errors()->first());
        }
        $record = $this->model->create($request->all());
        return $this->helper->responseJson(1, 'تم الاضافه بنجاح', $record);
    }

    public function driverAcceptOrder(Request $request)
    {
        $update = $this->model->findOrFail($request->id);

        $rules =
            [
                'id' => 'required|exists:orders,id',
                'driver_id' => 'required|exists:drivers,id',
                
            ];

        $data = validator()->make($request->all(), $rules);

        if ($data->fails()) {

            return $this->helper->responseJson(0, $data->errors()->first());
        }
        $request->merge(['state' => 1]);
        $update->update($request->all());

        return $this->helper->responseJson(1, 'تم التحديث بنجاح',$update);

    }

    public function doneOrder(Request $request)
    {
        $update = $this->model->findOrFail($request->id);

        $rules =
            [
                'id' => 'required|exists:orders,id',                
            ];

        $data = validator()->make($request->all(), $rules);

        if ($data->fails()) {

            return $this->helper->responseJson(0, $data->errors()->first());
        }
        $request->merge(['state' => 2]);
        $update->update($request->all());

        return $this->helper->responseJson(1, 'تم التحديث بنجاح',$update);

    }

   
}
