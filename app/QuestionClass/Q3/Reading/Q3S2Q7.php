<?php

namespace App\QuestionClass\Q3\Reading;

class Q3S2Q7
{
    public $id;
    public $databaseIdQuestion;
    public $testeeTotalNumber;
    public $testeeTotalCorrectAnswer;
    public $textNumber;
    public $title;
    public $explanationText;
    public $illustration;
    public $question;
    public $choice1;
    public $choice2;
    public $choice3;
    public $choice4;
    public $correctAnswer;
    public $questionId;

    public function __construct ($id,$databaseIdQuestion,$testeeTotalNumber,$testeeTotalCorrectAnswer,$title,$explanationText,$illustration,$textNumber,
    $question,$choice1,$choice2,$choice3,$choice4,$correctAnswer,$newQuestion){
            $this->id = $id;
            $this->databaseIdQuestion = $databaseIdQuestion;
            $this->testeeTotalNumber = $testeeTotalNumber;
            $this->testeeTotalCorrectAnswer = $testeeTotalCorrectAnswer;
            $this->title = $title;
            $this->explanationText = $explanationText;
            $this->illustration = $illustration;
            $this->textNumber = $textNumber;
            $this->question = $question;
            $this->choice1 = $choice1;
            $this->choice2 = $choice2;
            $this->choice3 = $choice3;
            $this->choice4 = $choice4;
            $this->correctAnswer = $correctAnswer;
            $this->newQuestion = $newQuestion;
        }
    
    public function getDatabaseQuestionId()
    {
        return $this->databaseIdQuestion;
    }   
    public function setQuestionId($id)
    {
        return $this->questionId = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getTitle()
    {
        return $this->title;
    }
    public function getExplanationText()
    {
        return $this->explanationText;
    }

    public function getIllustration()
    {
        $illustrationIdList = $this->illustration;
        if(strpos( $illustrationIdList, "＃" ) == false)
            return explode(",", $illustrationIdList);
        else return "";
    }

    public function getQuestionId()
    {
        return $this->questionId;
    }
    public function getQuestion()
    {
        return trim($this->question);
    }

    public function getChoiceA()
    {
        return $this->choice1;
    }

    public function getChoiceB()
    {
        return $this->choice2;
    }

    public function getChoiceC()
    {
        return $this->choice3;
    }

    public function getChoiceD()
    {
        return $this->choice4;
    }

    public function getCorrectChoice()
    {
        return mb_convert_kana($this->correctAnswer, "KVr");
    }
}