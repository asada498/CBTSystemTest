<?php

namespace App;

class GradeRecord
{
    private $examineeId;
    private $name;
    private $birthDay;
    private $gradesCertificate;
    private $passCertificate;
    private $passFail;
    private $anchorScore;
    private $anchorPassRate;
    private $score;
    private $sec1Score;
    private $sec2Score;
    private $sec3Score;

    public function __construct (
        $examineeId, $name, $birthDay, $gradesCertificate, $passCertificate, $passFail, $anchorScore, $anchorPassRate,
        $score, $sec1Score, $sec2Score, $sec3Score
    )
    {
        $this->examineeId = $examineeId;
        $this->name = $name;
        $this->birthDay = $birthDay;
        $this->gradesCertificate = $gradesCertificate;
        $this->passCertificate = $passCertificate;
        $this->passFail = $passFail;
        $this->anchorScore = $anchorScore;
        $this->anchorPassRate = $anchorPassRate;
        $this->score = $score;
        $this->sec1Score = $sec1Score;
        $this->sec2Score = $sec2Score;
        $this->sec3Score = $sec3Score;
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

    public function getBirthDay()
    {
        return $this->birthDay;
    }
    public function setBirthDay($birthDay)
    {
        $this->birthDay = $birthDay;
    }

    public function getGradesCertificate()
    {
        return $this->gradesCertificate;
    }
    public function setGradesCertificate($gradesCertificate)
    {
        $this->gradesCertificate = $gradesCertificate;
    }

    public function getPassCertificate()
    {
        return $this->passCertificate;
    }
    public function setPassCertificate($passCertificate)
    {
        $this->passCertificate = $passCertificate;
    }
    
    public function getPassFail()
    {
        switch($this->passFail){
        case 1:
            return '合';
        case 9:
            return '不正';
        default:
            return '不';
        }
    }
    public function setPassFail($passFail)
    {
        $this->passFail = $passFail;
    }

    public function getAnchorScore()
    {
        return $this->anchorScore;
    }
    public function setAnchorScore($anchorScore)
    {
        $this->anchorScore = $anchorScore;
    }

    public function getAnchorPassRate()
    {
        return $this->anchorPassRate;
    }
    public function setAnchorPassRate($anchorPassRate)
    {
        $this->anchorPassRate = $anchorPassRate;
    }

    public function getScore()
    {
        return $this->score;
    }
    public function setScore($score)
    {
        $this->score = $score;
    }

    public function getSec1Score()
    {
        return $this->sec1Score;
    }
    public function setSec1Score($sec1Score)
    {
        $this->sec1Score = $sec1Score;
    }

    public function getSec2Score()
    {
        return $this->sec2Score;
    }
    public function setSec2Score($sec2Score)
    {
        $this->sec2Score = $sec2Score;
    }

    public function getSec3Score()
    {
        return $this->sec3Score;
    }
    public function setSec3Score($sec3Score)
    {
        $this->sec3Score = $sec3Score;
    }
}