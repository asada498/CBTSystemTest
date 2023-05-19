<?php

namespace App;

class ExamineeRecord
{
    private $examineeId;
    private $name;
    private $paymentDone;
    private $photoDone;
    private $country;
    private $city;
    private $testDay;
    private $level;

    public function __construct (
        $examineeId, $name, $paymentDone, $photoDone, $country, $city, $testDay, $level
    )
    {
        $this->examineeId = $examineeId;
        $this->name = $name;
        $this->paymentDone = $paymentDone;
        $this->photoDone = $photoDone;
        $this->country = $country;
        $this->city = $city;
        $this->testDay = $testDay;
        $this->level = $level;
    }

    public function getExamineeId()
    {
        return $this->examineeId;
    }

    public function setExamineeId($examineeId)
    {
        $this->examineeId = $examineeId;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getPaymentDone()
    {
        if($this->paymentDone == '1'){
            return '済';
        }else{
            return '未';
        }
    }

    public function setPaymentDone($paymentDone)
    {
        $this->paymentDone = $paymentDone;
    }

    public function getPhotoDone()
    {
        if($this->photoDone == '1'){
            return '済';
        }else{
            return '未';
        }
    }

    public function setPhotoDone($photoDone)
    {
        $this->photoDone = $photoDone;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country)
    {
        $this->country = $country;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;
    }

    public function getTestDay()
    {
        return $this->testDay;
    }

    public function setTestDay($testDay)
    {
        $this->testDay = $testDay;
    }

    public function getLevel()
    {
        return substr($this->level, 0,1);
    }

    public function setLevel($level)
    {
        $this->level = $level;
    }
}