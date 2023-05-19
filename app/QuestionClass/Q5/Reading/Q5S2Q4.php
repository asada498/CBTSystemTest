<?php

namespace App\QuestionClass\Q5\Reading;

class Q5S2Q4
{
    public $id;
    public $categoryOfQuestion;
    public $databaseIdQuestion;
    public $theme;
    public $testeeTotalNumber;
    public $testeeTotalCorrectAnswer;
    public $text;
    public $question;
    public $choice1;
    public $choice2;
    public $choice3;
    public $choice4;
    public $correctAnswer;
    public $questionId;
    public $newQuestion;

    public function __construct ($id,$databaseIdQuestion,$categoryOfQuestion,$theme,$testeeTotalNumber,$testeeTotalCorrectAnswer,$text,$question,$choice1,
        $choice2,$choice3,$choice4,$correctAnswer,$newQuestion){
            $this->id = $id;
            $this->databaseIdQuestion = $databaseIdQuestion;
            $this->categoryOfQuestion = $categoryOfQuestion;
            $this->theme = $theme;
            $this->testeeTotalNumber = $testeeTotalNumber;
            $this->testeeTotalCorrectAnswer = $testeeTotalCorrectAnswer;
            $this->text = $text;
            $this->question = $question;
            $this->choice1 = $choice1;
            $this->choice2 = $choice2;
            $this->choice3 = $choice3;
            $this->choice4 = $choice4;
            $this->correctAnswer = $correctAnswer;
            $this->newQuestion = $newQuestion;
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
    public function getCategoryOfQuestion()
    {
        return $this->categoryOfQuestion;
    }
    public function getTheme()
    {
        return $this->theme;
    }

    public function getText()
    {
        $questionText = $this->text;
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
}