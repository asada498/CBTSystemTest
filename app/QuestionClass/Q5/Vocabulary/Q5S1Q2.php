<?php

namespace App\QuestionClass\Q5\Vocabulary;

class Q5S1Q2
{
    public $id;
    public $databaseIdQuestion;
    public $vocabulary;
    public $kanji;
    public $partOfSpeech;
    public $group1;
    public $testeeTotalNumber;
    public $testeeTotalCorrectAnswer;
    public $question;
    public $choice1;
    public $choice2;
    public $choice3;
    public $choice4;
    public $correctAnswer;
    public $questionId;
    public $newQuestion;

    public function __construct ($id,$databaseIdQuestion,$vocabulary,$kanji,$partOfSpeech,$group1,$testeeTotalNumber,$testeeTotalCorrectAnswer,$question,$choice1,
        $choice2,$choice3,$choice4,$correctAnswer,$newQuestion){
            $this->id = $id;
            $this->databaseIdQuestion = $databaseIdQuestion;
            $this->vocabulary = $vocabulary;
            $this->kanji = $kanji;
            $this->partOfSpeech = $partOfSpeech;
            $this->group1 = $group1;
            $this->testeeTotalNumber = $testeeTotalNumber;
            $this->testeeTotalCorrectAnswer = $testeeTotalCorrectAnswer;
            $this->question = $question;
            $this->choice1 = $choice1;
            $this->choice2 = $choice2;
            $this->choice3 = $choice3;
            $this->choice4 = $choice4;
            $this->correctAnswer = $correctAnswer;
            $this->newQuestion = $newQuestion;
        }
    public function checkNewQuestion()
    {
        return $this->newQuestion;
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
    public function getKanjiAnswer()
    {
        return $this->kanji;
    }
    public function getQuestionId()
    {
        return $this->questionId;
    }
    public function getQuestion()
    {
        $questionText = trim($this->question);
        // $questionText = str_replace("【","〖 ",$questionText);
        // $questionText = str_replace("】"," 〗",$questionText);
        return $questionText;
        // return htmlspecialchars($questionText);
    }

    public function getChoiceA()
    {
        return trim($this->choice1);
    }

    public function getChoiceB()
    {
        return trim($this->choice2);
    }

    public function getChoiceC()
    {
        return trim($this->choice3);
    }

    public function getChoiceD()
    {
        return trim($this->choice4);
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

    public function getPartOfSpeech()
    {
        return $this->partOfSpeech;
    }
}