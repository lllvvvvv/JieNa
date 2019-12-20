<?php
namespace App\Services;
use AlipaySystemOauthTokenRequest;
use AopCertClient;
use App\Helpers\Helpers;
use App\Http\Middleware\EncryptCookies;
use App\Order;
use App\OrdersFlow;
use App\User;
use http\Env\Request;
use AopClient;

class AlipayService
{

    public function __construct()
    {
//        $c = new AopCertClient;
//        $appCertPath = storage_path('app/alikeys/appCertPublicKey.crt');
//        $alipayCertPath = storage_path('app/alikeys/alipayCertPublicKey_RSA2.crt');
//        $rootCertPath = storage_path('app/alikeys/alipayRootCert.crt');
//
//        $c->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
//        $c->appId = '2019110869018436';
//        $c->rsaPrivateKey = 'MIIEogIBAAKCAQEAsxkx23eknJxpxDqVmonVc3xqZOUSwR7kzvGFzg66xFaqsY7gvLRvaS3/B89HU90CwzEtsp7Y2PDeVw9j70FtjxHAkG9r7o+I7rH6PgPs4HZ3lkwqM9I3zmvQwqRkTdtMVZdGcCwwxZgNRhL84p/BPuo1NtsmVxJB8ciqCrcAHniIAF1RN+DSa7nDnUHUzupwHCVAUqNEUOJrvUL17xW845wsDhptKLyCx4vx1fBr1xfERZ/i5KxgAGXf587jEJAbyb74WP97ESEEK0rTGA2rBROH+aifP+pt1Jk9jr/XG2CYycto9kmbdDaujVyINJPcFBD+JBtZpDrmcWH9+5UNMQIDAQABAoIBAHNSzd/b8otFzHUmpB6WknOCsZ+2QZVJJ1x5+QbuRSUYCWG79sqMBRFdJNMKjYtazOSTTjGuR0OqFgFJT7hIERwG+lAG7yD2G95XjCNSs7z1i5uj/6jOvFCW7B3pbQ0VINocRqcETYNunV157IyhAfZZKVrhUpbOktf1tkif/duf+mvCIoKzureAhrM4tbylBvUPMXEiAmVFKmafLZfmfufySdyXPTepnQ6Vl6c4bNhSYiPPojYD6ZtxxHMwe7nqnEMd7ga/tHKIih7baZvf4SZ5XNbLPKNliD4+chfR8GbPFSMSfK6a6HumT8PZbmqZQwO04oT8EZy287ZspkdvAoECgYEA+D8miN3GIneW9pOOMchNgc5+bxeI/Oc5wHeBplLCcJo/J1P8mfX909ulm3wO8+f6BQgzlOkXcPeXHTTqLtzpEQh2wij1kwiHxLyoCn1LMqIxMSfoDxL+hMw5RNE12sGwSwyOqbB8RMZMnRdxr+h6AwACIwxipvkqUGDglHP/6mUCgYEAuLEr0z33kRGGo8OUCZZknQBm3OBUQHopeMcIF4O+dhS/q64GkVk8pAA31MsN2Xn3rxcSriLznp9tQ0Z+gS5r2HE90bNUCsSgD/kDHUuMYF+eCUQji5LGRt5Of542UTnndZHmzPOkVelfM2tIJ+lwlkUTIyZKDmL+PSwFMYATpN0CgYA1AAaSIgczRKUOb+Jj7ofdtuB3h2bP1+4UkW80u2aX89aX5u5/UG2o1bQ9xfbLTDOs71tT55ABplq0+Z8l8jQwVsZEHcqQYbna3wxhcO6lGMu32vVIgp2kbJWtg50j30ZsP3oV8IAXjt68i0zSaafjDBVotjQ5weiu0IAiMcHpoQKBgDFOWzzR1j3MR/2AQQ0uMT4Y1V0yJkvwURIFUIu2iCpN772NgANdp2rBBCay7seYYB9GMZ57hj5aoXjMYQlrsy7dTHunPFCDnZemCsbPXHJ+FFq07ihczsIspxg6zJVyt/ATO2KLyNuGqfu4MFM3Zu83EZzYo/yjQOxjzB8i6huhAoGAW/WlApRLUiyDFJqTheRPl4mQsPmoKmCM8auB6i29TgQAGlqAylBU83pYzYC2whXpLWpErqobh8PZIXTo0g2E16ebBE/ITFn8D4MmDsqucxY2pwacYmupVzVyrbXSWeMO36oVaj4BpTkauclT6ri5n3kwGZQt12IdWqY0oqOD3+c=';
//        $c->format = "json";
//        $c->charset= "UTF-8";
//        $c->signType= "RSA2";
//
//        $c->alipayrsaPublicKey = $c->getPublicKey($alipayCertPath);
//        $c->isCheckAlipayPublicCert = true;
//        $c->appCertSN = $c->getCertSN($appCertPath);
//        $c->alipayRootCertSN = $c->getRootCertSN($rootCertPath);
//        $this->c = $c;
        $c = new AopClient;
        $c->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $c->appId = "2019110869027459";
        $c->rsaPrivateKey = 'MIIEpQIBAAKCAQEAqGBAutp6RXjCbIatpDhGA0ycoc/Mc4PTe+wV3Khg5AHWeOVonE1xG9A0bRvfVQJ+LD3DjKzETQAt2qHI1VrUVyUHZldploAmziEP7sah9XWyKdVItbFHnYPvg2c9kUcD2rJsKErKHxA+k6dnTNNQ13iFLZ97OZ0Xy2fsiCvmPtMuYCyJ4w39w8wqA4ZR7XrVPgCx93P0NHQtxHPpLPHe5HDrMsWP4W0GhGD2hX1wvP2l/sJpK7F0dkH/gb3S6+YhIjC4x0cpECuWjNXz8toV5HwEjWU8pr6UHuUbQxq5Cbhyc9c91I9biT9weZp7L3sDD0ATYhdurkckY0/2TcI7ewIDAQABAoIBABYGiVEoFUiTNHO35m0OA3KZCgBMy4Ts4LRcPLvhttL8vo6QC/AuXTZzNPh05fEb419sMPFtBDzCYj0wXrIyMIa5zE8B7kkGuIzMXGYyy6rtW4IHaXyDFUgwoxtAXRhs/r8UCfv3VJtdp6HvCx9MoU2ecWV3cEooF52/GXzyqSrchJTvX8JyH9IMbNRfGS6qz3gqdePySx3hTi26ltLrLp/lTfHm5dEmI6ARf3sCc16+JINYQw9hycgB7ObBwezSL8Yo8lXIAvpAzp+SXEY8mttDML417ch45wjTNCDCkMO06iTFZcFrSS3li0GNs2n/PQ6z2PmyyIjM/uLs9F2KQ4ECgYEA5+gkE4vQLktihQ/aeUkQOoqwq0qB0zVpXbSxrXj30geFFUyb7drauy7l4yRXQcGSX0Y+aZHuO+QFo8AJv97jHiKAKPsV5T7D/p/88nB9Ot0XwaSn59JISL9McH5PoFz/SrqpdgrbSb8oIbuT5R5zk8b58NtiHoiIePkDUodKS8ECgYEAud5tq8SRFdkYi4QkUdNysU5aoAfO1MFlaOuDoK2tBaJdlOKVf/uGLWgj7KJl3blZzMDLPDMatZssS7sN91KGeQeceu7KjLOFIT7Y1NGtel9FkFtqlhCNXKvTKqKr0Oj+I5UAMk7ptlGy8Ib+KpNDmnafEjSoKs2yvAU5VznsRjsCgYEAn0Rl6mqeCf1J5xqRL7THCX2QgcNE0Bohh/J+CCZJBJyXgJ5BhKRGjkFSLqIZrxZTAU3LtVM8qzMZ0HEmAPkBQAPvwrVWPz/Q+UFFRfeNeey30QzVJ7faXuPKioOlCfx0dA0oLuKb4dT/qdMaakN0muwPYaQ5icC5AWP7LSWvF8ECgYEAmbxnm/6PPrH9glB/NgseP5eej+VFZ2a7eWfrzLg6d4GPw/kSTPR/TAlqRW0hsp5/r0L8F0Px4KqDnfQPHjVtMqhf/rKKaIyIHjJ8aLMxuRkaUce7RnpUYoVguVApqzc8FbRFoFbzKrEyv434psB910IsWoOhYR4Yqznq7sHjM0cCgYEAsK1e2DoHFiCQfYvQvkNLQbR7LsyJQB2bN6OtCo2Oka09CPEwem3vZ944d7So0pX8L6FSzmt8hh5/GLES5Ez8gvrUTHpuOMlg3tudQO8Wx+trU7EQodzPMJFUtWG/7px+CGz6POuBuskT0/SQasvZbBIZ44GkyOZ1vADwN8D/+UY=';
        $c->format = "json";
        $c->charset= "UTF-8";
        $c->signType= "RSA2";
        $c->notify_url = "https://www.go2020.cn/api/notify";
        $c->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAk5FVysZl1m+zFPLnTdVHD9tTsQL0JR9xGkMwpDUSnteECN7nMx3P4rKQEQgTg3GYz9ka5OvwUhf7rLGW1qsCpnfWOkmu5UZQXuzV9Exlr5HRxz9JLRJWrIhVONDqR/xniD5jYvFmvGao44xN3QcatYRNcw8mKu9g9JW0yhiIA7GKrj4Mwj2+Hy0t2jrCguc6qzBSz8jxFzpysOuYUB5k1RnTygwBX+jfiU/S0STaZC3yst/1aXZwEOknzpGnSczeBXidhqN74tH++w+CKt3DxYBESvuNKwkBDfVwgNDAuvyb1HpCSZ2133hUDZJReM21gE66vhd8KA4CLXhWY11POwIDAQAB';
        $this->c = $c;
    }

