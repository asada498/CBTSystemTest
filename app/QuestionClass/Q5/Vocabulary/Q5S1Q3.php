<?php

namespace App\QuestionClass\Q5\Vocabulary;

class Q5S1Q3
{
    public $id;
    public $databaseIdQuestion;
    public $vocabulary;
    public $partOfSpeech;
    // public $group;
    public $testeeTotalNumber;
    public $testeeTotalCorrectAnswer;
    public $imageId;
    public $anchor;
    public $question;
    public $choice1;
    public $choice2;
    public $choice3;
    public $choice4;
    public $correctAnswer;
    public $questionId;
    public $newQuestion;

    public function __construct ($id,$databaseIdQuestion,$vocabulary,$partOfSpeech,$testeeTotalNumber,$testeeTotalCorrectAnswer,$imageId,$anchor,$question,$choice1,
        $choice2,$choice3,$choice4,$correctAnswer,$newQuestion){
            $this->id = $id;
            $this->databaseIdQuestion = $databaseIdQuestion;
            $this->vocabulary = $vocabulary;
            $this->partOfSpeech = $partOfSpeech;
            $this->testeeTotalNumber = $testeeTotalNumber;
            $this->testeeTotalCorrectAnswer = $testeeTotalCorrectAnswer;
            $this->imageId =  $imageId;
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
    public function getImageId()
    {
        return $this->imageId;
    }
    public function getVocabularyAnswer()
    {
        return $this->vocabulary;
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
        $correctAnswer = $this->correctAnswer;
        return mb_convert_kana($correctAnswer, "KVr");
    }

    public function getGroup()
    {
        return $this->group;
    }

    public function getPartOfSpeech()
    {
        return $this->partOfSpeech;
    }

    public function getAnchorStatus()
    {
        return $this->anchor;
    }
}