<?php

namespace App\QuestionClass\Q5\Reading;

class Q5S2Q5
{
    public $id;
    public $databaseIdQuestion;
    public $questionAnalysis;
    public $theme;
    public $testeeTotalNumber;
    public $testeeTotalCorrectAnswer;
    public $text;
    public $textNumber;
    public $question;
    public $choice1;
    public $choice2;
    public $choice3;
    public $choice4;
    public $correctAnswer;
    public $questionId;

    public function __construct ($id,$databaseIdQuestion,$questionAnalysis,$theme,$testeeTotalNumber,$testeeTotalCorrectAnswer,$textNumber,$text,$question,$choice1,
        $choice2,$choice3,$choice4,$correctAnswer){
            $this->id = $id;
            $this->databaseIdQuestion = $databaseIdQuestion;
            $this->questionAnalysis = $questionAnalysis;
            $this->theme = $theme;
            $this->testeeTotalNumber = $testeeTotalNumber;
            $this->testeeTotalCorrectAnswer = $testeeTotalCorrectAnswer;
            $this->textNumber = $textNumber;
            $this->text = $text;
            $this->question = $question;
            $this->choice1 = $choice1;
            $this->choice2 = $choice2;
            $this->choice3 = $choice3;
            $this->choice4 = $choice4;
            $this->correctAnswer = $correctAnswer;
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
    public function getQuestionAnalysis()
    {
        return $this->questionAnalysis;
    }
    public function getTheme()
    {
        return $this->theme;
    }

    public function getTextNumber()
    {
        return $this->textNumber;
    }

    public function getText()
    {
        return $this->text;
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