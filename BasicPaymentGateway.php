<?php

/**
 * RESTful Code challenge. Create a simple payment gateway class that implements this
 * interface. The only thing we're looking for here is your ability to comprehend API
 * documentation and the quality of the resulting code. The only API method you need
 * to make is for a plain-jane charge. No preauth, postauth, refunds, void, etc. need
 * to be implemented. Just keep it simple and to the point.
 * ---
 * 1. You can choose between the following gateways:
 *     a) Stripe      - https://stripe.com/docs/api#create_charge
 *        Secret Key  - sk_test_lBzwJ4lQzQvEPZwgl3s59Mal
 *
 *     b) Authorize.Net       - http://developer.authorize.net/api/reference/#payment-transactions-charge-a-credit-card
 *        API Login ID        - 925hDnUuTCZ
 *        API Transaction Key - 848V7x2Aq4BgFn9F 
 * ---
 * 2. You cannot use SDKs (this is to test your ability to comprehend API documentation).
 * ---
 * 3. You cannot use third party libraries. Everything must be from scratch. You can use
 *    cURL and other native PHP libraries as needed.
 * ---
 * 4. Create a simple "test script" that will do a test charge when ran, like so:
 *
 * $gw = new FooBarGateway('sk_test_lBzwJ4lQzQvEPZwgl3s59Mal');
 * $gw->setFirstName('Bob')
 *    ->setLastName('Smith')
 *    ->setAddress1('123 Test Street')
 *    ->setAddress2('Suite #4')
 *    ->setCity('Morristown')
 *    ->setProvince('TN')
 *    ->setPostal('37814')
 *    ->setCountry('US')
 *    ->setCardNumber('4007000000027)
 *    ->setExpirationDate('10', '2019')
 *    ->setCvv('123');
 * 
 * if ($gw->charge('49.99', 'USD')) {
 *     echo "Charge successful! Transaction ID: " . $gw->getTransactionId(); 
 * } else {
 *	   echo "Charge failed. Errors: " . print_r($gw->getErrors(), TRUE); 
 * }
 * ---
 */
interface BasicPaymentGateway
{
	/**
	 * @param   string  First name on card.
	 * @return  object  Chainable instance of self.
	 */
	public function setFirstName($name);
	
	/**
	 * @param   string  Last name on card.
	 * @return  object  Chainable instance of self.
	 */
	public function setLastName($name);

	/**
	 * @param   string  Billing address, line 1.
	 * @return  object  Chainable instance of self.
	 */	
	public function setAddress1($address);
	
	/**
	 * @param   string  Billing address, line 2.
	 * @return  object  Chainable instance of self.
	 */
	public function setAddress2($address);
	
	/**
	 * @param   string  Billing city.
	 * @return  object  Chainable instance of self.
	 */
	public function setCity($city);
	
	/**
	 * @param   string  Billing state/province.
	 * @return  object  Chainable instance of self.
	 */
	public function setProvince($province);

	/**
	 * @param   string  Billing zip/postal code.
	 * @return  object  Chainable instance of self.
	 */	
	public function setPostal($postal);
	
	/**
	 * @param   string  Billing country.
	 * @return  object  Chainable instance of self.
	 */
	public function setCountry($country);
	
	/**
	 * @param   string  Card number.
	 * @return  object  Chainable instance of self.
	 */
	public function setCardNumber($number);
	
	/**
	 * @param   string  Card expiration month in MM format.
	 * @param   string  Card expiration year in YYYY format.
	 * @return  object  Chainable instance of self.
	 */
	public function setExpirationDate($month, $year);
	
	/**
	 * @param   string  Card security code (CVV, CVV2, etc.).
	 * @return  object  Chainable instance of self.
	 */
	public function setCvv($cvv);
	
	/**
	 * @param   string  '0.00' format.
	 * @param   string  [optional] Currency. Default = USD.
	 * @return  bool    TRUE if charge successful, FALSE otherwise.
	 */
	public function charge($amount, $currency = 'USD');
	
	/**
	 * @return  array  Empty if no errors found.
	 */
	public function getErrors();
	
	/**
	 * @return  string  Transaction ID of the last successful API operation.
	 */
	public function getTransactionId();
}