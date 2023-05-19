<?php

namespace App\QuestionClass\Q1\Listening;

class Q1S3Q3
{
    public $id;
    public $qid;

    public $group1;
    public $group2;
    public $group3;
    public $sentence;
    public $vocabulary;
    public $question;

    public $correctAnswerRate;
    public $pastTesteeNumber;
    public $correctTesteeNumber;
    //public $illustration;
    public $listening;
    public $correctAnswer;
    public $silence;
    public $no;
    public $banFile;
    public $newQuestion;

    public function __construct ($id, $qid, $group1, $group2, $group3, $sentence, $vocabulary, $question,
                                    $pastTesteeNumber, $correctTesteeNumber,
                                    $listening, $correctAnswer, $silence, $newQuestion)
    {
        $this->id = $id;
        $this->qid = $qid;
        $this->group1 = $group1;
        $this->group2 = $group2;
        $this->group3 = $group3;
        $this->sentence = $sentence;
        $this->vocabulary = $vocabulary;
        $this->question = $question;
        $this->pastTesteeNumber = $pastTesteeNumber;
        $this->correctTesteeNumber = $correctTesteeNumber;
        //$this->illustration = $illustration;
        $this->banFile = '';
        $this->listening = $listening;
        $this->correctAnswer = $correctAnswer;
        $this->silence = $silence;
        $this->no = -1;
        $this->newQuestion = $newQuestion;
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getQid()
    {
        return $this->qid;
    }

    public function getGroup1()
    {
        return $this->group1;
    }
    
    public function getGroup2()
    {
        return $this->group2;
    }

    public function getGroup3()
    {
        return $this->group3;
    }

    public function getSentence()
    {
        return $this->sentence;
    }

    public function getVocabulary()
    {
        return $this->vocabulary;
    }

    public function getQuestion()
    {
        return $this->question;
    }
    
    public function getCorrectAnswerRate(){
        return $this->correctAnswerRate;
    }

    public function getPastTesteeNumber()
    {
        return $this->pastTesteeNumber;
    }
    
    public function getCorrectTesteeNumber()
    {
        return $this->correctTesteeNumber;
    }
    
    // public function getIllustration()
    // {
    //     return $this->illustration;
    // }

    public function getListening()
    {
        return $this->listening;
    }
    
    public function getCorrectAnswer()
    {
        $answer = $this->correctAnswer;
        return mb_convert_kana($answer, "KVr");
    }
    
    public function getSilence()
    {
        return $this->silence;
    }

    public function getNo()
    {
        return $this->no;
    }

    public function setNo($no)
    {
        $this->no = $no;
    }

    public function getBanFile()
    {
        return $this->banFile;
    }

    public function setBanFile($banFile)
    {
        $this->banFile = $banFile;
    }

    public function getNewQuestion(){
        return $this->newQuestion;
    }
}