<?php

namespace App\QuestionClass\Q5\Reading;

class Q5S2Q3
{
    public $id;
    public $databaseIdQuestion;
    public $sentencePattern;
    public $sentencePatternClassification;
    public $correctAnswerRate;
    public $testeeTotalNumber;
    public $testeeTotalCorrectAnswer;
    public $textNumber;
    public $text;
    public $question;
    public $choice1;
    public $choice2;
    public $choice3;
    public $choice4;
    public $correctAnswer;
    public $questionId;
    public $grammar;
    public $newQuestion;

    public function __construct ($id,$databaseIdQuestion,$grammar,$sentencePattern,$sentencePatternClassification,$correctAnswerRate,$testeeTotalNumber,$testeeTotalCorrectAnswer,$textNumber,$text,$question,$choice1,
        $choice2,$choice3,$choice4,$correctAnswer,$newQuestion){
            $this->id = $id;
            $this->databaseIdQuestion=$databaseIdQuestion;
            $this->grammar = $grammar;
            $this->sentencePattern = $sentencePattern;
            $this->sentencePatternClassification = $sentencePatternClassification;
            $this->correctAnswerRate = $correctAnswerRate;
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
            $this->newQuestion = $newQuestion;
        }

    public function getDatabaseQuestionId()
    {
        return $this->databaseIdQuestion;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getSentencePattern()
    {
        return $this->sentencePattern;
    }
    public function getSentencePatternClassification()
    {
        return $this->sentencePatternClassification;
    }
    public function getCorrectAnswerRate()
    {
        return $this->correctAnswerRate;
    }
    public function getTextNumber()
    {
        return $this->textNumber;
    }

    public function getQuestionId()
    {
        return $this->questionId;
    }

    public function getText()
    {
        return trim($this->text);
    }

    public function getQuestion()
    {
        return $this->question;
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