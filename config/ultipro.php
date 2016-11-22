<?php

return [
    'base_url' => 'https://service4.ultipro.com/services/EmployeeNewHire',

    'newHire_wsdl'=>'https://service4.ultipro.com/services/EmployeeNewHire?wsdl',
    'login_wsdl'=>'https://service4.ultipro.com/services/LoginService?wsdl',
    'login_header'=>[
        "ClientAccessKey"=>'EOFVE',
        "Password"=>'Peachy05!',
        "UserAccessKey"=>'BF1CID0000K0',
        "UserName"=>'PSTC999999'
    ],
     'hotel_wsdl'    => 'http://demo-hotelws.touricoholidays.com/HotelFlow.svc?WSDL',
    'hotel_header'  => ['LoginName'=>'Fir110','Password'=>'111111','Culture'=>'en_US','Version'=>'7.123'],
];