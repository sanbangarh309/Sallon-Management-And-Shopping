<?php
ini_set('display_errors',1);
function currencyConverter($fromCurrency,$toCurrency,$amount) {
  $fromCurrency = urlencode($fromCurrency);
  $toCurrency = urlencode($toCurrency);
  $url = "https://www.google.com/search?q=".$fromCurrency."+to+".$toCurrency;
  $get = file_get_contents($url);
  $data = preg_split('/\D\s(.*?)\s=\s/',$get);
  $exhangeRate = (float) substr($data[1],0,7);
  $convertedAmount = $amount*$exhangeRate;
  $data = array( 'exhangeRate' => $exhangeRate, 'convertedAmount' =>$convertedAmount, 'fromCurrency' => strtoupper($fromCurrency), 'toCurrency' => strtoupper($toCurrency));
  echo json_encode( $data );
}


$amount =10;

//From Currency
$from_currency ="USD";

//To Currency
$to_currency ="INR";

$converted_currency = currencyConverter($from_currency, $to_currency, $amount);

echo $converted_currency;
