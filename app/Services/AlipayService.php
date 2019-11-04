<?php
namespace App\Services;
use AlipaySystemOauthTokenRequest;
use AopCertClient;

class AlipayService
{
        public function __construct()
        {
            $c = new AopCertClient();
            $c->gatewayUrl = "https://openapi.alipay.com/gateway.do";
            $c->appId = config('alipay.appid');
            $c->rsaPrivateKey = config('alipay.rsaPrivateKey');
            $c->alipayrsaPublicKey = config('alipay.rsaPublicKey');
            $c->format = 'json';
            $c->charset = 'UTF-8';
            $c->signType = 'RSA2';
        }

        public function getUserData()
        {
            $request = new AlipaySystemOauthTokenRequest();
            $request->bizContent = "";
        }
}