    public function aliUserInfo($code,$phone,$uid) //查看用户token
    {
        $request = new AlipaySystemOauthTokenRequest();
        $request->setGrantType("authorization_code");
        $request->setCode($code);
        $result = $this->c->execute( $request);
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        if (User::where('alipay_token','=',$result->$responseNode->access_token)->exists())
        {
            return $result;
        }
        $user = User::where('phone','=',$phone)->update(['alipay_token' =>$result->$responseNode->access_token,'ali_uid'=>$result->alipay_system_oauth_token_response->user_id]);
        return $result;
    }

    public function decryptData( $encryptedData)
    {
        $key = 'yFNAgaC7yE8AZhE/jNopkw=='; //小程序应用AES密钥
        $aeskey = base64_decode($key);
        $iv = 0;
        $aesIv = base64_decode($iv);
        $aesCipher = base64_decode($encryptedData);
        $result = openssl_decrypt($aesCipher,"AES-128-CBC",$aeskey,1,$aesIv);
        return $result;
    }

    public function freeze($order)
    {
        $unit = Order::where('billno',$order)->first()->Unit()->first();
        $boxes = Helpers::getBoxes($order);
        $price = PriceService::getBoxDeposit($boxes);
        $request = new \AlipayFundAuthOrderAppFreezeRequest();
        $flow_id = OrdersFlow::create(['flow_id'=>Helpers::generateFlowNo(),'billno'=>$order,'type'=>1,'price'=>$price]); //生成流水号
        $request->setNotifyUrl("https://www.go2020.cn/api/notify");
        $t = "{" .
            "\"out_order_no\": \"$order\"," .
            "\"out_request_no\":\"$flow_id->flow_id\"," .
            "\"order_title\":\"预授权冻结\"," .
            "\"amount\":$price," .
            "\"product_code\":\"PRE_AUTH_ONLINE\"," .
            "\"payee_logon_id\":\"c17798521228@126.com\"," .
            "\"extra_param\":\"{\\\"category\\\":\\\"RENT_LUGGAGE\\\",\\\"outStoreCode\\\":\\\"unit.$unit->id\\\",\\\"outStoreAlias\\\":\\\"$unit->address\\\"}\"," . //charge001
//            "\"pay_timeout\":\"2d\"" .
//            "\"scene_code\":\"OVERSEAS_ONLINE_AUTH_COMMON_SCENE\"" .{\\"payChannelType\\":\\"CREDITZHIMA\\"},
            "\"enable_pay_channels\":\"[{\\\"payChannelType\\\":\\\"MONEY_FUND\\\"},{\\\"payChannelType\\\":\\\"PCREDIT_PAY\\\"}]\"" .
//            "\"identity_params\":\"{\\\"identity_hash\\\":\\\"ABCDEFDxxxxxx\\\",\\\"alipay_user_id\\\":\\\"2088xxx\\\"}\"" .
            "}";
        $request->setBizContent($t);
        $result = $this->c->sdkExecute( $request);
        return $result;
    }

