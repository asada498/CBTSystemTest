<?php

namespace App\QuestionClass\Q4\Vocabulary;

class Q4S1Q2
{
    public $questionId;
    public $id;
    public $vocabulary;
    public $kanji;
    public $group1;
    public $group2;

    public $percentOfCorrectAnswer;
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
        $group1,
        $group2,
        $kanji,
        $percentOfCorrectAnswer,
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
        $this->group1 = $group1;
        $this->group2 = $group2;
        $this->kanji =$kanji;
        $this->percentOfCorrectAnswer = $percentOfCorrectAnswer;
        $this->testeeTotalNumber = $testeeTotalNumber;
        $this->testeeTotalCorrectAnswer = $testeeTotalCorrectAnswer;
        $this->question = $question;
        $this->choiceA = $choiceA;
        $this->choiceB = $choiceB;
        $this->choiceC = $choiceC;
        $this->choiceD = $choiceD;
        $this->correctAnswer  = $correctAnswer;
        $this->newQuestion  = $newQuestion;

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
        $str1 = str_replace("【", "〖 ", $str);
        $str2 = str_replace("】", " 〗", $str1);
        return $str;
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

    public function getGroup1()
    {
        return $this->group1;
    }

    public function getGroup2()
    {
        return $this->group2;
    }

    public function gettesteeTotalNumber()
    {
        return $this->testeeTotalNumber;
    }

    public function getKanji()
    {
        return $this->kanji;

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