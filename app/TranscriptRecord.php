<?php

namespace App;

class TranscriptRecord
{
    private $jukenBangou;   // 受験番号
    private $heikinSougou;  // 平均総合
    private $heikin1;       // 平均1    
    private $heikin2;       // 平均2
    private $heikin3;       // 平均3
    private $heikin101;     // 平均101
    private $heikin102;     // 平均102
    private $heikin103;     // 平均103
    private $heikin104;     // 平均104
    private $heikin105;     // 平均105
    private $heikin106;     // 平均106
    private $heikin107;     // 平均107
    private $heikin108;     // 平均108
    private $heikin109;     // 平均109
    private $heikin110;     // 平均110
    private $heikin201;     // 平均201
    private $heikin202;     // 平均202
    private $heikin203;     // 平均203
    private $heikin204;     // 平均204
    private $heikin205;     // 平均205
    private $heikin206;     // 平均206
    private $heikin207;     // 平均207
    private $heikin208;     // 平均208
    private $heikin209;     // 平均209
    private $heikin210;     // 平均210
    private $heikin301;     // 平均301
    private $heikin302;     // 平均302
    private $heikin303;     // 平均303
    private $heikin304;     // 平均304
    private $heikin305;     // 平均305
    private $heikin306;     // 平均306
    private $heikin307;     // 平均307
    private $heikin308;     // 平均308
    private $heikin309;     // 平均309
    private $heikin310;     // 平均310
    private $haitenSougou;  // 配点総合
    private $haiten1;       // 配点1
    private $haiten2;       // 配点2
    private $haiten3;       // 配点3
    private $haiten101;     // 配点101
    private $haiten102;     // 配点102
    private $haiten103;     // 配点103
    private $haiten104;     // 配点104
    private $haiten105;     // 配点105
    private $haiten106;     // 配点106
    private $haiten107;     // 配点107
    private $haiten108;     // 配点108
    private $haiten109;     // 配点109
    private $haiten110;     // 配点110
    private $haiten201;     // 配点201
    private $haiten202;     // 配点202
    private $haiten203;     // 配点203
    private $haiten204;     // 配点204
    private $haiten205;     // 配点205
    private $haiten206;     // 配点206
    private $haiten207;     // 配点207
    private $haiten208;     // 配点208
    private $haiten209;     // 配点209
    private $haiten210;     // 配点210
    private $haiten301;     // 配点301
    private $haiten302;     // 配点302
    private $haiten303;     // 配点303
    private $haiten304;     // 配点304
    private $haiten305;     // 配点305
    private $haiten306;     // 配点306
    private $haiten307;     // 配点307
    private $haiten308;     // 配点308
    private $haiten309;     // 配点309
    private $haiten310;     // 配点310
    private $bunyaMei1;     // 分野名1
    private $bunyaMei2;     // 分野名2
    private $bunyaMei3;     // 分野名3
    private $bunyaMei4;     // 分野名4
    private $bunyaMei5;     // 分野名5
    private $mondai101;     // 問題101
    private $mondai102;     // 問題102
    private $mondai103;     // 問題103
    private $mondai104;     // 問題104
    private $mondai105;     // 問題105
    private $mondai106;     // 問題106
    private $mondai107;     // 問題107
    private $mondai108;     // 問題108
    private $mondai109;     // 問題109
    private $mondai110;     // 問題110
    private $mondai201;     // 問題201
    private $mondai202;     // 問題202
    private $mondai203;     // 問題203
    private $mondai204;     // 問題204
    private $mondai205;     // 問題205
    private $mondai206;     // 問題206
    private $mondai207;     // 問題207
    private $mondai208;     // 問題208
    private $mondai209;     // 問題209
    private $mondai210;     // 問題210
    private $mondai301;     // 問題301
    private $mondai302;     // 問題302
    private $mondai303;     // 問題303
    private $mondai304;     // 問題304
    private $mondai305;     // 問題305
    private $mondai306;     // 問題306
    private $mondai307;     // 問題307
    private $mondai308;     // 問題308
    private $mondai309;     // 問題309
    private $mondai310;     // 問題310
    private $jukenBangou2;  // （受験番号）
    private $hyoukaSougou;  // 評価総合
    private $hyouka1;       // 評価1
    private $hyouka2;       // 評価2
    private $hyouka3;       // 評価3
    private $gouhiHantei;   // 合否判定
    private $tokutenSougou; // 得点総合
    private $tokuten1;      // 得点1
    private $tokuten2;      // 得点2
    private $tokuten3;      // 得点3
    private $tokuten101;    // 得点101
    private $tokuten102;    // 得点102
    private $tokuten103;    // 得点103
    private $tokuten104;    // 得点104
    private $tokuten105;    // 得点105
    private $tokuten106;    // 得点106
    private $tokuten107;    // 得点107
    private $tokuten108;    // 得点108
    private $tokuten109;    // 得点109
    private $tokuten110;    // 得点110
    private $tokuten201;    // 得点201
    private $tokuten202;    // 得点202
    private $tokuten203;    // 得点203
    private $tokuten204;    // 得点204
    private $tokuten205;    // 得点205
    private $tokuten206;    // 得点206
    private $tokuten207;    // 得点207
    private $tokuten208;    // 得点208
    private $tokuten209;    // 得点209
    private $tokuten210;    // 得点210
    private $tokuten301;    // 得点301
    private $tokuten302;    // 得点302
    private $tokuten303;    // 得点303
    private $tokuten304;    // 得点304
    private $tokuten305;    // 得点305
    private $tokuten306;    // 得点306
    private $tokuten307;    // 得点307
    private $tokuten308;    // 得点308
    private $tokuten309;    // 得点309
    private $tokuten310;    // 得点310
    
