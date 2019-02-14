<?php
$curriesncies = array('kwd','egp','aed','usd','bhd','jod','omr','eur','gbp','mad','lbp');
// echo '<pre>';print_r(setting('site'));exit;
// foreach ($curriesncies as $key => $value) {
//   // code...
// }
return [

    'SAR' => [
        'name'                => 'Saudi Riyal',
        'code'                => 682,
        'precision'           => 2,
        'subunit'             => 100,
        'convert_amnt'        => 1,
        'symbol'              => 'ر.س',
        'symbol_first'        => true,
        'decimal_mark'        => '.',
        'thousands_separator' => ',',
    ],

    'KWD' => [
        'name'                => 'Kuwaiti Dinar',
        'code'                => 414,
        'precision'           => 3,
        'subunit'             => 1000,
        'convert_amnt'        => 0.081,
        'symbol'              => 'د.ك',
        'symbol_first'        => true,
        'decimal_mark'        => '.',
        'thousands_separator' => ',',
    ],

    'EGP' => [
        'name'                => 'Egyptian Pound',
        'code'                => 818,
        'precision'           => 2,
        'subunit'             => 100,
        'convert_amnt'        => 4.78,
        'symbol'              => 'ج.م',
        'symbol_first'        => true,
        'decimal_mark'        => '.',
        'thousands_separator' => ',',
    ],

    'AED' => [
        'name'                => 'UAE Dirham',
        'code'                => 784,
        'precision'           => 2,
        'subunit'             => 100,
        'convert_amnt'        => 0.98,
        'symbol'              => 'د.إ',
        'symbol_first'        => true,
        'decimal_mark'        => '.',
        'thousands_separator' => ',',
    ],

    'USD' => [
        'name'                => 'US Dollar',
        'code'                => 840,
        'precision'           => 2,
        'subunit'             => 100,
        'convert_amnt'        => 0.27,
        'symbol'              => '$',
        'symbol_first'        => true,
        'decimal_mark'        => '.',
        'thousands_separator' => ',',
    ],

    'BHD' => [
        'name'                => 'Bahraini Dinar',
        'code'                => 48,
        'precision'           => 3,
        'subunit'             => 1000,
        'convert_amnt'        => 0.1,
        'symbol'              => 'ب.د',
        'symbol_first'        => true,
        'decimal_mark'        => '.',
        'thousands_separator' => ',',
    ],

    'JOD' => [
        'name'                => 'Jordanian Dinar',
        'code'                => 400,
        'precision'           => 3,
        'subunit'             => 100,
        'convert_amnt'        => 0.19,
        'symbol'              => 'د.ا',
        'symbol_first'        => true,
        'decimal_mark'        => '.',
        'thousands_separator' => ',',
    ],

    'OMR' => [
        'name'                => 'Rial Omani',
        'code'                => 512,
        'precision'           => 3,
        'subunit'             => 1000,
        'convert_amnt'        => 0.1,
        'symbol'              => 'ر.ع.',
        'symbol_first'        => true,
        'decimal_mark'        => '.',
        'thousands_separator' => ',',
    ],

    'EUR' => [
        'name'                => 'Euro',
        'code'                => 978,
        'precision'           => 2,
        'subunit'             => 100,
        'convert_amnt'        => 0.23,
        'symbol'              => '€',
        'symbol_first'        => true,
        'decimal_mark'        => ',',
        'thousands_separator' => '.',
    ],

    'GBP' => [
        'name'                => 'Pound Sterling',
        'code'                => 826,
        'precision'           => 2,
        'subunit'             => 100,
        'convert_amnt'        => 0.21,
        'symbol'              => '£',
        'symbol_first'        => true,
        'decimal_mark'        => '.',
        'thousands_separator' => ',',
    ],

    'MAD' => [
        'name'                => 'Moroccan Dirham',
        'code'                => 504,
        'precision'           => 2,
        'subunit'             => 100,
        'convert_amnt'        => 2.55,
        'symbol'              => 'د.م.',
        'symbol_first'        => false,
        'decimal_mark'        => '.',
        'thousands_separator' => ',',
    ],

    'LBP' => [
        'name'                => 'Lebanese Pound',
        'code'                => 422,
        'precision'           => 2,
        'subunit'             => 100,
        'convert_amnt'        => 402.73,
        'symbol'              => 'ل.ل',
        'symbol_first'        => true,
        'decimal_mark'        => '.',
        'thousands_separator' => ',',
    ],
];
