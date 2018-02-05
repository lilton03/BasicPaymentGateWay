<?php


Trait InputValidatorTrait
{

    /**
     * @param $string
     * @param $min
     * @param $max
     * @param $name
     */
    protected function checkStringOnly($string, $min, $max, $name){
    try{
        $this->checkIfExists($string,$name);
        $this->checkStringLength($string,$min,$max,$name);
        if(!ctype_alpha($string))
            throw new Exception($name. ' must not contain numbers and special characters');
    }
    catch (Exception $e){
        echo '<br> Message:'. $e->getMessage().'<br>';
    }
}

    /**
     * @param $data
     * @param $min
     * @param $max
     * @param $name
     */
    protected function checkStringLength($data, $min, $max, $name)
{
    try {
        if (strlen($data) < $min || strlen($data) > $max)
            throw new Exception($name . ' length cannot be less than ' . $min . ' or greater than ' . $max);
    }
    catch (Exception $e){
        echo '<br> Message :'. $e->getMessage().'<br>';
    }
}


    /**
     * @param $number
     * @param $min
     * @param $max
     * @param $name
     */
    protected function checkIfStringNumberOny($number, $min, $max, $name){
    try {
        $this->checkIfExists($number, $name);
        if (!ctype_digit($number))
            throw new Exception($name . ' cannot contain letters or special characters');
        else
            $this->checkStringLength($number,$min,$max,$name);

    }
    catch (Exception $e){
        echo '<br> Message :'. $e->getMessage().'<br>';
    }
}

    /**
     * @param $number
     * @param $min
     * @param $max
     * @param $name
     */
    protected function checkIntegerNumber($number, $min, $max, $name){
    try{
        $this->checkIfExists($number,$name);
        if($number<$min || $number>$max)
            throw new Exception($name. ' must not be greater than or length must not be greater than '.$max.' and must not be less than or length must not be less than '.$min);
    }
    catch (Exception $e){
        echo '<br> Message :'. $e->getMessage().'<br>';

    }
}


    /**
     * @param $data
     * @param $dataName
     */
    protected function checkIfExists($data, $dataName){
    try{
        if(!isset($data))
        throw new Exception($dataName.' cannot be blank');
    }
    catch (Exception $e  ){
        echo '<br> Message :'. $e->getMessage().'<br>';
    }
}



}