<?php

namespace App;

class ProgressRecord
{
    private $time;
    private $examineeId;
    private $password;
    private $name;
    private $level;
    private $login;
    private $progress;
    private $reStart;
    private $reason;
    
    public function __construct (
        $time, $examineeId, $password, $name, $level, $login, $progress, $reStart, $reStartDisabled, $cheatDisabled, $reason
    )
    {
        $this->time = $time;
        $this->examineeId = $examineeId;
        $this->password = $password;
        $this->name = $name;
        $this->level = $level;
        $this->login = $login;
        $this->progress = $progress;
        $this->reStart = $reStart;
        $this->reStartDisabled = $reStartDisabled;
        $this->cheatDisabled = $cheatDisabled;
        $this->reason = $reason;
    }

    public function getTime()
    {
        return $this->time;
    }
    
    public function getPassword(){
        return $this->password;
    }
    
    public function getLogin()
    {
        return $this->login;
    }

    public function getProgress()
    {
        return $this->progress;
    }
    
    public function getReStart()
    {
        return $this->reStart;
    }

    public function getReStartDisabled()
    {
        return $this->reStartDisabled;
    }
    
    public function getCheatDisabled()
    {
        return $this->cheatDisabled;
    }

    public function getReason()
    {
        return $this->reason;
    }

    public function getExamineeId()
    {
        return $this->examineeId;
    }

    public function setExamineeId($examineeId)
    {
        $this->examineeId = $examineeId;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getLevel()
    {
        return substr($this->level, 0,1);
    }

    public function setLevel($level)
    {
        $this->level = $level;
    }
}