    public function __construct ()
    {
    }

	public function setJukenBangou($jukenBangou){
        $this->jukenBangou   = $jukenBangou;   // 受験番号
    }
    public function setHeikinSougou($heikinSougou){
        $this->heikinSougou  = $heikinSougou;  // 平均総合
    }
    public function setHeikin1($heikin1){
        $this->heikin1       = $heikin1;       // 平均1
    }
    public function setHeikin2($heikin2){
        $this->heikin2       = $heikin2;       // 平均2
    }
    public function setHeikin3($heikin3){
        $this->heikin3       = $heikin3;       // 平均3
    }
    public function setHeikin101($heikin101){
        $this->heikin101     = $heikin101;     // 平均101
    }
    public function setHeikin102($heikin102){
        $this->heikin102     = $heikin102;     // 平均102
    }
    public function setHeikin103($heikin103){
        $this->heikin103     = $heikin103;     // 平均103
    }
    public function setHeikin104($heikin104){
        $this->heikin104     = $heikin104;     // 平均104
    }
    public function setHeikin105($heikin105){
        $this->heikin105     = $heikin105;     // 平均105
    }
    public function setHeikin106($heikin106){
        $this->heikin106     = $heikin106;     // 平均106
    }
    public function setHeikin107($heikin107){
        $this->heikin107     = $heikin107;     // 平均107
    }
    public function setHeikin108($heikin108){
        $this->heikin108     = $heikin108;     // 平均108
    }
    public function setHeikin109($heikin109){
        $this->heikin109     = $heikin109;     // 平均109
    }
    public function setHeikin110($heikin110){
        $this->heikin110     = $heikin110;     // 平均110
    }
    public function setHeikin201($heikin201){
        $this->heikin201     = $heikin201;     // 平均201
    }
    public function setHeikin202($heikin202){
        $this->heikin202     = $heikin202;     // 平均202
    }
    public function setHeikin203($heikin203){
        $this->heikin203     = $heikin203;     // 平均203
    }
    public function setHeikin204($heikin204){
        $this->heikin204     = $heikin204;     // 平均204
    }
    public function setHeikin205($heikin205){
        $this->heikin205     = $heikin205;     // 平均205
    }
    public function setHeikin206($heikin206){
        $this->heikin206     = $heikin206;     // 平均206
    }
    public function setHeikin207($heikin207){
        $this->heikin207     = $heikin207;     // 平均207
    }
    public function setHeikin208($heikin208){
        $this->heikin208     = $heikin208;     // 平均208
    }
    public function setHeikin209($heikin209){
        $this->heikin209     = $heikin209;     // 平均209
    }
    public function setHeikin210($heikin210){
        $this->heikin210     = $heikin210;     // 平均210
    }
    public function setHeikin301($heikin301){
        $this->heikin301     = $heikin301;     // 平均301
    }
    public function setHeikin302($heikin302){
        $this->heikin302     = $heikin302;     // 平均302
    }
    public function setHeikin303($heikin303){
        $this->heikin303     = $heikin303;     // 平均303
    }
    public function setHeikin304($heikin304){
        $this->heikin304     = $heikin304;     // 平均304
    }
    public function setHeikin305($heikin305){
        $this->heikin305     = $heikin305;     // 平均305
    }
    public function setHeikin306($heikin306){
        $this->heikin306     = $heikin306;     // 平均306
    }
    public function setHeikin307($heikin307){
        $this->heikin307     = $heikin307;     // 平均307
    }
    public function setHeikin308($heikin308){
        $this->heikin308     = $heikin308;     // 平均308
    }
    public function setHeikin309($heikin309){
        $this->heikin309     = $heikin309;     // 平均309
    }
    public function setHeikin310($heikin310){
        $this->heikin310     = $heikin310;     // 平均310
    }
    public function setHaitenSougou($haitenSougou){
        $this->haitenSougou  = $haitenSougou;  // 配点総合
    }
    public function setHaiten1($haiten1){
        $this->haiten1       = $haiten1;       // 配点1
    }
    public function setHaiten2($haiten2){
        $this->haiten2       = $haiten2;       // 配点2
    }
    public function setHaiten3($haiten3){
        $this->haiten3       = $haiten3;       // 配点3
    }
    public function setHaiten101($haiten101){
        $this->haiten101     = $haiten101;     // 配点101
    }
    public function setHaiten102($haiten102){
        $this->haiten102     = $haiten102;     // 配点102
    }
    public function setHaiten103($haiten103){
        $this->haiten103     = $haiten103;     // 配点103
    }
    public function setHaiten104($haiten104){
        $this->haiten104     = $haiten104;     // 配点104
    }
    public function setHaiten105($haiten105){
        $this->haiten105     = $haiten105;     // 配点105
    }
    public function setHaiten106($haiten106){
        $this->haiten106     = $haiten106;     // 配点106
    }
    public function setHaiten107($haiten107){
        $this->haiten107     = $haiten107;     // 配点107
    }
    public function setHaiten108($haiten108){
        $this->haiten108     = $haiten108;     // 配点108
    }
    public function setHaiten109($haiten109){
        $this->haiten109     = $haiten109;     // 配点109
    }
    public function setHaiten110($haiten110){
        $this->haiten110     = $haiten110;     // 配点110
    }
    public function setHaiten201($haiten201){
        $this->haiten201     = $haiten201;     // 配点201
    }
    public function setHaiten202($haiten202){
        $this->haiten202     = $haiten202;     // 配点202
    }
    public function setHaiten203($haiten203){
        $this->haiten203     = $haiten203;     // 配点203
    }
    public function setHaiten204($haiten204){
        $this->haiten204     = $haiten204;     // 配点204
    }
    public function setHaiten205($haiten205){
        $this->haiten205     = $haiten205;     // 配点205
    }
    public function setHaiten206($haiten206){
        $this->haiten206     = $haiten206;     // 配点206
    }
    public function setHaiten207($haiten207){
        $this->haiten207     = $haiten207;     // 配点207
    }
    public function setHaiten208($haiten208){
        $this->haiten208     = $haiten208;     // 配点208
    }
    public function setHaiten209($haiten209){
        $this->haiten209     = $haiten209;     // 配点209
    }
    public function setHaiten210($haiten210){
        $this->haiten210     = $haiten210;     // 配点210
    }
    public function setHaiten301($haiten301){
        $this->haiten301     = $haiten301;     // 配点301
    }
    public function setHaiten302($haiten302){
        $this->haiten302     = $haiten302;     // 配点302
    }
    public function setHaiten303($haiten303){
        $this->haiten303     = $haiten303;     // 配点303
    }
    public function setHaiten304($haiten304){
        $this->haiten304     = $haiten304;     // 配点304
    }
    public function setHaiten305($haiten305){
        $this->haiten305     = $haiten305;     // 配点305
    }
    public function setHaiten306($haiten306){
        $this->haiten306     = $haiten306;     // 配点306
    }
    public function setHaiten307($haiten307){
        $this->haiten307     = $haiten307;     // 配点307
    }
    public function setHaiten308($haiten308){
        $this->haiten308     = $haiten308;     // 配点308
    }
    public function setHaiten309($haiten309){
        $this->haiten309     = $haiten309;     // 配点309
    }
    public function setHaiten310($haiten310){
        $this->haiten310     = $haiten310;     // 配点310
    }
    public function setBunyaMei1($bunyaMei1){
        $this->bunyaMei1     = $bunyaMei1;     // 分野名1
    }
    public function setBunyaMei2($bunyaMei2){
        $this->bunyaMei2     = $bunyaMei2;     // 分野名2
    }
    public function setBunyaMei3($bunyaMei3){
        $this->bunyaMei3     = $bunyaMei3;     // 分野名3
    }
    public function setBunyaMei4($bunyaMei4){
        $this->bunyaMei4     = $bunyaMei4;     // 分野名4
    }
    public function setBunyaMei5($bunyaMei5){
        $this->bunyaMei5     = $bunyaMei5;     // 分野名5
    }
    public function setMondai101($mondai101){
        $this->mondai101     = $mondai101;     // 問題101
    }
    public function setMondai102($mondai102){
        $this->mondai102     = $mondai102;     // 問題102
    }
    public function setMondai103($mondai103){
        $this->mondai103     = $mondai103;     // 問題103
    }
    public function setMondai104($mondai104){
        $this->mondai104     = $mondai104;     // 問題104
    }
    public function setMondai105($mondai105){
        $this->mondai105     = $mondai105;     // 問題105
    }
    public function setMondai106($mondai106){
        $this->mondai106     = $mondai106;     // 問題106
    }
    public function setMondai107($mondai107){
        $this->mondai107     = $mondai107;     // 問題107
    }
    public function setMondai108($mondai108){
        $this->mondai108     = $mondai108;     // 問題108
    }
    public function setMondai109($mondai109){
        $this->mondai109     = $mondai109;     // 問題109
    }
    public function setMondai110($mondai110){
        $this->mondai110     = $mondai110;     // 問題110
    }
    public function setMondai201($mondai201){
        $this->mondai201     = $mondai201;     // 問題201
    }
    public function setMondai202($mondai202){
        $this->mondai202     = $mondai202;     // 問題202
    }
    public function setMondai203($mondai203){
        $this->mondai203     = $mondai203;     // 問題203
    }
    public function setMondai204($mondai204){
        $this->mondai204     = $mondai204;     // 問題204
    }
    public function setMondai205($mondai205){
        $this->mondai205     = $mondai205;     // 問題205
    }
    public function setMondai206($mondai206){
        $this->mondai206     = $mondai206;     // 問題206
    }
    public function setMondai207($mondai207){
        $this->mondai207     = $mondai207;     // 問題207
    }
    public function setMondai208($mondai208){
        $this->mondai208     = $mondai208;     // 問題208
    }
    public function setMondai209($mondai209){
        $this->mondai209     = $mondai209;     // 問題209
    }
    public function setMondai210($mondai210){
        $this->mondai210     = $mondai210;     // 問題210
    }
    public function setMondai301($mondai301){
        $this->mondai301     = $mondai301;     // 問題301
    }
    public function setMondai302($mondai302){
        $this->mondai302     = $mondai302;     // 問題302
    }
    public function setMondai303($mondai303){
        $this->mondai303     = $mondai303;     // 問題303
    }
    public function setMondai304($mondai304){
        $this->mondai304     = $mondai304;     // 問題304
    }
    public function setMondai305($mondai305){
        $this->mondai305     = $mondai305;     // 問題305
    }
    public function setMondai306($mondai306){
        $this->mondai306     = $mondai306;     // 問題306
    }
    public function setMondai307($mondai307){
        $this->mondai307     = $mondai307;     // 問題307
    }
    public function setMondai308($mondai308){
        $this->mondai308     = $mondai308;     // 問題308
    }
    public function setMondai309($mondai309){
        $this->mondai309     = $mondai309;     // 問題309
    }
    public function setMondai310($mondai310){
        $this->mondai310     = $mondai310;     // 問題310
    }
    public function setJukenBangou2($jukenBangou2){
        $this->jukenBangou2  = $jukenBangou2;  // （受験番号）
    }
    public function setHyoukaSougou($hyoukaSougou){
        $this->hyoukaSougou  = $hyoukaSougou;  // 評価総合
    }
    public function setHyouka1($hyouka1){
        $this->hyouka1       = $hyouka1;       // 評価1
    }
    public function setHyouka2($hyouka2){
        $this->hyouka2       = $hyouka2;       // 評価2
    }
    public function setHyouka3($hyouka3){
        $this->hyouka3       = $hyouka3;       // 評価3
    }
    public function setGouhiHantei($gouhiHantei){
        $this->gouhiHantei   = $gouhiHantei;   // 合否判定
    }
    public function setTokutenSougou($tokutenSougou){
        $this->tokutenSougou = $tokutenSougou; // 得点総合
    }
    public function setTokuten1($tokuten1){
        $this->tokuten1      = $tokuten1;      // 得点1
    }
    public function setTokuten2($tokuten2){
        $this->tokuten2      = $tokuten2;      // 得点2
    }
    public function setTokuten3($tokuten3){
        $this->tokuten3      = $tokuten3;      // 得点3
    }
    public function setTokuten101($tokuten101){
        $this->tokuten101    = $tokuten101;    // 得点101
    }
    public function setTokuten102($tokuten102){
        $this->tokuten102    = $tokuten102;    // 得点102
    }
    public function setTokuten103($tokuten103){
        $this->tokuten103    = $tokuten103;    // 得点103
    }
    public function setTokuten104($tokuten104){
        $this->tokuten104    = $tokuten104;    // 得点104
    }
    public function setTokuten105($tokuten105){
        $this->tokuten105    = $tokuten105;    // 得点105
    }
    public function setTokuten106($tokuten106){
        $this->tokuten106    = $tokuten106;    // 得点106
    }
    public function setTokuten107($tokuten107){
        $this->tokuten107    = $tokuten107;    // 得点107
    }
    public function setTokuten108($tokuten108){
        $this->tokuten108    = $tokuten108;    // 得点108
    }
    public function setTokuten109($tokuten109){
        $this->tokuten109    = $tokuten109;    // 得点109
    }
    public function setTokuten110($tokuten110){
        $this->tokuten110    = $tokuten110;    // 得点110
    }
    public function setTokuten201($tokuten201){
        $this->tokuten201    = $tokuten201;    // 得点201
    }
    public function setTokuten202($tokuten202){
        $this->tokuten202    = $tokuten202;    // 得点202
    }
    public function setTokuten203($tokuten203){
        $this->tokuten203    = $tokuten203;    // 得点203
    }
    public function setTokuten204($tokuten204){
        $this->tokuten204    = $tokuten204;    // 得点204
    }
    public function setTokuten205($tokuten205){
        $this->tokuten205    = $tokuten205;    // 得点205
    }
    public function setTokuten206($tokuten206){
        $this->tokuten206    = $tokuten206;    // 得点206
    }
    public function setTokuten207($tokuten207){
        $this->tokuten207    = $tokuten207;    // 得点207
    }
    public function setTokuten208($tokuten208){
        $this->tokuten208    = $tokuten208;    // 得点208
    }
    public function setTokuten209($tokuten209){
        $this->tokuten209    = $tokuten209;    // 得点209
    }
    public function setTokuten210($tokuten210){
        $this->tokuten210    = $tokuten210;    // 得点210
    }
    public function setTokuten301($tokuten301){
        $this->tokuten301    = $tokuten301;    // 得点301
    }
    public function setTokuten302($tokuten302){
        $this->tokuten302    = $tokuten302;    // 得点302
    }
    public function setTokuten303($tokuten303){
        $this->tokuten303    = $tokuten303;    // 得点303
    }
    public function setTokuten304($tokuten304){
        $this->tokuten304    = $tokuten304;    // 得点304
    }
    public function setTokuten305($tokuten305){
        $this->tokuten305    = $tokuten305;    // 得点305
    }
    public function setTokuten306($tokuten306){
        $this->tokuten306    = $tokuten306;    // 得点306
    }
    public function setTokuten307($tokuten307){
        $this->tokuten307    = $tokuten307;    // 得点307
    }
    public function setTokuten308($tokuten308){
        $this->tokuten308    = $tokuten308;    // 得点308
    }
    public function setTokuten309($tokuten309){
        $this->tokuten309    = $tokuten309;    // 得点309
    }
    public function setTokuten310($tokuten310){
        $this->tokuten310    = $tokuten310;    // 得点310
    }

