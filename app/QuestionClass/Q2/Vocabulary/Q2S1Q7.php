<?php

namespace App\QuestionClass\Q2\Vocabulary;

class Q2S1Q7
{
    public $id;
    public $databaseIdQuestion;
    public $grammar;
    public $classGrammar;
    public $kanji;
    public $testeeTotalNumber;
    public $testeeTotalCorrectAnswer;
    public $anchor;
    public $question;
    public $choiceA;
    public $choiceB;
    public $choiceC;
    public $choiceD;
    public $correctAnswer;
    public $newQuestion;

    public function __construct (
            $id,
            $databaseIdQuestion,
            $grammar,
            $classGrammar,
            $kanji,
            $testeeTotalNumber,
            $testeeTotalCorrectAnswer,
            $anchor,
            $question,
            $choiceA,
            $choiceB,
            $choiceC,
            $choiceD,
            $correctAnswer,
            $newQuestion
        )
        {
            $this->id = $id;
            $this->databaseIdQuestion = $databaseIdQuestion;
            $this->grammar = $grammar;
            $this->classGrammar = $classGrammar;
            $this->kanji = $kanji;
            $this->testeeTotalNumber = $testeeTotalNumber;
            $this->testeeTotalCorrectAnswer = $testeeTotalCorrectAnswer;
            $this->anchor = $anchor;
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
        return trim($this->question);
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

    public function getAnchor()
    {
        return $this->anchor;
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