<?php

namespace App\QuestionClass\Q1\Vocabulary;

class Q1S1Q12
{
    public $id;
    public $databaseIdQuestion;
    public $classReadingTheme;
    public $testeeTotalNumber;
    public $testeeTotalCorrectAnswer;
    public $samePassage;
    public $text;
    public $question;
    public $choiceA;
    public $choiceB;
    public $choiceC;
    public $choiceD;
    public $correctAnswer;
    public $newQuestion;
    public $questionId;

    public function __construct (
        $id,
        $databaseIdQuestion,
        $classReadingTheme,
        $testeeTotalNumber,
        $testeeTotalCorrectAnswer,
        $samePassage,
        $text,
        $question,
        $choiceA,
        $choiceB,
        $choiceC,
        $choiceD,
        $correctAnswer,
        $newQuestion)
        {
            $this->id = $id;
            $this->databaseIdQuestion = $databaseIdQuestion;
            $this->classReadingTheme = $classReadingTheme;
            $this->testeeTotalNumber = $testeeTotalNumber;
            $this->testeeTotalCorrectAnswer = $testeeTotalCorrectAnswer;
            $this->samePassage = $samePassage;
            $this->text = $text;
            $this->question = $question;
            $this->choiceA = $choiceA;
            $this->choiceB = $choiceB;
            $this->choiceC = $choiceC;
            $this->choiceD = $choiceD;
            $this->correctAnswer = $correctAnswer;
            $this->newQuestion = $newQuestion;
        }

    public function setQuestionId($id)
    {
        return $this->questionId = $id;
    }
    public function getId()
    {
        return $this->id;
    }

    public function getPassage()
    {
        return $this->samePassage;
    }

    public function getText()
    {
        $str = $this->text;
        $str1 = str_replace('【','<u>',$str);
        $str2 = str_replace('】','</u>',$str1);

        return $str2;
    }

    public function getQuestionId()
    {
        return $this->questionId;
    }
    public function getQuestion()
    {
        $str = trim($this->question);
        $str1 = str_replace('【','<u>',$str);
        $str2 = str_replace('】','</u>',$str1);

        return $str2;
    }

    public function getChoiceA()
    {
        return $this->choiceA;
    }

    public function getChoiceB()
    {
        return $this->choiceB;
    }

    public function getChoiceC()
    {
        return $this->choiceC;
    }

    public function getChoiceD()
    {
        return $this->choiceD;
    }

    public function getCorrectChoice()
    {
        return mb_convert_kana($this->correctAnswer, "KVr");
    }

    public function getDatabaseQuestionId()
    {
        return $this->databaseIdQuestion;
    }
} 