    public function getJukenBangou(){
        return $this->jukenBangou;   // 受験番号
    }
    public function getHeikinSougou(){
        return $this->heikinSougou;  // 平均総合
    }
    public function getHeikin1(){
        return $this->heikin1;       // 平均1
    }
    public function getHeikin2(){
        return $this->heikin2;       // 平均2
    }
    public function getHeikin3(){
        return $this->heikin3;       // 平均3
    }
    public function getHeikin101(){
        return $this->heikin101;     // 平均101
    }
    public function getHeikin102(){
        return $this->heikin102;     // 平均102
    }
    public function getHeikin103(){
        return $this->heikin103;     // 平均103
    }
    public function getHeikin104(){
        return $this->heikin104;     // 平均104
    }
    public function getHeikin105(){
        return $this->heikin105;     // 平均105
    }
    public function getHeikin106(){
        return $this->heikin106;     // 平均106
    }
    public function getHeikin107(){
        return $this->heikin107;     // 平均107
    }
    public function getHeikin108(){
        return $this->heikin108;     // 平均108
    }
    public function getHeikin109(){
        return $this->heikin109;     // 平均109
    }
    public function getHeikin110(){
        return $this->heikin110;     // 平均110
    }
    public function getHeikin201(){
        return $this->heikin201;     // 平均201
    }
    public function getHeikin202(){
        return $this->heikin202;     // 平均202
    }
    public function getHeikin203(){
        return $this->heikin203;     // 平均203
    }
    public function getHeikin204(){
        return $this->heikin204;     // 平均204
    }
    public function getHeikin205(){
        return $this->heikin205;     // 平均205
    }
    public function getHeikin206(){
        return $this->heikin206;     // 平均206
    }
    public function getHeikin207(){
        return $this->heikin207;     // 平均207
    }
    public function getHeikin208(){
        return $this->heikin208;     // 平均208
    }
    public function getHeikin209(){
        return $this->heikin209;     // 平均209
    }
    public function getHeikin210(){
        return $this->heikin210;     // 平均210
    }
    public function getHeikin301(){
        return $this->heikin301;     // 平均301
    }
    public function getHeikin302(){
        return $this->heikin302;     // 平均302
    }
    public function getHeikin303(){
        return $this->heikin303;     // 平均303
    }
    public function getHeikin304(){
        return $this->heikin304;     // 平均304
    }
    public function getHeikin305(){
        return $this->heikin305;     // 平均305
    }
    public function getHeikin306(){
        return $this->heikin306;     // 平均306
    }
    public function getHeikin307(){
        return $this->heikin307;     // 平均307
    }
    public function getHeikin308(){
        return $this->heikin308;     // 平均308
    }
    public function getHeikin309(){
        return $this->heikin309;     // 平均309
    }
    public function getHeikin310(){
        return $this->heikin310;     // 平均310
    }
    public function getHaitenSougou(){
        return $this->haitenSougou;  // 配点総合
    }
    public function getHaiten1(){
        return $this->haiten1;       // 配点1
    }
    public function getHaiten2(){
        return $this->haiten2;       // 配点2
    }
    public function getHaiten3(){
        return $this->haiten3;       // 配点3
    }
    public function getHaiten101(){
        return $this->haiten101;     // 配点101
    }
    public function getHaiten102(){
        return $this->haiten102;     // 配点102
    }
    public function getHaiten103(){
        return $this->haiten103;     // 配点103
    }
    public function getHaiten104(){
        return $this->haiten104;     // 配点104
    }
    public function getHaiten105(){
        return $this->haiten105;     // 配点105
    }
    public function getHaiten106(){
        return $this->haiten106;     // 配点106
    }
    public function getHaiten107(){
        return $this->haiten107;     // 配点107
    }
    public function getHaiten108(){
        return $this->haiten108;     // 配点108
    }
    public function getHaiten109(){
        return $this->haiten109;     // 配点109
    }
    public function getHaiten110(){
        return $this->haiten110;     // 配点110
    }
    public function getHaiten201(){
        return $this->haiten201;     // 配点201
    }
    public function getHaiten202(){
        return $this->haiten202;     // 配点202
    }
    public function getHaiten203(){
        return $this->haiten203;     // 配点203
    }
    public function getHaiten204(){
        return $this->haiten204;     // 配点204
    }
    public function getHaiten205(){
        return $this->haiten205;     // 配点205
    }
    public function getHaiten206(){
        return $this->haiten206;     // 配点206
    }
    public function getHaiten207(){
        return $this->haiten207;     // 配点207
    }
    public function getHaiten208(){
        return $this->haiten208;     // 配点208
    }
    public function getHaiten209(){
        return $this->haiten209;     // 配点209
    }
    public function getHaiten210(){
        return $this->haiten210;     // 配点210
    }
    public function getHaiten301(){
        return $this->haiten301;     // 配点301
    }
    public function getHaiten302(){
        return $this->haiten302;     // 配点302
    }
    public function getHaiten303(){
        return $this->haiten303;     // 配点303
    }
    public function getHaiten304(){
        return $this->haiten304;     // 配点304
    }
    public function getHaiten305(){
        return $this->haiten305;     // 配点305
    }
    public function getHaiten306(){
        return $this->haiten306;     // 配点306
    }
    public function getHaiten307(){
        return $this->haiten307;     // 配点307
    }
    public function getHaiten308(){
        return $this->haiten308;     // 配点308
    }
    public function getHaiten309(){
        return $this->haiten309;     // 配点309
    }
    public function getHaiten310(){
        return $this->haiten310;     // 配点310
    }
    public function getBunyaMei1(){
        return $this->bunyaMei1;     // 分野名1
    }
    public function getBunyaMei2(){
        return $this->bunyaMei2;     // 分野名2
    }
    public function getBunyaMei3(){
        return $this->bunyaMei3;     // 分野名3
    }
    public function getBunyaMei4(){
        return $this->bunyaMei4;     // 分野名4
    }
    public function getBunyaMei5(){
        return $this->bunyaMei5;     // 分野名5
    }
    public function getMondai101(){
        return $this->mondai101;     // 問題101
    }
    public function getMondai102(){
        return $this->mondai102;     // 問題102
    }
    public function getMondai103(){
        return $this->mondai103;     // 問題103
    }
    public function getMondai104(){
        return $this->mondai104;     // 問題104
    }
    public function getMondai105(){
        return $this->mondai105;     // 問題105
    }
    public function getMondai106(){
        return $this->mondai106;     // 問題106
    }
    public function getMondai107(){
        return $this->mondai107;     // 問題107
    }
    public function getMondai108(){
        return $this->mondai108;     // 問題108
    }
    public function getMondai109(){
        return $this->mondai109;     // 問題109
    }
    public function getMondai110(){
        return $this->mondai110;     // 問題110
    }
    public function getMondai201(){
        return $this->mondai201;     // 問題201
    }
    public function getMondai202(){
        return $this->mondai202;     // 問題202
    }
    public function getMondai203(){
        return $this->mondai203;     // 問題203
    }
    public function getMondai204(){
        return $this->mondai204;     // 問題204
    }
    public function getMondai205(){
        return $this->mondai205;     // 問題205
    }
    public function getMondai206(){
        return $this->mondai206;     // 問題206
    }
    public function getMondai207(){
        return $this->mondai207;     // 問題207
    }
    public function getMondai208(){
        return $this->mondai208;     // 問題208
    }
    public function getMondai209(){
        return $this->mondai209;     // 問題209
    }
    public function getMondai210(){
        return $this->mondai210;     // 問題210
    }
    public function getMondai301(){
        return $this->mondai301;     // 問題301
    }
    public function getMondai302(){
        return $this->mondai302;     // 問題302
    }
    public function getMondai303(){
        return $this->mondai303;     // 問題303
    }
    public function getMondai304(){
        return $this->mondai304;     // 問題304
    }
    public function getMondai305(){
        return $this->mondai305;     // 問題305
    }
    public function getMondai306(){
        return $this->mondai306;     // 問題306
    }
    public function getMondai307(){
        return $this->mondai307;     // 問題307
    }
    public function getMondai308(){
        return $this->mondai308;     // 問題308
    }
    public function getMondai309(){
        return $this->mondai309;     // 問題309
    }
    public function getMondai310(){
        return $this->mondai310;     // 問題310
    }
    public function getJukenBangou2(){
        return $this->jukenBangou2;  // （受験番号）
    }
    public function getHyoukaSougou(){
        return $this->hyoukaSougou;  // 評価総合
    }
    public function getHyouka1(){
        return $this->hyouka1;       // 評価1
    }
    public function getHyouka2(){
        return $this->hyouka2;       // 評価2
    }
    public function getHyouka3(){
        return $this->hyouka3;       // 評価3
    }
    public function getGouhiHantei(){
        return $this->gouhiHantei;   // 合否判定
    }
    public function getTokutenSougou(){
        return $this->tokutenSougou; // 得点総合
    }
    public function getTokuten1(){
        return $this->tokuten1;      // 得点1
    }
    public function getTokuten2(){
        return $this->tokuten2;      // 得点2
    }
    public function getTokuten3(){
        return $this->tokuten3;      // 得点3
    }
    public function getTokuten101(){
        return $this->tokuten101;    // 得点101
    }
    public function getTokuten102(){
        return $this->tokuten102;    // 得点102
    }
    public function getTokuten103(){
        return $this->tokuten103;    // 得点103
    }
    public function getTokuten104(){
        return $this->tokuten104;    // 得点104
    }
    public function getTokuten105(){
        return $this->tokuten105;    // 得点105
    }
    public function getTokuten106(){
        return $this->tokuten106;    // 得点106
    }
    public function getTokuten107(){
        return $this->tokuten107;    // 得点107
    }
    public function getTokuten108(){
        return $this->tokuten108;    // 得点108
    }
    public function getTokuten109(){
        return $this->tokuten109;    // 得点109
    }
    public function getTokuten110(){
        return $this->tokuten110;    // 得点110
    }
    public function getTokuten201(){
        return $this->tokuten201;    // 得点201
    }
    public function getTokuten202(){
        return $this->tokuten202;    // 得点202
    }
    public function getTokuten203(){
        return $this->tokuten203;    // 得点203
    }
    public function getTokuten204(){
        return $this->tokuten204;    // 得点204
    }
    public function getTokuten205(){
        return $this->tokuten205;    // 得点205
    }
    public function getTokuten206(){
        return $this->tokuten206;    // 得点206
    }
    public function getTokuten207(){
        return $this->tokuten207;    // 得点207
    }
    public function getTokuten208(){
        return $this->tokuten208;    // 得点208
    }
    public function getTokuten209(){
        return $this->tokuten209;    // 得点209
    }
    public function getTokuten210(){
        return $this->tokuten210;    // 得点210
    }
    public function getTokuten301(){
        return $this->tokuten301;    // 得点301
    }
    public function getTokuten302(){
        return $this->tokuten302;    // 得点302
    }
    public function getTokuten303(){
        return $this->tokuten303;    // 得点303
    }
    public function getTokuten304(){
        return $this->tokuten304;    // 得点304
    }
    public function getTokuten305(){
        return $this->tokuten305;    // 得点305
    }
    public function getTokuten306(){
        return $this->tokuten306;    // 得点306
    }
    public function getTokuten307(){
        return $this->tokuten307;    // 得点307
    }
    public function getTokuten308(){
        return $this->tokuten308;    // 得点308
    }
    public function getTokuten309(){
        return $this->tokuten309;    // 得点309
    }
    public function getTokuten310(){
        return $this->tokuten310;    // 得点310
    }
}