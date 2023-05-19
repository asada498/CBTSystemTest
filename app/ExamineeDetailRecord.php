<?php

namespace App;

class ExamineeDetailRecord
{
    private $examineeId;
    private $pin;
    private $country;
    private $city;
    private $testDay;
    private $level;
    private $name;
    private $payment;
    private $birthDay;
    private $country_ad;
    private $address;
    private $zipCode;
    private $email;
    private $paymentNotYet;
    private $paymentDone;
    private $photo;
    private $photoDisabled;

    public function __construct (
        $examineeId, $pin, $country, $city, $testDay, $level, $name, $payment, $photo, $birthDay, $country_ad, $address, $zipCode, $email
    )
    {
        $this->examineeId = $examineeId;
        $this->pin = $pin;
        $this->country = $country;
        $this->city = $city;
        $this->testDay = $testDay;
        $this->level = $level;
        $this->name = $name;
        $this->payment = $payment;
        $this->photo = $photo;
        $this->birthDay = $birthDay;
        $this->country_ad = $country_ad;
        $this->address = $address;
        $this->zipCode = $zipCode;
        $this->email = $email;
        if($payment == "1"){
            $this->paymentDone = "checked";
            $this->paymentNotYet = "";
        }else{
            $this->paymentDone = "";
            $this->paymentNotYet = "checked";
        }
        if($photo == ""){
            $this->photo = "未";
            $this->photoDisabled = "disabled";
        }else{
            $this->photo = "済";
            $this->photoDisabled = "";
        }
    }

    public function getExamineeId()
    {
        return $this->examineeId;
    }
    public function setExamineeId($examineeId)
    {
        $this->examineeId = $examineeId;
    }

    public function getPin()
    {
        return $this->pin;
    }
    public function setPin($pin)
    {
        $this->pin = $pin;
    }

    public function getName()
    {
        return $this->name;
    }
    public function setName($name)
    {
        $this->name = $name;
    }

    public function getPayment()
    {
        return $this->payment;
    }
    public function setPayment($paymentDone)
    {
        $this->paymentDone = $paymentDone;
    }

    public function getPhoto()
    {
        return $this->photo;
    }
    public function setPhoto($photoDone)
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

    public function getBirthDay()
    {
        return $this->birthDay;
    }
    public function setBirthDay($birthDay)
    {
        $this->birthDay = $birthDay;
    }

    public function getCountryAd()
    {
        return $this->country_ad;
    }
    public function setCountryAd($country_ad)
    {
        $this->country_ad = $country_ad;
    }

    public function getAddress()
    {
        return $this->address;
    }
    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function getZipCode()
    {
        return $this->zipCode;
    }
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
    }

    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->mail = $email;
    }

    public function getPaymentNotYet()
    {
        return $this->paymentNotYet;
    }

    public function getPaymentDone()
    {
        return $this->paymentDone;
    }

    public function getPhotoDisabled()
    {
        return $this->photoDisabled;
    }
}