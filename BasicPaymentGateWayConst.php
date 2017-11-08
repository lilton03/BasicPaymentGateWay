<?php


class BasicPaymentGateWayConst
{
const chargeApiRoute='https://api.stripe.com/v1/charges';
const chargeTransactionLog= 'ChargeTransactionLog.json';

    /**
     * @return string
     * get the stripe api route url
     */
    public static function getChargePaymentApiRoute(){
    return self::chargeApiRoute;
}

    /**
     *@return string
     * get the path to the charge transaction log
     */
    public static function getChargeTransactionLogPath(){
        return self::chargeTransactionLog;
    }

}