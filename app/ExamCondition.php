<?php

namespace App;

class ExamCondition
{
    private $country;
    private $city;
    private $testDay;
    private $id;

    public function __construct ($country, $city, $testDay, $id)
    {
        $this->country = $country;
        $this->city = $city;
        $this->testDay = $testDay;
        $this->id = $id;
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

    public function getID()
    {
        return $this->id;
    }

    public function setID($id)
    {
        $this->id = $id;
    }
}