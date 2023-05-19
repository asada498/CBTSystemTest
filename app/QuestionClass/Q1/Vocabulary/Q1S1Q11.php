<?php

namespace App\QuestionClass\Q1\Vocabulary;

class Q1S1Q11
{
    public $id;
    public $databaseIdQuestion;
    public $classReadingTheme;
    public $testeeTotalNumber;
    public $testeeTotalCorrectAnswer;
    public $samePassage;
    public $questionNumber;
    public $title;
    public $textA;
    public $textB;
    public $question;
    public $choiceA;
    public $choiceB;
    public $choiceC;
    public $choiceD;
    public $correctAnswer;
    public $newQuestion;
    public $questionId;

    public function __construct (
        $id,
        $databaseIdQuestion,
        $classReadingTheme,
        $testeeTotalNumber,
        $testeeTotalCorrectAnswer,
        $samePassage,
        $questionNumber,
        $title,
        $textA,
        $textB,
        $question,
        $choiceA,
        $choiceB,
        $choiceC,
        $choiceD,
        $correctAnswer,
        $newQuestion
        ){
            $this->id = $id;
            $this->databaseIdQuestion = $databaseIdQuestion;
            $this->classReadingTheme = $classReadingTheme;
            $this->testeeTotalNumber = $testeeTotalNumber;
            $this->testeeTotalCorrectAnswer = $testeeTotalCorrectAnswer;
            $this->samePassage = $samePassage;
            $this->questionNumber = $questionNumber;
            $this->title = $title;
            $this->textA = $textA;
            $this->textB = $textB;
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

    public function getPassage()
    {
        return $this->samePassage;
    }

    public function getTitle()
    {
        return $this->title;
    }
    
    public function getTextB()
    {
        $questionText = $this->textB;
        // $questionText = "パクさんが　リュウさんに　みじかい　てがみを　かきました。***##リュウさん#　きょうは　しごとが　たくさん　あります。　どようびと　にちようびは　ともだちに　あいます。　ですから　げつようび　きてください。#パク##***";
        $letterSign = "***";

        $pos = strpos($questionText, $letterSign);
        if ($pos !== false) {
            $questionText = substr_replace($questionText,  '<div class='.'"'.'container boxLetter'.'"'.'>' , $pos, strlen($letterSign));

        $pos = strpos($questionText, $letterSign);
        if ($pos !== false) {
            $questionText = substr_replace($questionText,  '</div>' , $pos, strlen($letterSign));
        }

        $detectText = "#";
        $lastPos = 0;
        $position = array();

        while (($lastPos = strpos($questionText, $detectText, $lastPos))!== false) {
        $position[] = $lastPos;
        $lastPos = $lastPos + strlen($detectText);
        }
        $arrayDivide = array_chunk($position,3);
        // dd($arrayDivide);
        foreach($arrayDivide as $placement)
        {
            if($placement[1] == $placement[0] + 1)
            {
                $questionText=substr_replace($questionText,'[[',$placement[0],2);
                $questionText=substr_replace($questionText,']',$placement[2],1);

            }
            else if ($placement[2] == $placement[1]+1)
            {
                $questionText=substr_replace($questionText,'{',$placement[0],1);
                $questionText=substr_replace($questionText,'}}',$placement[1],2);

            }
        }

        $questionText = str_replace('[[','<div class = '.'"'.'test-left-side'.'"'.">",$questionText);
        $questionText = str_replace(']',"</div>",$questionText);
        $questionText = str_replace('{','<div class = '.'"'.'test-right-side'.'"'.">",$questionText);
        $questionText = str_replace('}}',"</div>",$questionText);
        }

        return $questionText;
    //     return $this->text;
    }
    public function getTextA()
    {
        $questionText = $this->textA;
        // $questionText = "パクさんが　リュウさんに　みじかい　てがみを　かきました。***##リュウさん#　きょうは　しごとが　たくさん　あります。　どようびと　にちようびは　ともだちに　あいます。　ですから　げつようび　きてください。#パク##***";
        $letterSign = "***";

        $pos = strpos($questionText, $letterSign);
        if ($pos !== false) {
            $questionText = substr_replace($questionText,  '<div class='.'"'.'container boxLetter'.'"'.'>' , $pos, strlen($letterSign));

        $pos = strpos($questionText, $letterSign);
        if ($pos !== false) {
            $questionText = substr_replace($questionText,  '</div>' , $pos, strlen($letterSign));
        }

        $detectText = "#";
        $lastPos = 0;
        $position = array();

        while (($lastPos = strpos($questionText, $detectText, $lastPos))!== false) {
        $position[] = $lastPos;
        $lastPos = $lastPos + strlen($detectText);
        }
        $arrayDivide = array_chunk($position,3);
        // dd($arrayDivide);
        foreach($arrayDivide as $placement)
        {
            if($placement[1] == $placement[0] + 1)
            {
                $questionText=substr_replace($questionText,'[[',$placement[0],2);
                $questionText=substr_replace($questionText,']',$placement[2],1);

            }
            else if ($placement[2] == $placement[1]+1)
            {
                $questionText=substr_replace($questionText,'{',$placement[0],1);
                $questionText=substr_replace($questionText,'}}',$placement[1],2);

            }
        }

        $questionText = str_replace('[[','<div class = '.'"'.'test-left-side'.'"'.">",$questionText);
        $questionText = str_replace(']',"</div>",$questionText);
        $questionText = str_replace('{','<div class = '.'"'.'test-right-side'.'"'.">",$questionText);
        $questionText = str_replace('}}',"</div>",$questionText);
        }

        return $questionText;
    //     return $this->text;
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

    public function getDatabaseQuestionId()
    {
        return $this->databaseIdQuestion;
    }
} 