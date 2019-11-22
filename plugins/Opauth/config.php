<?php
// 各プロバイダ毎のAPIキー
$config = [
    'security_salt' => '2sBz1U5epdrtub6DQH26MfzhXAehaf1H55SRwTBu', // レスポンスのシグネチャ生成に使うソルト値(ランダム文字列)
    'path' => '/auth/',
    'callback_url' => '/auth/complete', // Opauthのソーシャルログイン処理完了後にリダイレクトするURL
    'Strategy' => [
        'Facebook' => [
            'app_id' => '437678570213282',
            'app_secret' => '7e427dbc0ae27fd721d0f8cdcf795589',
            'scope' => 'email',
            'fields' => 'email,name'
        ]
    ]
];
