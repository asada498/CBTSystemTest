<?php

namespace App\QuestionClass\Q4\Reading;

class Q4S2Q2
{
    public $id;
    public $databaseIdQuestion;
    public $grammar;
    public $classGrammar;
    public $testeeTotalNumber;
    public $testeeTotalCorrectAnswer;
    public $question;
    public $choiceA;
    public $choiceB;
    public $choiceC;
    public $choiceD;
    public $correctAnswer;
    public $questionId;
    public $newQuestion;

    public function __construct ($id,$databaseIdQuestion,$grammar,$classGrammar,$testeeTotalNumber,$testeeTotalCorrectAnswer,$question,
        $choiceA,$choiceB,$choiceC,$choiceD,$correctAnswer,$newQuestion){
            $this->id = $id;
            $this->databaseIdQuestion = $databaseIdQuestion;
            $this->grammar = $grammar;
            $this->classGrammar = $classGrammar;
            $this->testeeTotalNumber = $testeeTotalNumber;
            $this->testeeTotalCorrectAnswer = $testeeTotalCorrectAnswer;
            $this->question = $question;
            $this->choiceA = $choiceA;
            $this->choiceB = $choiceB;
            $this->choiceC = $choiceC;
            $this->choiceD = $choiceD;
            $this->correctAnswer = $correctAnswer;
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
    public function getGrammarClass()
    {
        return $this->classGrammar;
    }
    public function getGrammar()
    {
        return $this->grammar;
    }
    public function getQuestionId()
    {
        return $this->questionId;
    }
    public function getQuestion()
    {
        $questionText = trim($this->question);  
        $detectText = "_";
        $lastPos = 0;
        $positions = array();
        while (($lastPos = strpos($questionText, $detectText, $lastPos))!== false) {
        $positions[] = $lastPos;
        $lastPos = $lastPos + strlen($detectText);
        }
        $starValue =  strpos($questionText,"★");
        foreach ($positions as $value) {
        if (!array_search($value-1,$positions))
        {
            if (array_search($value,$positions) !== 1 && $value !== $starValue+3)
            $questionText = substr_replace($questionText,"[",$value,1);
        }
        else if (!array_search($value+1,$positions))
        {
            if ($value !== $starValue-1)
            $questionText = substr_replace($questionText,"]",$value,1);
        }
    }
        $questionText = str_replace('_',"　",$questionText);
        $questionText = str_replace('[',"<u>",$questionText);
        $questionText = str_replace(']',"</u>",$questionText);

        return $questionText;
    }

    public function getChoiceA()
    {
        return $this->choiceA;
    }

    public function getChoiceB()
    {
        return $this->choiceB;
    }

    public function getChoiceC()
    {
        return $this->choiceC;
    }

    public function getChoiceD()
    {
        return $this->choiceD;
    }

    public function getCorrectChoice()
    {
        return mb_convert_kana($this->correctAnswer, "KVr");
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