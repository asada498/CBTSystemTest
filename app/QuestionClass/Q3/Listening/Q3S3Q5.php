<?php

namespace App\QuestionClass\Q3\Listening;

class Q3S3Q5
{   
    public $id;
    public $qid;
    public $sentence_pattern;
    public $correct_answer_rate;
    public $listening_class;
    public $listening_group;
    public $question_classification;
    public $past_testee_number;
    public $correct_testee_number;

    public $dupe;
    public $listening;
    public $silence;
    public $correct_answer;
    public $number_of_exam;
    public $usable;
    public $anchor; 
    public $no;
    public $banFile;
    public $question;
    public $choices;


    public function __construct(
        $id,
        $qid,
        $sentence_pattern,
        $correct_answer_rate,
        $listening_class,
        $listening_group,
        $question_classification,
        $past_testee_number,
        $correct_testee_number,
        // $dupe,
        $question,
        $choices,
        $listening,
        $silence,
        $correct_answer,
        $number_of_exam,
        $usable,
        $anchor
    ) {
        $this->id = $id;
        $this->qid = $qid;
        $this->sentence_pattern = $sentence_pattern;
        $this->correct_answer_rate = $correct_answer_rate;
        $this->listening_class = $listening_class;
        $this->listening_group = $listening_group;
        $this->question_classification=$question_classification;
        $this->past_testee_number = $past_testee_number;
        $this->correct_testee_number = $correct_testee_number;
        // $this->dupe = $dupe;
        $this->banFile = '';
        $this->listening = $listening;
        $this->silence = $silence;
        $this->correct_answer = $correct_answer;
        $this->number_of_exam = $number_of_exam;
        $this->usable = $usable;
        $this->anchor = $anchor;
        $this->no = -1;
        $this->question = $question;
        $this->choices = $choices;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getQid()
    {
        return $this->qid;
    }

    public function getQuestion()
    {
        return $this->question;
    }
    

    public function getListeningClass()
    {
        return $this->listening_class;
    }

    public function getListeningGroup()
    {
        return $this->listening_group;
    }
    public function getClassification()
    {
        return $this->question_classification;
    }

    public function getListening()
    {
        return $this->listening;
    }
    public function getCorrectTest()
    {
        return $this->correct_testee_number;
    }

    public function getPastTesteeNumber()
    {
        return $this->past_testee_number;
    }

    public function getCorrectAnswerrate()
    {
        return $this->correct_answer_rate;
    }
    public function getAnswer()
    {
        $answer =$this->correct_answer;
        return mb_convert_kana($answer, "KVr");
    }

    public function getSilence()
    {
        return $this->silence;
    }
    // public function getDupe()
    // {
    //     return $this->dupe;
    // }
    public function getnumberofExam()
    {
        return $this->number_of_exam;
    }

    public function getAnchor()
    {
        return $this->anchor;
    }

    public function getpattern()
    {
        return $this->sentence_pattern;
    }
    
    public function getChoice()
    {
        return $this->choices;
    }
    public function getUsable()
    {
        return $this->usable;
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
  

}
