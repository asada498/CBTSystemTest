<?php

namespace App;

class SiteRecord
{
    private $testSite;
    private $country;
    private $city;
    private $password;

    public function __construct (
        $testSite, $country, $city, $password
    )
    {
        $this->testSite = $testSite;
        $this->country = $country;
        $this->city = $city;
        $this->password = $password;
    }

    public function getTestSite()
    {
        return $this->testSite;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getPassword()
    {
        return $this->password;
    }
}