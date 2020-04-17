<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Http\Requests\CreateHouseKeepsRequest;
use App\Http\Resources\HouseKeepCollection;
use App\Http\Resources\HouseKeepResource;
use App\Repositories\HousekeepRepository;
use App\Repositories\OrderRepository;
use App\Services\AlipayService;
use App\Services\SmsService;
use App\Unit;
use Illuminate\Http\Request;

class HouseKeepsController extends Controller
{
    protected $houseKeepsRepository;

    public function __construct(HousekeepRepository $repository)
    {
        $this->houseKeepsRepository = $repository;
    }

    public function create(CreateHouseKeepsRequest $request,AlipayService $alipay)
    {
        $unitId = Unit::where('name',$request->address)->first();
        $unitId ? $unitId = $unitId->id : $unitId = 1;
        $houseKeeps = $this->houseKeepsRepository->create([
            'billno'=>Helpers::generateNO('HK'),
            'service_type'=>1,
            'specific_type'=>1,
            'price' =>5,
            'unit_id' => $unitId,
            'user_id' => $request->user()->id,
            'order_type'=> 0,
            'detailed_address'=>$request->detailedAddress,
            'appointment'=>$request->appointment,
        ]);
        $result = $alipay->MovePay($houseKeeps->id,5,$request->user()->ali_uid);
        return response()->json(['code'=>200,'result'=> $result,'orderId' => $houseKeeps->id]);
    }

    public function VerifyPay(Request $request)
    {
        $this->houseKeepsRepository->update(['order_status'=>1,'pay_time'=>now()],$request->id);
        SmsService::sendSMS(17798521228,['orderType'=>'保洁']);
        return response()->json(['code' => 200,'message' => '状态更新成功']);
    }

    public function index(Request $request)
    {
        $list = $this->houseKeepsRepository->where('order_status',1)->orderBy('pay_time','desc')->get() ;
        return new HouseKeepCollection($list);
    }

    public function hkList(Request $request)
    {
        $list = $this->houseKeepsRepository->where('user_id',$request->user()->id)->where('order_status',1)->with('unit')->get();
        return new HouseKeepCollection($list);
    }

}
