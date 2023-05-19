<?php

namespace App;

class AnswerDownloadCondition
{
    private $examineeId;
    private $target_date;
    private $from_date;
    private $to_date;
    private $level;
    private $autoSearch;

    public function __construct (
        $examineeId, $target_date, $from_date, $to_date, $level, $autoSearch
    )
    {
        $this->examineeId = $examineeId;
        $this->target_date = $target_date;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->level = $level;
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

    public function getTargetDate()
    {
        return $this->target_date;
    }

    public function setTargetDate($target_date)
    {
        $this->target_date = $target_date;
    }

    public function getFromDate()
    {
        return $this->from_date;
    }

    public function setFromDate($from_date)
    {
        $this->from_date = $from_date;
    }

    public function getToDate()
    {
        return $this->to_date;
    }

    public function setToDate($to_date)
    {
        $this->to_date = $to_date;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function setLevel($level)
    {
        $this->level = $level;
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