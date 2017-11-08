<?php
require_once ('BasicPaymentGateway.php');
require_once ('BasicPaymentGateWayConst.php');

class FooBarGateWay implements BasicPaymentGateway
{
    protected $firstName;
    protected $lastName;
    protected $addressOne;
    protected $addressTwo;
    protected $city;
    protected $province;
    protected $postal;
    protected $country;
    protected $cardNumber;
    protected $expirationMonth;
    protected $expirationYear;
    protected $cvv;
    protected $secretKey;
    protected $errors;
    protected $transactionIds;
    protected $transactionLog;

    /**
     * FooBarGateWay constructor.
     *
     * @param string $secretKey
     */
    public function __construct($secretKey='')
    {
        $this->secretKey=$secretKey;
        $this->errors=[];
        $this->transactionIds;
        $this->transactionLog=['transactionCtr'=>0];
        $chargeTransactionLogPath=BasicPaymentGateWayConst::getChargeTransactionLogPath();
        /*retrieve latest transactions, if there are any*/
        if(file_exists($chargeTransactionLogPath)){
            $this->transactionLog = json_decode(file_get_contents($chargeTransactionLogPath),true);
              $transactionCtr=$this->transactionLog['transactionCtr']<1?0:$this->transactionLog['transactionCtr']-1;
              $this->transactionIds=$this->transactionLog[$transactionCtr]['transaction_id'];
              $this->errors=$this->transactionLog[$transactionCtr]['errors'];
        }
    }

    /**
     * @param   string  First name on card.
     * @return  object  Chainable instance of self.
     */
    public function setFirstName($name)
    {
        $this->firstName=$name;
        return $this;
    }

    /**
     * @param   string  Last name on card.
     * @return  object  Chainable instance of self.
     */
    public function setLastName($name)
    {
        $this->lastName=$name;
        return $this;
    }

    /**
     * @param   string  Billing address, line 1.
     * @return  object  Chainable instance of self.
     */
    public function setAddress1($address)
    {
        $this->addressOne=$address;
        return $this;
    }

    /**
     * @param   string  Billing address, line 2.
     * @return  object  Chainable instance of self.
     */
    public function setAddress2($address)
    {
        $this->addressTwo=$address;
        return $this;
    }

    /**
     * @param   string  Billing city.
     * @return  object  Chainable instance of self.
     */
    public function setCity($city)
    {
        $this->city=$city;
        return $this;
    }

    /**
     * @param   string  Billing state/province.
     * @return  object  Chainable instance of self.
     */
    public function setProvince($province)
    {
        $this->province=$province;
        return $this;
    }

    /**
     * @param   string  Billing zip/postal code.
     * @return  object  Chainable instance of self.
     */
    public function setPostal($postal)
    {
        $this->postal=$postal;
        return $this;
    }

    /**
     * @param   string  Billing country.
     * @return  object  Chainable instance of self.
     */
    public function setCountry($country)
    {
        $this->country=$country;
        return $this;
    }

    /**
     * @param   string  Card number.
     * @return  object  Chainable instance of self.
     */
    public function setCardNumber($number)
    {
        $this->cardNumber=$number;
        return $this;
    }

    /**
     * @param   string  Card expiration month in MM format.
     * @param   string  Card expiration year in YYYY format.
     * @return  object  Chainable instance of self.
     */
    public function setExpirationDate($month, $year)
    {
        $this->expirationMonth=$month;
        $this->expirationYear=$year;
        return $this;

    }

    /**
     * @param   string  Card security code (CVV, CVV2, etc.).
     * @return  object  Chainable instance of self.
     */
    public function setCvv($cvv)
    {
       $this->cvv=$cvv;
        return $this;
    }

    /**
     * @param   string '0.00' format.
     * @param   string [optional] Currency. Default = USD.
     * @return  bool    TRUE if charge successful, FALSE otherwise.
     */
    public function charge($amount, $currency = 'USD')
    {
        $post=$this->setPostData($amount,$currency);
        $response=$this->postChargeToApi($post);
        $this->updateTransactionLog($response);
        return empty($this->errors);

    }

    /**
     * @return  array  Empty if no errors found.
     * gets the Latest errors for the latest transaction, returns [] if there where no errors on the latest transaction
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return  string  Transaction ID of the last successful API operation.
     * gets Latest Transaction Id, returns 'NA' if latest transaction failed.
     */
    public function getTransactionId()
    {
        return $this->transactionIds;
    }


    /**
     * @param $amount
     * @param $currency
     * @return string
     * Formats and sets the required post data to be sent through the Curl Post into the API
     */
    protected function setPostData($amount, $currency){
      $data=['amount'=>$amount,
            'currency'=>$currency,
            'source[exp_month]'=>$this->expirationMonth,
            'source[exp_year]'=>$this->expirationYear,
            'source[number]'=>$this->cardNumber,
            'source[object]'=>'Card',
            'source[cvc]'=>$this->cvv,
            'source[address_country]'=>$this->country,
            'source[address_city]'=>$this->city,
            'source[address_state]'=>$this->province,
            'source[address_zip]'=>$this->postal,
            'source[address_line1]'=>$this->addressOne,
            'source[address_line2]'=>$this->addressTwo,
            'source[name]'=>$this->firstName.' '.$this->lastName];
      $postData='';
        foreach ($data as $key => $value)
            $postData.='&'.$key.'='.$value;
        return $postData;
    }

    /**
     * @param String $post
     * @return mixed
     * Perform Post operation to the Charge Api
     */
    protected function postChargeToApi($post){
        $authorization ='Authorization: Bearer '.$this->secretKey;
        $curl = curl_init();
        curl_setopt_array($curl,[
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL=>BasicPaymentGateWayConst::getChargePaymentApiRoute(),
            CURLOPT_HTTPHEADER=>['Content-Type: application/x-www-form-urlencoded',$authorization],
            CURLOPT_POST=>1,
            CURLOPT_POSTFIELDS=>$post
        ]);
        $response=json_decode(curl_exec($curl),true);
        curl_close($curl);
        return $response;
    }

    /**
     * @param array $data
     * Updates the ChargeTransactionLogfile with the latest charge transaction details
     */
    protected function updateTransactionLog(array $data){
        $transactionCtr=$this->transactionLog['transactionCtr'];
        $error=isset($data['error']);
        $this->transactionLog[$transactionCtr]=[
            'card_number'=>$this->cardNumber,
            'date'=>(new\DateTime('now'))->format('Y-m-d H:i:s'),
            'success'=>!$error,
            'errors'=>$error?$data['error']:[],
            'transaction_id'=>$error?$data['error']['charge']:$data['id']
            ];
        $this->transactionLog['transactionCtr']+=1;
        $this->errors=$this->transactionLog[$transactionCtr]['errors'];
        $this->transactionIds=$this->transactionLog[$transactionCtr]['transaction_id'];
        file_put_contents(BasicPaymentGateWayConst::getChargeTransactionLogPath(),json_encode($this->transactionLog));
    }

    /**
     * @return mixed
     * show all charge Transactions
     */
    public function getTransactionLog(){
        return $this->transactionLog;
    }
}

