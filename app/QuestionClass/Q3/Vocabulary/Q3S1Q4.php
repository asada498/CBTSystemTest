<?php

namespace App\QuestionClass\Q3\Vocabulary;

class Q3S1Q4
{
    public $questionId;
    public $id;
    public $vocabulary;
    public $partOfSpeech;

    public $testeeTotalNumber;
    public $testeeTotalCorrectAnswer;
    public $anchor;
    public $question;
    public $choiceA;
    public $choiceB;
    public $choiceC;
    public $choiceD;
    public $correctAnswer;
    public $newQuestion;
    public $databaseIdQuestion;
    public $percentOfCorrectAnswer; //I can't know what is it

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
        // $str1 = str_replace("【", "〖 ", $str);
        // $str2 = str_replace("】", " 〗", $str1);
        $str1 = str_replace("【", "<u>", $str);
        $str2 = str_replace("】", "</u>", $str1);
        return $str2;
    }

    public function getChoiceA()
    {
        return preg_replace('/\s+/', '', $this->choiceA);
    }

    public function getChoiceB()
    {

        return preg_replace('/\s+/', '', $this->choiceB);
    }

    public function getChoiceC()
    {
        return preg_replace('/\s+/', '', $this->choiceC);
    }

    public function getChoiceD()
    {
        return preg_replace('/\s+/', '', $this->choiceD);
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

    public function checkNewQuestion()
    {
        return $this->newQuestion;
    }

    public function getDatabaseQuestionId()
    {
        return $this->databaseIdQuestion;
    }
}