<?php


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