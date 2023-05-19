<?php

namespace App;

class SearchCondition
{
    private $examineeId;
    private $name;
    private $country;
    private $city;
    private $testDay;
    private $level;
    private $paymentNotYet;
    private $paymentDone;
    private $photoNotYet;
    private $photoDone;
    private $op;
    private $autoSearch;

    public function __construct (
        $examineeId, $name, $country, $city, $testDay, $level, $paymentDone, $paymentNotYet, $photoDone, $photoNotYet, $op, $autoSearch
    )
    {
        $this->examineeId = $examineeId;
        $this->name = $name;
        $this->country = $country;
        $this->city = $city;
        $this->testDay = $testDay;
        $this->level = $level;
        $this->paymentDone = $paymentDone;
        $this->paymentNotYet = $paymentNotYet;
        $this->photoDone = $photoDone;
        $this->photoNotYet = $photoNotYet;
        $this->op = $op;
        $this->autoSearch = $autoSearch;
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
        return $this->paymentDone;
    }

    public function setPaymentDone($paymentDone)
    {
        $this->paymentDone = $paymentDone;
    }

    public function getPaymentNotYet()
    {
        return $this->paymentNotYet;
    }

    public function setPaymentNotYet($paymentNotYet)
    {
        $this->paymentNotYet = $paymentNotYet;
    }

    public function getPhotoDone()
    {
        return $this->photoDone;
    }

    public function setPhotoDone($photoDone)
    {
        $this->photoDone = $photoDone;
    }

    public function getPhotoNotYet()
    {
        return $this->photoNotYet;
    }

    public function setPhotoNotYet($photoNotYet)
    {
        $this->photoNotYet = $photoNotYet;
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
        return $this->level;
    }

    public function setLevel($level)
    {
        $this->level = $level;
    }

    public function getOp()
    {
        return $this->op;
    }

    public function setOp($op)
    {
        $this->op = $op;
    }

    public function setAutoSearch($autoSearch)
    {
        $this->autoSearch = $autoSearch;
    }

    public function getAutoSearch()
    {
        return $this->autoSearch;
    }

}