    public function pay($billno,$notify,$price)
    {
        $flow = OrdersFlow::create(['flow_id'=>Helpers::generateFlowNo(),'billno'=>$billno,'type'=>2,'price'=>$price]);
        $order = Order::where('billno',$billno);
        $order->update(['price'=>$price]);
        $unit = $order->first()->Unit()->first();
        if ($price>$notify->amount)
        {
            $price = $notify->amount;
        }
        $request = new \AlipayTradePayRequest();
        $request->setNotifyUrl("https://www.go2020.cn/api/notify");
        $request->setBizContent("{" .
            "\"out_trade_no\":\"$flow->flow_id\"," .
            "\"product_code\":\"PRE_AUTH_ONLINE\",".
            "\"auth_no\":\"$notify->auth_no\"," .
            "\"subject\":\"预授权转支付测试\"," .
            "\"buyer_id\":\"$notify->payer_user_id\"," .
            "\"seller_id\":\"2088631770405887\"," .
            "\"total_amount\":\"$price\",".
            "\"store_id\":\"unit.$unit->id\",".
            "\"auth_confirm_mode\":\"COMPLETE\"".
            "  }");
        $result = $this->c->execute($request);
        return $result;
    }

    public function unfreeze($billno,$notify)
    {
        $request = new \AlipayFundAuthOrderUnfreezeRequest();
        $flow = OrdersFlow::create(['flow_id'=>Helpers::generateFlowNo(),'billno'=>$billno,'type'=>3,'price'=>$notify->amount]);
        $request->setNotifyUrl("https://www.go2020.cn/api/notify");
        $request->setBizContent(
            "{" .
            "\"auth_no\":\"$notify->auth_no\"," .
            "\"out_request_no\":\"$flow->flow_id\"," .
            "\"amount\":$notify->amount," .
            "\"remark\":\"0元免费\"," .
            "\"extra_param\":\"{\\\"unfreezeBizInfo\\\": \\\"{\\\\\\\"bizComplete\\\\\\\":\\\\\\\"true\\\\\\\"}\\\"}\"" .
            "  }"
        );
        $result = $this->c->execute( $request);
        return $result;

    }

    public function buyBox($billno,$price,$ali_uid)
    {
        $request = new \AlipayTradeCreateRequest();
        $flow = OrdersFlow::create(['flow_id'=>Helpers::generateFlowNo(),'billno'=>$billno,'type'=>4,'price'=>$price]);
        $request->setNotifyUrl("https://www.go2020.cn/api/notify");
        $s="{" .
            "\"out_trade_no\":\"$flow->flow_id\"," .
            "\"total_amount\":$price," .
            "\"subject\":\"鲸亿盒子\"," .
            "\"buyer_id\":\"$ali_uid\"" .
            "  }";
        $request->setBizContent($s);
        $result = $this->c->execute( $request);
        return $result;

    }

}
