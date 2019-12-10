<?php
return [
    'pay' => [
        // APPID
        'app_id' => ' 2019110769027021',
        // 支付宝 支付成功后 主动通知商户服务器地址  注意 是post请求
        'notify_url' => 'http://192.168.0.110:9555/api/home/ali_pay_ntify',
        // 支付宝 支付成功后 回调页面 get
        'return_url' => 'http://192.168.0.110:9528/#/pay_success',
        // 公钥（注意是支付宝的公钥，不是商家应用公钥）
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAk5FVysZl1m+zFPLnTdVHD9tTsQL0JR9xGkMwpDUSnteECN7nMx3P4rKQEQgTg3GYz9ka5OvwUhf7rLGW1qsCpnfWOkmu5UZQXuzV9Exlr5HRxz9JLRJWrIhVONDqR/xniD5jYvFmvGao44xN3QcatYRNcw8mKu9g9JW0yhiIA7GKrj4Mwj2+Hy0t2jrCguc6qzBSz8jxFzpysOuYUB5k1RnTygwBX+jfiU/S0STaZC3yst/1aXZwEOknzpGnSczeBXidhqN74tH++w+CKt3DxYBESvuNKwkBDfVwgNDAuvyb1HpCSZ2133hUDZJReM21gE66vhd8KA4CLXhWY11POwIDAQAB',
        // 加密方式： **RSA2** 私钥 商家应用私钥
        'private_key' => 'MIIEowIBAAKCAQEAgzLq51gFzd23h4T88E4ZRot7lrocVwjiV4f/9m6WGTY/XqKkPYeMkILFLhIb8qczuBH4Caka0cUPvZ1uUhwbtVNwF9XBAOZU6FwuXHKY1vQM/7d15/fU6+rMUia1RnyF9l6pW7ZrrNE25SQlXN7oOKnLlCdWt4i4JfAOAyqjqeT/HNT6cdaJRZujJKsyvEfoxUHuvb1tFfBVFdBkctxPwSmaW4+/5Z6fv6+n9xBfmciW47xIOdcmQM4C9sbTroAM1dK8S1QAqS+1MngFBhQlIlYjDM5dzgisqPG/mf+st+s1saMi8INtYGZ9Q0JNciVBGKmwyE8yQt2PMtAREMVN7QIDAQABAoIBACaH2qMMn8/A8Lh1Hw+38AZeynIOwGnzKv4H6+1EtwI6g0vYuiFz4J1EOoJ4QQr3feHuLQkxR7YZiLDoPlEQ/jY3hfEKDr+j5YXEJL1zzl06mWK0T4VLrJWIhG8P/SbxRSqAzH4pyd+cJeIPxoVK1apme2pMT1Rha6uAn6zgZeZr9NHqIfMDcrSx5IunC61RJAUdanfthrwr9zJogCpiG0JaXATApk/UHGPv1ntqDlqnGcVlo+6AquBD6mxA0bR8ilwDtnMLIEhBituINsQhWvI/zrJiE5yXUfz/DvZeR7xNixnk+lQyqAIFTFcEPS/KTGJK5PxFZfyeAwKcRJJ1XWECgYEA0HeEvo5FY7SXnPCa0xXME0fDNhM4iB2poujA7wDXm/7tR6vP3skBSDejuQx0zoYunCb5AV8M2JSKJ9PkssM7Ss1tr4JhRrVuS8ErT1E8spCcbFJ0JAQC93jbIjIDXFZqu3Y94GMyxbpVYqIjgj8q/kZTtqJZGQJUGucYDAcxh2kCgYEAoR0q1X9kwKNnGTdazOXnfGXyY/LqUTqVoZXBOrKJbAxTdh5Iiz+5GQRke/fBRwj31l3NmAOaM+kDMRjPuIs1BnHQ0efZ+4vnyHnCC5W+JgB/YJbvfLJLbDcwuY7p6uowsa1XnENt8MEJZehWlnl3GYqdR2WghJ2hCqBLdNIdJeUCgYEAjewgYj6nfOawpTakoPpg8etUOhdB0GEpYPBGkTAomVonnjiZDxoXFlxSySNyzjtJtiNOOKHGNBiEKfM26oDBd/59IxSHN0VDbq1218v9n6+V3qUPzokwn9wWi6Qy54UpqrAoFgBSy4w8nya1N/HbSjsEuPyz3bPZIGud0mp/TKkCgYAF7LaohzjDRL8D1F9IbaPnlTAmsMYhGpBqEsG6UNpw2Lsw+sgcJsm9u8WeMWwwGopSnbxzvJ4tDoKJiaoJ1USr6f+N2ILRl0F8w81485a8ewQ0HxjLcxsG7bii+jhr5RTJU+CG5IvkcJTR5ItamTjh/ZDvETOn7MwBQgfEUf20aQKBgHaPL6EkyNjln/XRBAlQheFluZfiMHNm42B+5P/m/LDkTclY8qC9uycvZSBA/dikgOO0TBEioe2AC+ax7GrzudnsiI7JTLgLzOBVmuOcYk4FPXHjhp3EDVruTUCm3rRmQg4HBBYBDQ/SsAwqpuKaSVRBgwXiGUdAcyb1DcX/ocSd',
        'log' => [ // optional
            'file' => '../storage/logs/alipay.log',
            'level' => 'debug', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'single', // optional, 可选 daily.
            'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
        ],
        'http' => [
            'timeout' => 5.0,
            'connect_timeout' => 5.0,
            // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
        ],
        'mode' => 'dev', // optional,设置此参数，将进入沙箱模式
    ]
];
