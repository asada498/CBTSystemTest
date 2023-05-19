<?php

namespace App;

class AnswerDownloadRecord
{
    private $examineeId;
    private $level;
    private $questionId;
    private $section;
    private $questionNumber;
    private $number;
    private $answer;
    private $passFail;

    public function __construct (
        $examineeId, $level, $questionId, $section, $questionNumber, $number, $answer, $passFail
    )
    {
        $this->examineeId = $examineeId;
        $this->level = $level;
        $this->questionId = $questionId;
        $this->section = $section;
        $this->questionNumber = $questionNumber;
        $this->number = $number;
        $this->answer = $answer;
        $this->passFail = $passFail;
    }

    public function getExamineeId()
    {
        return $this->examineeId;
    }

    public function getLevel(){
        return $this->level;
    }

    public function getQuestionId(){
        return $this->questionId;
    }
    
    public function getSection(){
        return $this->section;
    }

    public function getQuestionNumber(){
        return $this->questionNumber;
    }

    public function getNumber(){
        return $this->number;
    }

    public function getAnswer(){
        return $this->answer;
    }

    public function getPassFail()
    {
        return $this->passFail;
    }
}