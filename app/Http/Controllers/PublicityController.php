<?php

namespace App\Http\Controllers;

use App\Repositories\PublicityRepository;
use App\Services\AlipayService;
use Illuminate\Http\Request;

class PublicityController extends Controller
{
    protected $repository;

    public function __construct(PublicityRepository $repository)
    {
        $this->repository = $repository;
    }

    public function publicityQrCode(Request $request)
    {
        $publicity = $this->repository->create($request->all());
        $qrcode = new AlipayService();
        $result = $qrcode->createQcCode($publicity->id,$publicity->phone,$publicity->name);
        $ulr = $this->repository->update(['url'=>$result->alipay_open_app_qrcode_create_response->qr_code_url],$publicity->id);
        return response()->json(['code' => 200,'result'=>$result]);
    }

    public function test(Request $request)
    {
        $admin = $this->repository->create($request->all());
        return response()->json([$admin->id]);
    }
}
