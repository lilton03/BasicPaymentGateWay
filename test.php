<?php

require_once ('FooBarGateWay.php');
require_once ('BasicPaymentGateWayConst.php');
$gw = new FooBarGateway('sk_test_lBzwJ4lQzQvEPZwgl3s59Mal');

 $gw->setFirstName('Bob')->setLastName('Smith')->setAddress1('123 Test Street')
    ->setAddress2('Suite #4')
    ->setCity('Morristown')->setProvince('TN')
    ->setPostal('37814')
    ->setCountry('US')
    ->setCardNumber('4242424242424242')
    ->setExpirationDate('10', '2019')
     ->setCvv('123');


    if ($gw->charge('500', 'USD')) {
      echo "Charge successful! Transaction ID: " . $gw->getTransactionId();
     }
     else
     echo "Charge failed. Errors: " . print_r($gw->getErrors(), TRUE);


    /*additional tests to show transaction logs*/


    echo nl2br("\n\n");
    $transactionLog = $gw->getTransactionLog();
    for($i=$transactionLog['transactionCtr']-1;$i>=0;$i--){
        printf("Transaction log: %d",$i+1);
        foreach ($transactionLog[$i] as $key => $value) {
            echo nl2br("\n");
            if($key=='errors') {
                if(isset($value['message']))
                    echo $key . ': ' . $value['message'];
                else
                    echo $key . ': ' . 'no errors';
            }
            else if($value===false)
                echo $key.': '. 0;
            else
              echo $key.': '.$value;
            echo nl2br("\n");
        }
        echo nl2br("\n");

    }

?>