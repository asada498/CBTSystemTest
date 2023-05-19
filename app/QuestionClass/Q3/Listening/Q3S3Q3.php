<?php

namespace App\QuestionClass\Q3\Listening;

class Q3S3Q3
{
    public $id;
    public $qid;
    public $group1;
    public $group2;
    public $group3;
    public $correctAnswerRate;
    public $pastTesteeNumber;
    public $correctTesteeNumber;
    public $question;
    // public $choiceA;
    // public $choiceB;
    // public $choiceC;
    // public $choiceD;
    public $listening;
    public $correctAnswer;
    // public $anchor;
    public $silence;
    public $no;
    public $banFile;
    public $rows;
    public $newQuestion;

    public function __construct ($id, $qid, $group1, $group2, $group3, $pastTesteeNumber, $correctTesteeNumber,
                                    $question, 
                                    $listening, $correctAnswer, $silence, $newQuestion)
    {
        $this->id = $id;
        $this->qid = $qid;
        $this->group1 = $group1;
        $this->group2 = $group2;
        $this->group3 = $group3;
        $this->pastTesteeNumber = $pastTesteeNumber;
        $this->correctTesteeNumber = $correctTesteeNumber;
        $this->question = $question;
        // $this->choiceA = $choiceA;
        // $this->choiceB = $choiceB;
        // $this->choiceC = $choiceC;
        // $this->choiceD = $choiceD;
        $this->banFile = '';
        $this->listening = $listening;
        $this->correctAnswer = $correctAnswer;
        // $this->anchor = $anchor;
        $this->silence = $silence;
        $this->no = -1;
        $this->rows = 1;
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
    
    public function getQuestion()
    {
        return $this->question;
    }
 
    // public function getChoiceA()
    // {
    //     return $this->choiceA;
    // }
    
    // public function getChoiceB()
    // {
    //     return $this->choiceB;
    // }
    
    // public function getChoiceC()
    // {
    //     return $this->choiceC;
    // }

    // public function getChoiceD()
    // {
    //     return $this->choiceD;
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
    
    // public function getAnchor()
    // {
    //     return $this->anchor;
    // }

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

    public function getRows()
    {
        return $this->rows;
    }

    public function setRows($rows)
    {
        $this->rows = $rows;
    }

    public function getNewQuestion()
    {
        return $this->newQuestion;
    }
}