<?php

namespace App\QuestionClass\Q5\Vocabulary;


class Q5S1Q4
{
    public $questionId;
    public $id;
    public $databaseIdQuestion;
    public $vocabulary;
    public $partOfSpeech;

    public $percentOfCorrectAnswer;
    public $testeeTotalNumber;
    public $testeeTotalCorrectAnswer;

    public $question;
    public $choicea;
    public $choiceb;
    public $choicec;
    public $choiced;
    public $correct;
    public $newQuestion;

    public function __construct(
        $id,
        $databaseIdQuestion,
        $vocabulary,
        $partOfSpeech,
        $percentOfCorrectAnswer,
        $testeeTotalNumber,
        $testeeTotalCorrectAnswer,
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
        $this->partOfSpeech = $partOfSpeech;
        $this->percentOfCorrectAnswer = $percentOfCorrectAnswer;
        $this->testeeTotalNumber = $testeeTotalNumber;
        $this->testeeTotalCorrectAnswer = $testeeTotalCorrectAnswer;

        $this->question = $question;
        $this->choicea = $choicea;
        $this->choiceb = $choiceb;
        $this->choicec = $choicec;
        $this->choiced = $choiced;
        $this->correct  = $correct;
        $this->newQuestion = $newQuestion;

    }

    public function checkNewQuestion()
    {
        return $this->newQuestion;
    }
    
    public function setQuestionId($id)
    {
        return $this->questionId = $id;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getvocabularyAnswer()
    {
        return $this->vocabulary;
    }

    public function getDatabaseQuestionId()
    {
        return $this->databaseIdQuestion;
    }

    public function getpartofspeech()
    {
        return $this->partOfSpeech;
    }

    // public function getGroup()
    // {
    //     return $this->group;
    // }

    public function gettesteeTotalNumber()
    {
        return $this->testeeTotalNumber;
    }


    public function gettesteeTotalCorrectAnswer()
    {
        return $this->testeeTotalCorrectAnswer;
    }



    public function getQuestionId()
    {
        return $this->questionId;
    }
    public function getQuestion()
    {


        echo trim($this->question);
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
}
