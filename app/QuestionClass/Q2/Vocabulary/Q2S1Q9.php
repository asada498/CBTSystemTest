<?php

namespace App\QuestionClass\Q2\Vocabulary;

class Q2S1Q9
{
    public $id;
    public $databaseIdQuestion;
    public $grammar;
    public $classGrammar;
    public $sentencePatternClassification;
    public $correctAnswerRate;
    public $testeeTotalNumber;
    public $testeeTotalCorrectAnswer;
    public $questionNumber;
    public $text;
    public $samePassage;
    public $choiceA;
    public $choiceB;
    public $choiceC;
    public $choiceD;
    public $correctAnswer;
    public $questionId;
    public $newQuestion;


    public function __construct ($id,$databaseIdQuestion,$grammar,$classGrammar,$correctAnswerRate,$testeeTotalNumber,$testeeTotalCorrectAnswer,$questionNumber,$text,$samePassage,
    $choiceA,$choiceB,$choiceC,$choiceD,$correctAnswer,$newQuestion){
            $this->id = $id;
            $this->databaseIdQuestion = $databaseIdQuestion;
            $this->grammar = $grammar;
            $this->classGrammar = $classGrammar;
            $this->correctAnswerRate = $correctAnswerRate;
            $this->testeeTotalNumber = $testeeTotalNumber;
            $this->testeeTotalCorrectAnswer = $testeeTotalCorrectAnswer;
            $this->questionNumber = $questionNumber;
            $this->text = $text;
            $this->samePassage = $samePassage;
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

    public function getCorrectAnswerRate()
    {
        return $this->correctAnswerRate;
    }
    public function getSamePassage()
    {
        return $this->samePassage;
    }
    
    public function getText()
    {
        return trim($this->text);
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