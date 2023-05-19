<?php

namespace App\QuestionClass\Q3\Vocabulary;

class Q3S1Q5
{
    public $questionId;
    public $id;
    public $vocabulary;
    public $partOfSpeech;

    public $testeeTotalNumber;
    public $testeeTotalCorrectAnswer;
    public $question;
    public $choiceA;
    public $choiceB;
    public $choiceC;
    public $choiceD;
    public $correctAnswer;
    public $databaseIdQuestion;
    public $newQuestion;

    public function __construct(
        $id,
        $databaseIdQuestion,
        $vocabulary,
        $partOfSpeech,
        $testeeTotalNumber,
        $testeeTotalCorrectAnswer,
        $question,
        $choiceA,
        $choiceB,
        $choiceC,
        $choiceD,
        $correctAnswer,
        $newQuestion
    ) {
        $this->id = $id;
        $this->databaseIdQuestion = $databaseIdQuestion;
        $this->vocabulary = $vocabulary;
        $this->partOfSpeech = $partOfSpeech;
        $this->testeeTotalNumber = $testeeTotalNumber;
        $this->testeeTotalCorrectAnswer = $testeeTotalCorrectAnswer;
        $this->question = $question;
        $this->choiceA = $choiceA;
        $this->choiceB = $choiceB;
        $this->choiceC = $choiceC;
        $this->choiceD = $choiceD;
        $this->correctAnswer  = $correctAnswer;
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
    public function getvocabularyAnswer()
    {
        return $this->vocabulary;
    }
    public function getQuestionId()
    {
        return $this->questionId;
    }
    public function getQuestion()
    {
        $str = $this->question;
        // $str1 = str_replace("【", "<u>", $str);
        // $str2 = str_replace("】", "</u>", $str1);
        return $str;
    }

    public function getChoiceA()
    {
        $str = $this->choiceA;
        $str1 = str_replace("【", "<u>", $str);
        $str2 = str_replace("】", "</u>", $str1);
        return $str2;
    }

    public function getChoiceB()
    {

        $str = $this->choiceB;
        $str1 = str_replace("【", "<u>", $str);
        $str2 = str_replace("】", "</u>", $str1);
        return $str2;    
    }

    public function getChoiceC()
    {
        $str = $this->choiceC;
        $str1 = str_replace("【", "<u>", $str);
        $str2 = str_replace("】", "</u>", $str1);
        return $str2;   
    }

    public function getChoiceD()
    {
        $str = $this->choiceD;
        $str1 = str_replace("【", "<u>", $str);
        $str2 = str_replace("】", "</u>", $str1);
        return $str2;
    }
    public function setAnswer($correct)
    {
        return $this->correct = $correct;
    }
    public function getCorrectChoice()
    {
        $correctAnswer = $this->correctAnswer;
        return mb_convert_kana($correctAnswer, "KVr");
    }

    public function getPartOfSpeech()
    {
        return $this->partOfSpeech;
    }


    public function gettesteeTotalNumber()
    {
        return $this->testeeTotalNumber;
    }

    public function getDatabaseQuestionId()
    {
        return $this->databaseIdQuestion;
    }

    public function checkNewQuestion()
    {
        return $this->newQuestion;
    }
}