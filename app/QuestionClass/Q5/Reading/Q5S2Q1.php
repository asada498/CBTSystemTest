<?php

namespace App\QuestionClass\Q5\Reading;

class Q5S2Q1
{
    public $id;
    public $databaseIdQuestion;
    public $sentencePattern;
    public $kanji;
    public $partOfSpeech;
    public $testeeTotalNumber;
    public $testeeTotalCorrectAnswer;
    public $anchor;
    public $question;
    public $choice1;
    public $choice2;
    public $choice3;
    public $choice4;
    public $correctAnswer;
    public $questionId;
    public $newQuestion;

    public function __construct ($id,$databaseIdQuestion,$sentencePattern,$partOfSpeech,$kanji,$testeeTotalNumber,$testeeTotalCorrectAnswer,$anchor,$question,$choice1,
        $choice2,$choice3,$choice4,$correctAnswer,$newQuestion){
            $this->id = $id;
            $this->databaseIdQuestion=$databaseIdQuestion;
            $this->sentencePattern = $sentencePattern;
            $this->kanji = $kanji;
            $this->partOfSpeech = $partOfSpeech;
            $this->testeeTotalNumber = $testeeTotalNumber;
            $this->testeeTotalCorrectAnswer = $testeeTotalCorrectAnswer;
            $this->anchor = $anchor;
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
    
    public function getKanji()
    {
        return $this->kanji;

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
    public function getSentencePattern()
    {
        return $this->sentencePattern;
    }
    public function getPartOfSpeech()
    {
        return $this->partOfSpeech;
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

    public function getAnchor()
    {
        return $this->anchor;
    }
}