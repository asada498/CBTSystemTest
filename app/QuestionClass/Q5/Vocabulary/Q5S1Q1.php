<?php

namespace App\QuestionClass\Q5\Vocabulary;

class Q5S1Q1
{
    public $questionId;
    public $id;
    public $vocabulary;
    public $kanji;
    public $partOfSpeech;
    public $group;

    public $percentOfCorrectAnswer;
    public $testeeTotalNumber;
    public $testeeTotalCorrectAnswer;
    public $anchor;
    public $question;
    public $choicea;
    public $choiceb;
    public $choicec;
    public $choiced;
    public $correct;
    public $databaseIdQuestion;
    public $newQuestion;

    public function __construct(
        $id,
        $databaseIdQuestion,
        $vocabulary,
        $kanji,
        $partOfSpeech,
        $group,
        $percentOfCorrectAnswer,
        $testeeTotalNumber,
        $testeeTotalCorrectAnswer,
        $anchor,
        $question,
        $choicea,
        $choiceb,
        $choicec,
        $choiced,
        $correct,
        $newQuestion
    ) {
        $this->id = $id;
        $this->databaseIdQuestion = $databaseIdQuestion;
        $this->vocabulary = $vocabulary;
        $this->kanji =$kanji;
        $this->partOfSpeech = $partOfSpeech;
        $this->group = $group;
        $this->percentOfCorrectAnswer = $percentOfCorrectAnswer;
        $this->testeeTotalNumber = $testeeTotalNumber;
        $this->testeeTotalCorrectAnswer = $testeeTotalCorrectAnswer;
        $this->anchor = $anchor;
        $this->question = $question;
        $this->choicea = $choicea;
        $this->choiceb = $choiceb;
        $this->choicec = $choicec;
        $this->choiced = $choiced;
        $this->correct  = $correct;
        $this->newQuestion = $newQuestion;
    }

    public function setQuestionId($id)
    {
        return $this->questionId = $id;
    }

    public function getDatabaseQuestionId()
    {
        return $this->databaseIdQuestion;
    }

    public function checkNewQuestion()
    {
        return $this->newQuestion;
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
        return $str;
    }

    public function getChoiceA()
    {
        return $this->choicea;
    }

    public function getChoiceB()
    {

        return $this->choiceb;
    }

    public function getChoiceC()
    {
        return $this->choicec;
    }

    public function getChoiceD()
    {
        return $this->choiced;
    }
    public function setAnswer($correct)
    {
        return $this->correct = $correct;
    }
    public function getCorrectChoice()
    {
        $correctAnswer = $this->correct;
        return mb_convert_kana($correctAnswer, "KVr");
    }

    public function getGroup()
    {
        return $this->group;
    }

    public function getpartofspeech()
    {
        return $this->partOfSpeech;
    }

    public function gettesteeTotalNumber()
    {
        return $this->testeeTotalNumber;
    }

    public function gettesteeTotalCorrectAnswer()
    {
        return $this->partOfSpeech;
    }
    public function getAnchor()
    {
        return $this->anchor;
    }
    public function getKanji()
    {
        return $this->kanji;

    }
}