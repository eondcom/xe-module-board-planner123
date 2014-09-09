<?php
/**
## @Package:    xe_official_planner123 (board skin)
## @File name:	class.planner123_main.php
## @Author:     Keysung Chung (keysung2004@yahoo.co.kr)
## @Copyright:  © 2009 Keysung Chung(keysung2004@yahoo.co.kr)
## @Contributors: Clements J. SONG (http://clements.kyunggi.ca/ , clements_song@hotmail.com)
## @Release:	under GPL-v2 License.
## @License:	http://www.opensource.org/licenses/gpl-2.0.php
##
## Redistribution and use in source and binary forms, with or without modification, 
## are permitted provided that the following conditions are met:
## 
## Redistributions of source code must retain the above copyright notice, this list of 
## conditions and the following disclaimer.
## Redistributions in binary form must reproduce the above copyright notice, this list 
## of conditions and the following disclaimer in the documentation and/or other materials 
## provided with the distribution.
##
## Neither the name of the author nor the names of contributors may be used to endorse 
## or promote products derived from this software without specific prior written permission.
## 
## THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY 
## EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
## MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
## COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
## EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
## GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED 
## AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
## NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
## OF THE POSSIBILITY OF SUCH DAMAGE.
##
## [author]
##  - Keysung Chung, 2009, 07, 28
##  - http://chungfamily.woweb.net/
## [changes]
##  - 2010.10.15 : Solar class 제거.
##  - 2010.10.10 : Class로 변경.
##  - 2010.09.10 : 휴일및 기념일 함수 분리함.
##  - 2009.12.20 : new build
**/

class planner123_main extends Object
{

//--------------------------------------------------------------------------------
    /**
     * @file   function_standard.php  planner_standard용 function: function_calendar_month.php 소스
     * @author keysung Chung
     * @brief  calender function
     **/

//--------------------------------------------------------------------------------
    /**
     * @function: fn_leapmonth($pYear)
     * @return:   boolean
     * @brief:    그해의 윤달 여부 (윤년)
     **/
function fn_leapmonth($pYear){

    /*연도를 100으로 나눠떨어지지 않으면서 4로 나누어 떨어지면 윤달있음.
     *또는
     *연도를 100으로 나눠떨어지는 경우는 연도를 400으로 나눠떨어지면 윤달있음.
    **/

    if (($pYear % 100 <> 0 && $pYear % 4 == 0) or $pYear % 400 == 0) {
        $fn_leapmonth = true;
    }
    else {
        $fn_leapmonth = false;
    }
    return $fn_leapmonth;

    // 또는 단순히 date()를 이용 하거나....("L" : 윤년여부 윤년엔 1, 그 외엔 0)
    // return date("L", mktime(0, 0, 0, $pMonth, 1, $pYear));
}

//--------------------------------------------------------------------------------

    /**
     * @function: fn_monthcount($pYear,$pMonth)
     * @return  : integer
     * @brief:    해당월의 마지막 일을 반환한다
     **/
function fn_monthcount($pYear,$pMonth) {
    $aMonthNum = explode("-","31-0-31-30-31-30-31-31-30-31-30-31-");
     //2월달은 예외

    if (planner123_main::fn_leapmonth($pYear)) {
        $aMonthNum[1] = 29;
    }
    else {
        $aMonthNum[1] = 28;
    }
//    return $aMonthNum;
    return $aMonthNum[$pMonth-1];

    // 또는 단순히 date()를 이용 하거나....("t" : 주어진 월의 일수)
    // return date("t", mktime(0, 0, 0, $pMonth, 1, $pYear));
}

//--------------------------------------------------------------------------------

    /**
     * @function: fn_firstweek($pYear,$pMonth)
     * @return  : integer
     * @brief:    해당년/월의 첫번째일의 위치를 반환 ("w" : 0=일요일, 6=토요일)
     **/
function fn_firstweek($pYear,$pMonth) {
    return date("w", mktime(0, 0, 0,$pMonth, 1, $pYear));
}

//--------------------------------------------------------------------------------

    /**
     * @function: fn_nowweek($pYear,$pMonth,$pDay)
     * @return  : integer
     * @brief:    해당년/월/일의 요일 위치를 반환 ("w" : 0=일요일, 6=토요일)
     **/
function fn_nowweek($pYear,$pMonth,$pDay) {
    return date("w", mktime(0, 0, 0,$pMonth, $pDay, $pYear));
}

//------------------------------------------------------------------------------
    /**
     * @function: fn_lastweek($pYear,$pMonth)
     * @return  : integer
     * @brief:    해당년/월의 마지막날 위치를 반환  ("w" : 0=일요일, 6=토요일)
     **/
function fn_lastweek($pYear,$pMonth) {
    return date("w", mktime(0, 0, 0,$pMonth, planner123_main::fn_monthcount($pYear,$pMonth), $pYear));
}
//-----------------------------------------------------------------------------------
    /**
     * @function: fn_blankweekfirst($pYear,$pMonth)
     * @return  : integer
     * @brief:    해당년/월의 첫번째주 빈값을 구한다.
     * @brief:    해당 년/월의 일수시작이 수요일(3) 이라면 일(0)/월(1)/화(2) 즉 3개는 빈값이다.
     **/
function fn_blankweekfirst($pYear,$pMonth) {
    return planner123_main::fn_firstweek($pYear,$pMonth);
}

//--------------------------------------------------------------------------------------

    /**
     * @function: fn_blankweeklast($pYear,$pMonth)
     * @return  : integer
     * @brief:    해당년/월의 마지막주 빈값을 구한다.
     * @brief:    해당 년/월의 일수끝이 목요일(4) 이라면 금(5)/토(6) 즉 2개는 빈값이다.
     **/
function fn_blankweeklast($pYear,$pMonth) {
    return 6 - planner123_main::fn_lastweek($pYear,$pMonth);
}

//--------------------------------------------------------------------------------------

    /**
     * @function: fn_weekcountofmonth($pYear,$pMonth,$pDay)
     * @return  : integer
     * @brief:    해당 년/월/일이 당월 몇번째 주에 해당 되는지를 구한다.
     * @brief:    해당 년/월/일이 당월 2번째 주에 포함된다면 2를 반환.
     **/
function fn_weekcountofmonth($pYear,$pMonth,$pDay) {
    $wrkday = $pDay + date("w", mktime(0, 0, 0,$pMonth, 1, $pYear)); //1일의 요일번호(일=0, 토=6)
    $weekcount = floor($wrkday/7);  //소숫점이하 절사
    if ( $wrkday % 7 > 0 ) {
        $weekcount = $weekcount + 1;
    }
    return $weekcount;      // n번째 주
}

//--------------------------------------------------------------------------------------

    /**
     * @function: fn_weekdaycountofmonth($pYear,$pMonth,$pDay)
     * @return  : integer
     * @brief:    해당 년/월/일의 요일이 당월 몇번째 요일에 해당되는지 구한다.
     * @brief:    해당 년/월/일의 요일이 당월 2번째요일 이면 2를 반환.
     **/
function fn_weekdaycountofmonth($pYear,$pMonth,$pDay) {
    $k=0;       // 카운터
    $pYoil = date("w", mktime(0, 0, 0,$pMonth, $pDay, $pYear)); //해당일의 요일번호(일=0, 토=6)

    for ($i=1; $i<=$pDay; $i++) {                               // 1일 부터 말일까지 수행
        $wrk1 = date("w", mktime(0, 0, 0,$pMonth, $i, $pYear));
        if ($wrk1 == $pYoil) {              // 요일 일치
            $k=$k+1;
        }
    }
    return $k;      // n번째 요일
}

//--------------------------------------------------------------------------------------

    /**
     * @function: fn_nsweekday($pYear, $pMonth, $pCount, $pYoil)
     * @return  : string
     * @brief:    해당년/월 n번째 x요일의 일자를 구한다
     * @brief:     pCount: 숫자, pYoil: 숫자 (일요일(0) ... 토요일(6)).
     **/
function fn_nsweekday($pYear, $pMonth, $pCount, $pYoil) {
    $k=0;       // 카운터
    $j = date("t", mktime(0, 0, 0, $pMonth, 1, $pYear));    // 해당월의 날자수(말일) 값
    for ($i=1; $i<=$j; $i++) {                              // 1일 부터 말일까지 수행
        $wrk1 = date("w", mktime(0, 0, 0,$pMonth, $i, $pYear));
        if ($wrk1 == $pYoil) {              // 요일 일치
            $k=$k+1;
            if ($k == $pCount) {            // 횟수 일치
                $wrkYmd =date("Y-n-j", mktime(0, 0, 0,$pMonth, $i, $pYear));
            }
        }
    }
    return $wrkYmd;
}

//--------------------------------------------------------------------------------------

    /**
     * @function: fn_nsweeknsweekday($pYear, $pMonth, $pCount, $pYoil)
     * @return  : string
     * @brief:    해당년/월 n번째 주 x요일의 일자를 구한다
     * @brief:    pCount: 숫자, pYoil: 숫자 (일요일(0) ... 토요일(6)).
     **/
function fn_nsweeknsweekday($pYear, $pMonth, $pCount, $pYoil) {
    $k=1;       //  주 카운터
    $j = date("t", mktime(0, 0, 0, $pMonth, 1, $pYear));    // 해당월의 날자수(말일) 값
    for ($i=1; $i<=$j; $i++) {                              // 1일 부터 말일까지 수행
        $wrk1 = date("w", mktime(0, 0, 0,$pMonth, $i, $pYear)); // 요일
        if ($i != 1 && $wrk1==0) {         // 첫날이 아니면서 일요일 이면 주 카운터 증가
            $k = $k + 1;
        }
        if ($wrk1 == $pYoil) {              // 요일 일치
            if ($k == $pCount) {            // 횟수 일치
                $wrkYmd =date("Y-n-j", mktime(0, 0, 0,$pMonth, $i, $pYear));
            }
        }
    }
    return $wrkYmd;
}

//--------------------------------------------------------------------------------------

    /**
     * @function: fn_weekdaycountofmonth_end($pYear,$pMonth,$pDay)
     * @return  : integer
     * @brief:    해당 년/월/일의 요일이 당월 끝에서 몇번째 요일에 해당되는지 요일차를 구한다.
     * @brief:    해당 년/월/일의 요일이 당월 끝에서 2번째요일 이면 2를 반환.
     **/
function fn_weekdaycountofmonth_end($pYear,$pMonth,$pDay) {
    $k=0;       // 카운터
    $pYoil = date("w", mktime(0, 0, 0,$pMonth, $pDay, $pYear)); //해당일의 요일번호(일=0, 토=6)
    $j = date("t", mktime(0, 0, 0, $pMonth, 1, $pYear));    // 해당월의 날자수(말일) 값
    for ($i=$j; $i>=$pDay; $i--) {                          // 말일 부터 당일까지 수행
        $wrk1 = date("w", mktime(0, 0, 0,$pMonth, $i, $pYear));
        if ($wrk1 == $pYoil) {              // 요일 일치
            $k=$k+1;
        }
    }
    return $k;      // n번째 요일
}

//--------------------------------------------------------------------------------------

    /**
     * @function: fn_nslastweekday($pYear, $pMonth, $pCount, $pYoil)
     * @return  : string
     * @brief:    해당년/월 끝에서 n번째 x요일의 일자를 구한다
     * @brief:     pCount: 숫자, pYoil: 숫자 (일요일(0) ... 토요일(6)).
     **/
function fn_nslastweekday($pYear, $pMonth, $pCount, $pYoil) {
    $k=0;       //  주 카운터
    $j = date("t", mktime(0, 0, 0, $pMonth, 1, $pYear));    // 해당월의 날자수(말일) 값
    for ($i=$j; $i>=1; $i--) {                              // 말일 부터 1일까지 수행
        $wrk1 = date("w", mktime(0, 0, 0,$pMonth, $i, $pYear)); // 요일
        if ($wrk1 == $pYoil) {              // 요일 일치
            $k = $k + 1;
            if ($k == $pCount) {            // 횟수 일치
                $wrkYmd =date("Y-n-j", mktime(0, 0, 0,$pMonth, $i, $pYear));
            }
        }
    }
    return $wrkYmd;
}

//--------------------------------------------------------------------------------
    /**
     * @function: fn_CalMain($pYear,$pMonth)
     * @return  : array
     * @brief:    주어진 년/월의 달력을 만든다.
     * @brief:     2차원배열을 사용하여 틀을 만든다.
     * @brief:     가로(1주)는 무조건 7이 되므로 세로값만 알면 된다.
     * @brief:     빈칸은 null 값으로한다
     * @brief:     형태예제
     * @brief:     |일|월|화|수|목|금|토|
     * @brief:     | n| n| n| n| n| n| 1|
     * @brief:     | 2| 3| 4| 5| 6| 7| 8|
     * @brief:     | 9|10|11|12|13|14|15|
     * @brief:     |16|17|18|19|20|21|22|
     * @brief:     |23|24|25|26|27|28|29|
     * @brief:     |30|31| n| n| n| n| n|
     **/
function fn_CalMain($pYear,$pMonth) {

    //$aCal[][];       //달력의 틀을 위한 2차원배열
    //$intVertical;  //세로줄값
    //$intWeekcnt;   //주일수
    //$i;
    //$j;
    //$k;            //루프전체 합
    //$intDay;       //일수 값

    //초기값 셋팅
    $k=0;
    $intDay=1;      //일

    //세로값얻는 방법 (그달의 일수 + 첫째주빈값 + 마지막주빈값)/7=세로값
    $intVertical = (planner123_main::fn_monthcount($pYear,$pMonth)+planner123_main::fn_blankweekfirst($pYear,$pMonth)+planner123_main::fn_blankweeklast($pYear,$pMonth))/7;
    $intWeekcnt = 7;

    //배열셋팅
    // array[세로사이즈][가로사이즈]
    $aCal[$intVertical][$intWeekcnt];

    //배열에 값 삽입
    for ($i=0; $i<$intVertical; $i++ ) {
        for ($j=0; $j<$intWeekcnt; $j++ ){
            $k=$k+1;
            //k의값이 첫번째주 빈값보다 작거나 같을경우는 *을 삽입
            if ($k<=planner123_main::fn_blankweekfirst($pYear,$pMonth)) {
                $aCal[$i][$j] = "*";
            }
            //k의값이 첫번째주빈값이상이며, 일자가 해당월의 마지막 일자 값과 작거나같을경우는 일수를 삽입
            else {
                if ($intDay<=planner123_main::fn_monthcount($pYear,$pMonth)) {
                $aCal[$i][$j] = $intDay;
                $intDay = $intDay+1;
                }

                //이외의 값은 *로 채운다
                else {
                    $aCal[$i][$j] = "*";
                }
            }
        }
    }
    return $aCal;
}

//--------------------------------------------------------------------------------------

    /**
     * @function: fn_smallcalendar()
     * @return  : string
     * @brief:    소형 당월 칼런다 HTML코드 출력
     * @brief:
     **/

function fn_smallcalendar(){
    $year = date("Y");
    $month = date("n");
    $day = date("d");
    $day_max = date("t",mktime(0,0,0,$month,1,$year));
    $week_start = date("w",mktime(0,0,0,$month,1,$year));
    $i = 0;
    $j = 0;
    $html = "<div class='calendar_box'><div class='calendar_title B'>".sprintf("%d-%02d-%02d",$year,$month,$day)."</div>";
    while ($j<$day_max){
        if ($i<$week_start) {
            $html .= "<div class='calendar_text'>·</div>";
        } else {
            if ($i%7==0) $font_color = " RED";
            else if ($i%7==6) $font_color = " BLUE";
            else $font_color = "";
            if ($day == ($j+1)) $font_weight = " B"; else $font_weight = "";
            $html .= "<div class='calendar_text$font_color$font_weight'>" . ($j+1) . "</div>";
            $j ++;
        }
        $i ++;
    }
    while ($i%7!==0){
        $html .= "<div class='calendar_text'>·</div>";
        $i ++;
    }
    $html .= "<div class='calendar_tail'></div></div>";
    return $html;
}


//--------------------------------------------------------------------------------------
    /**
     * @function: fn_sunlunar_data()
     * @return  : string(13자리-13자리-... 170개)
     * @brief:    음력-양력 변환위한 자료
     **/
function fn_sunlunar_data() {
return "1212122322121-1212121221220-1121121222120-2112132122122-2112112121220-2121211212120-2212321121212-2122121121210-2122121212120-1232122121212-1212121221220-1121123221222-1121121212220-1212112121220-2121231212121-2221211212120-1221212121210-2123221212121-2121212212120-1211212232212-1211212122210-2121121212220-1212132112212-2212112112210-2212211212120-1221412121212-1212122121210-2112212122120-1231212122212-1211212122210-2121123122122-2121121122120-2212112112120-2212231212112-2122121212120-1212122121210-2132122122121-2112121222120-1211212322122-1211211221220-2121121121220-2122132112122-1221212121120-2121221212110-2122321221212-1121212212210-2112121221220-1231211221222-1211211212220-1221123121221-2221121121210-2221212112120-1221241212112-1212212212120-1121212212210-2114121212221-2112112122210-2211211412212-2211211212120-2212121121210-2212214112121-2122122121120-1212122122120-1121412122122-1121121222120-2112112122120-2231211212122-2121211212120-2212121321212-2122121121210-2122121212120-1212142121212-1211221221220-1121121221220-2114112121222-1212112121220-2121211232122-1221211212120-1221212121210-2121223212121-2121212212120-1211212212210-2121321212221-2121121212220-1212112112210-2223211211221-2212211212120-1221212321212-1212122121210-2112212122120-1211232122212-1211212122210-2121121122210-2212312112212-2212112112120-2212121232112-2122121212110-2212122121210-2112124122121-2112121221220-1211211221220-2121321122122-2121121121220-2122112112322-1221212112120-1221221212110-2122123221212-1121212212210-2112121221220-1211231212222-1211211212220-1221121121220-1223212112121-2221212112120-1221221232112-1212212122120-1121212212210-2112132212221-2112112122210-2211211212210-2221321121212-2212121121210-2212212112120-1232212122112-1212122122110-2121212322122-1121121222120-2112112122120-2211231212122-2121211212120-2122121121210-2124212112121-2122121212120-1212121223212-1211212221220-1121121221220-2112132121222-1212112121220-2121211212120-2122321121212-1221212121210-2121221212120-1232121221212-1211212212210-2121123212221-2121121212220-1212112112220-1221231211221-2212211211220-1212212121210-2123212212121-2112122122120-1211212322212-1211212122210-2121121122120-2212114112122-2212112112120-2212121211210-2212232121211-2122122121210-2112122122120-1231212122212-1211211221220-2121121321222-2121121121220-2122112112120-2122141211212-1221221212110-2121221221210-2114121221221";   //1881-2050
}

//------------------------------------------------------------------------

    /**
     * @function: fn_lun2sol($pYear,$pMonth,$pDay)
     * @return  : string(yyyy-mm-dd-요일)
     * @brief:    음력을 양력으로 변환하는 함수
     **/
function fn_lun2sol($pYear,$pMonth,$pDay) {
$getYEAR = (int)$pYear;
$getMONTH = (int)$pMonth;
$getDAY = (int)$pDay;
//$getYEAR = (int)substr($yyyymmdd,0,4);
//$getMONTH = (int)substr($yyyymmdd,4,2);
//$getDAY = (int)substr($yyyymmdd,6,2);

$arrayDATASTR = planner123_main::fn_sunlunar_data();
$arrayDATA = explode("-",$arrayDATASTR);

$arrayLDAYSTR="31-0-31-30-31-30-31-31-30-31-30-31";
$arrayLDAY = explode("-",$arrayLDAYSTR);

$arrayYUKSTR="갑-을-병-정-무-기-경-신-임-계";
$arrayYUK = explode("-",$arrayYUKSTR);

$arrayGAPSTR="자-축-인-묘-진-사-오-미-신-유-술-해";
$arrayGAP = explode("-",$arrayGAPSTR);

$arrayDDISTR="쥐-소-호랑이-토끼-용-뱀-말-양-원숭이-닭-개-돼지";
$arrayDDI = explode("-",$arrayDDISTR);

$arrayWEEKSTR="일-월-화-수-목-금-토";
$arrayWEEK = explode("-",$arrayWEEKSTR);

if ($getYEAR <= 1881 || $getYEAR >= 2050) { //년수가 해당일자를 넘는 경우
$YunMonthFlag = 0;
return false; //년도 범위가 벗어남..
}
if ($getMONTH > 12) { // 달수가 13이 넘는 경우
$YunMonthFlag = 0;
return false; //달수 범위가 벗어남..
}
$m1 = $getYEAR - 1881;
if (substr($arrayDATA[$m1],12,1) == 0) { // 윤달이 없는 해임
$YunMonthFlag = 0;
} else {
if (substr($arrayDATA[$m1],$getMONTH, 1) > 2) {
$YunMonthFlag = 1;
} else {
$YunMonthFlag = 0;
}
}
//-------------
$m1 = -1;
$td = 0;

if ($getYEAR > 1881 && $getYEAR < 2050) {
$m1 = $getYEAR - 1882;
for ($i=0;$i<=$m1;$i++) {
for ($j=0;$j<=12;$j++) {
$td = $td + (substr($arrayDATA[$i],$j,1));
}
if (substr($arrayDATA[$i],12,1) == 0) {
$td = $td + 336;
} else {
$td = $td + 362;
}
}
} else {
$gf_lun2sol = 0;
}

$m1++;
$n2 = $getMONTH - 1;
$m2 = -1;

while(1) {
$m2++;
if (substr($arrayDATA[$m1], $m2, 1) > 2) {
$td = $td + 26 + (substr($arrayDATA[$m1], $m2, 1));
$n2++;
} else {
if ($m2 == $n2) {
if ($gf_yun) {
$td = $td + 28 + (substr($arrayDATA[$m1], $m2, 1));
}
break;
} else {
$td = $td + 28 + (substr($arrayDATA[$m1], $m2, 1));
}
}
}

$td = $td + $getDAY + 29;
$m1 = 1880;
while(1) {
$m1++;
if ($m1 % 400 == 0 || $m1 % 100 != 0 && $m1 % 4 == 0) {
$leap = 1;
} else {
$leap = 0;
}

if ($leap == 1) {
$m2 = 366;
} else {
$m2 = 365;
}

if ($td < $m2) break;

$td = $td - $m2;
}
$syear = $m1;
$arrayLDAY[1] = $m2 - 337;

$m1 = 0;

while(1) {
$m1++;
if ($td <= $arrayLDAY[$m1-1]) {
break;
}
$td = $td - $arrayLDAY[$m1-1];
}
$smonth = $m1;
$sday = $td;
$y = $syear - 1;
$td = intval($y*365) + intval($y/4) - intval($y/100) + intval($y/400);

if ($syear % 400 == 0 || $syear % 100 != 0 && $syear % 4 == 0) {
$leap = 1;
} else {
$leap = 0;
}

if ($leap == 1) {
$arrayLDAY[1] = 29;
} else {
$arrayLDAY[1] = 28;
}
for ($i=0;$i<=$smonth-2;$i++) {
$td = $td + $arrayLDAY[$i];
}
$td = $td + $sday;
$w = $td % 7;

$sweek = $arrayWEEK[$w];
$gf_lun2sol = 1;

return($syear."-".$smonth."-".$sday."-".$sweek."-".$YunMonthFlag);
}

//--------------------------------------------------------------------------------------

    /**
     * @function: fn_sol2lun($pYear,$pMonth,$pDay)
     * @return  : string(yyyy-mm-dd-간지-띠)
     * @brief:    양력을 음력으로 변환하는 함수
     **/
function fn_sol2lun($pYear,$pMonth,$pDay) {
$getYEAR = $pYear;
$getMONTH = $pMonth;
$getDAY = $pDay;
//$getYEAR = (int)substr($yyyymmdd,0,4);
//$getMONTH = (int)substr($yyyymmdd,4,2);
//$getDAY = (int)substr($yyyymmdd,6,2);

$arrayDATASTR = planner123_main::fn_sunlunar_data();
$arrayDATA = explode("-",$arrayDATASTR);

$arrayLDAYSTR="31-0-31-30-31-30-31-31-30-31-30-31";
$arrayLDAY = explode("-",$arrayLDAYSTR);

$arrayYUKSTR="갑-을-병-정-무-기-경-신-임-계";
$arrayYUK = explode("-",$arrayYUKSTR);

$arrayGAPSTR="자-축-인-묘-진-사-오-미-신-유-술-해";
$arrayGAP = explode("-",$arrayGAPSTR);

$arrayDDISTR="쥐-소-호랑이-토끼-용-뱀-말-양-원숭이-닭-개-돼지";
$arrayDDI = explode("-",$arrayDDISTR);

$arrayWEEKSTR="일-월-화-수-목-금-토";
$arrayWEEK = explode("-",$arrayWEEKSTR);

$dt = $arrayDATA;

for ($i=0;$i<=168;$i++) {
    $dt[$i] = 0;
    for ($j=0;$j<12;$j++) {
        switch (substr($arrayDATA[$i],$j,1)) {
        case 1:
        $dt[$i] += 29;
        break;
        case 3:
        $dt[$i] += 29;
        break;
        case 2:
        $dt[$i] += 30;
        break;
        case 4:
        $dt[$i] += 30;
        break;
        }
    }

    switch (substr($arrayDATA[$i],12,1)) {
    case 0:
    break;
    case 1:
    $dt[$i] += 29;
    break;
    case 3:
    $dt[$i] += 29;
    break;
    case 2:
    $dt[$i] += 30;
    break;
    case 4:
    $dt[$i] += 30;
    break;
    }
}


$td1 = 1880 * 365 + (int)(1880/4) - (int)(1880/100) + (int)(1880/400) + 30;
$k11 = $getYEAR - 1;

$td2 = $k11 * 365 + (int)($k11/4) - (int)($k11/100) + (int)($k11/400);

if ($getYEAR % 400 == 0 || $getYEAR % 100 != 0 && $getYEAR % 4 == 0) {
    $arrayLDAY[1] = 29;
    }
    else {
    $arrayLDAY[1] = 28;
    }

if ($getMONTH > 13) {
    $gf_sol2lun = 0;
}

if ($getDAY > $arrayLDAY[$getMONTH-1]) {
    $gf_sol2lun = 0;
}

for ($i=0;$i<=$getMONTH-2;$i++) {
    $td2 += $arrayLDAY[$i];
}

$td2 += $getDAY;
$td = $td2 - $td1 + 1;
$td0 = $dt[0];

for ($i=0;$i<=168;$i++) {
    if ($td <= $td0) {
    break;
    }
    $td0 += $dt[$i+1];
}

$ryear = $i + 1881;
$td0 -= $dt[$i];
$td -= $td0;

if (substr($arrayDATA[$i], 12, 1) == 0) {
    $jcount = 11;
    }
    else {
        $jcount = 12;
    }
$m2 = 0;

for ($j=0;$j<=$jcount;$j++) { // 달수 check, 윤달 > 2 (by harcoon)
    if (substr($arrayDATA[$i],$j,1) <= 2) {
        $m2++;
        $m1 = substr($arrayDATA[$i],$j,1) + 28;
        $gf_yun = 0;
        $yundal = null;     // add ksc
    }
    else {
        $m1 = substr($arrayDATA[$i],$j,1) + 26;
        $gf_yun = 1;
        $yundal = "윤달"; // add ksc
    }
    if ($td <= $m1) {
    break;
    }
    $td = $td - $m1;
}

$k1=($ryear+6) % 10;
$syuk = $arrayYUK[$k1];
$k2=($ryear+8) % 12;
$sgap = $arrayGAP[$k2];
$sddi = $arrayDDI[$k2];

$gf_sol2lun = 1;

return ($ryear."-".$m2."-".$td."-".$syuk.$sgap."년-".$sddi."띠-".$yundal);
}

//------------------------------------------------------------------------
    /**
     * @function: fn_sol2lun_ary($pYear, $pMonth)
     * @return  : array
     * @brief:    년간 양력 일자에 대응되는 음력일자 어레이 리턴
     **/
Function fn_sol2lun_ary($pYear, $pMonth) {
    /******************************************************
    *년간 양력 일자에 대응되는 음력일자 어레이 (예:2009-7-15-윤달)
    *******************************************************/

//  For ($i = 0; $i<13; $i++) {
//      For ($j = 0; $j <32; $j++ ) {
//          $aHoli[$i][$j] = null;
//      }
//  }
    $aHoli = null;

    for ($i=$pMonth; $i < $pMonth+1; $i++ ) {   //당월만
        for ($j=0; $j < 32; $j++ ) {
            if ($i > 0 && $j>0 && $j < 32) {      // 1-12
                $temp01 = explode("-",planner123_main::fn_sol2lun($pYear,$i,$j));
                $iSunYmd =$temp01[0]."-".$temp01[1]."-".$temp01[2];

                if ($temp01[5] != null) {   //윤달일 경우 - 붙여서 출력
                $Yundal = "-".$temp01[5];
                }
                else {
                    $Yundal = null;
                }

                $aHoli[$i][$j] = $iSunYmd.$Yundal;
            }
        }
    }
    return $aHoli;
}


//------------------------------------------------------------------------
    /**
     * @function: fn_ganji_ary($pYear, $pMonth, $pDay)
     * @return  : array
     * @brief:    간지가 새로 시작되는 새해 시작일을 입력받아 양력에 대응되는 세차, 일진 어레이 리턴
     * @brief:    유효기간 1902 - 2037
     **/
Function fn_ganji_ary($pYear, $pMonth, $pDay) {
    //*****************************************************
    // 년간 양력 일자에 대응되는 간지 어레이 (세차, 일진) 계산
    //
    // 새해의 시작을 구분 할때 사람에 따라 이론이 있으니 아래 내용 참고 하세요.
    //
    // "주역을 하시는 분들은 새해의 시작을 동지로 보고,
    // 명리학이나 사주를 보는 분들은 새해의 시작을 입춘으로 보는것 같습니다만,
    // 새해의 시작은 정월 초하루입니다."  - (한국천문연구원 홈페이지내 질문답변 게시판에서 발췌)
    //
    // 위와 같이 새해 시작점은 사람에 따라 기준점이 다르다고 합니다.
    //
    // 그러나, 기준일이 바뀌면 일자의 간지(일진)는 변하지 않지만 년의 간지(세차)와 월의 간지(월권)는
    // 변경이 되니 얼마나 난감한 일인지 모르겠습니다.
    // 사실 어느 기준을 적용하느냐에 따라 달라지는 자료(팔자)를 이용해 흔히들 사주를 본다거나 하는 일이
    // 조금은 황당하다는 생각도 듭니다.
    //
    // 그러나 간지는 우리 선조들이 계속 사용해 왔고 그중 일진은 과거 수천년 동안 그 주기가 변하지 않고
    // 계속 이어져 내려 왔다고 하니 나름 매우 중요한 자료라고 생각 합니다.
    //
    // 본 함수는 날짜를 고정 하지 않고 호출시 넘어온 일자를 새해 시작일로 하여 세차와 일진을 계산 합니다.
    // 넘어온 날이 8월 보다 적을 경우는 설날과, 입춘이라고 가정 햇습니다.
    //
    // 또 고려 할 다른 문제점은 입춘이나 동지를 시작일로 할경우는 입춘이나 동지 일자를 강제로 지정하지 않고
    // 계산을 할 경우(본 함수) 그 계산이 정확해야 되는데 정확한 24절기 계산이 쉬운일이 아니라고 합니다.
    // 현재 구할 수 있는 24절기 계산 함수들은 천체 운동 계산에 약간의 오차가 있는듯 하며,
    // 그 결과 24절기가 간혹 하루 정도 차이가 나는 경우가 있음을 염두에 두시고 이용 하시기 바랍니다.
    //(실제 계산상 차이가 몇 시간 이라고 해도 이것이 24:00시 기준 전날인지 다음날 인지에 따라서도 날자가 바뀜니다.)
    //
    //참고로 한국천문연구원이 게시한 24절기는 http://www.kasi.re.kr/Knowledge/almanac.aspx 에서 확인할 수 있습니다.
    //******************************************************

    if ($pYear<1902 || $pYear>2037) {       // 유효기간 1902-2037
        return;
    }

//  F|| ($i = 0; $i<13; $i++) {
//      For ($j = 0; $j <32; $j++ ) {
//          $aHoli[$i][$j] = null;
//      }
//  }
$aHoli = null;

$arr_gan = array("甲","乙","丙","丁","戊","己","庚","辛","壬","癸");
$arr_ji =  array("子","丑","寅","卯","辰","巳","午","未","申","酉","戌","亥");

$arr_ganji =  array('甲子','乙丑','丙寅','丁卯','戊辰','己巳','庚午','辛未','壬申','癸酉','甲戌','乙亥',
                '丙子','丁丑','戊寅','己卯','庚辰','辛巳','壬午','癸未','甲申','乙酉','丙戌','丁亥',
                '戊子','己丑','庚寅','辛卯','壬辰','癸巳','甲午','乙未','丙申','丁酉','戊戌','己亥',
                '庚子','辛丑','壬寅','癸卯','甲辰','乙巳','丙午','丁未','戊申','己酉','庚戌','辛亥',
                '壬子','癸丑','甲寅','乙卯','丙辰','丁巳','戊午','己未','庚申','辛酉','壬戌','癸亥');

// 월건을 위해 세차의 간지중 간만 출력을 위해
$arr_ganji_WG =  array('甲','乙','丙','丁','戊','己','庚','辛','壬','癸','甲','乙',
                '丙','丁','戊','己','庚','辛','壬','癸','甲','乙','丙','丁',
                '戊','己','庚','辛','壬','癸','甲','乙','丙','丁','戊','己',
                '庚','辛','壬','癸','甲','乙','丙','丁','戊','己','庚','辛',
                '壬','癸','甲','乙','丙','丁','戊','己','庚','辛','壬','癸');

    $baseYear = 1902;  // 1902년 1월 1일: 세차-"임인壬寅",  일진-"갑신甲申" 인데
                       // 어레이 arr_ganji 값으로 세차는 "38, 일진은 "20"에 해당

    $base_date = "1902-1-1";

// ---세차계산 (절기의 새해 시작점)---

    $k = ($pYear - $baseYear+38) % 60 ; // 60으로 나눈 나머지
                                        // 해당년의 세차를 arr_ganji 어레이에 맞게 조정 (38)

    if ($pMonth < 8 ) {             // 기준월 이 8월 보다 작은경우(설날과 입춘이 기준일경우)
        if ($k-1 < 0 ) {
            $Secha1=$arr_ganji_WG[59]."-".$arr_ganji[59];       // 기준일 이전의 세차
        }
        else {
            $Secha1=$arr_ganji_WG[$k-1]."-".$arr_ganji[$k-1];
        }

        $Secha2 =$arr_ganji_WG[$k]."-".$arr_ganji[$k];      // 기준일 부터의 세차

    }
    else {                              // 동지일 경우
        if ($k+1 > 59 ) {
            $Secha2=$arr_ganji_WG[0]."-".$arr_ganji[0];     // 기준일 부터의 세차
        }
        else {
            $Secha2=$arr_ganji_WG[$k+1]."-".$arr_ganji[$k+1];
        }

        $Secha1 = $arr_ganji_WG[$k]."-".$arr_ganji[$k];     // 기준일 이전의 세차

    }

// ---일진 추가 ---
    for ($i=1; $i < 13; $i++ ) {        // 입력받은 월 일은 새해 시작일임.
        for ($j=1; $j < 32; $j++ ) {
            if ($i > 0 && $j>0 && $j < 32) {
                $startdate = date("Y-n-j", mktime(0, 0, 0, $i, $j, $pYear));
                $pastdays = (strtotime($startdate)-strtotime($base_date))/86400;  //  1902-1-1 부터 해당년 1월 1일 직전 까지 경과일
                $k = ($pastdays+21) % 60;       // 해당일의 일진을 arr_ganji 어레이에 맞게 조정 (21)

                if ($i < $pMonth || $i == $pMonth && $j < $pDay) {
                $aHoli[$i][$j] = $Secha1."年-".$arr_ganji[$k]."日";
                }
                else {
                $aHoli[$i][$j] = $Secha2."年-".$arr_ganji[$k]."日";
                }
            }
        }
    }

    return $aHoli;

}

//------------------------------------------------------------------------
    /**
     * @function: fn_jeolki_ganji_ary($pYear,$pMonth,$pOption)
     * @return  : array
     * @brief:    년간 양력 일자에 대응되는 24절기, 일진 등을 입력한 어레이 리턴
     * @modify:   V220에서 $pMonth 추가, V320에서 class.solar.php 함수 제거.
     **/
Function fn_jeolki_ganji_ary($pYear,$pMonth,$pOption) {
    /******************************************************
    * 년간 양력 일자에 대응되는 절기, 일진 어레이
    *******************************************************/
    $aHoli = null;
	$aHoli = $jeolki = planner123_main::fn_get_term24($pYear);

// 간지 시작 기준날자 설정 : 입력 받은 option에 의해.
    switch ($pOption) {
      case (1):
        $ganjioption = "설날";        // 설날을 새해 첫날로 간주.
        break;
      case (2):
        $ganjioption = "입춘";        // 명리학, 사주 위주 (입춘을 새해 첫날로 간주).
        break;
      case (3):
        $ganjioption = "동지";        // 주자학 위주 (동지를 새해 첫날로 간주).
        break;
      default:                        // option 없을때
        $ganjioption = "설날";        // 설날을 새해 첫날로 간주.
        break;
    }

// 음력 1월 1일  양력날자 구하기  (세차 계산을 위해)------------------------
    $lunfirstday = explode("-",planner123_main::fn_lun2sol($pYear,1,1));
    $SeolMM = $lunfirstday[1];      // 세차(년 간지)가 바뀌는 일자 월  (음력 1월 1일)
    $SeolDD = $lunfirstday[2];      // 세차(년 간지)가 바뀌는 일자 일  (음력 1월 1일)

// 24 절기 및 주요날자 얻기-----------------------
    foreach($jeolki as $key1 => $value1) {
		foreach($value1 as $key2 => $value2) {
            // 콤멘트 추가
            switch ($value2) {
               case ("입춘"):
                $IpchunMM = $key1;     // 입춘 일자 월
                $IpchunDD = $key2;     // 입춘 일자 일
                break;
               case ("춘분"):
                $ChunbunMM = $key1;    // 춘분 일자 월
                $ChunbunDD = $key2;    // 춘분 일자 일
                break;
               case ("하지"):
                $HajiMM = $key1;       // 하지 일자 월
                $HajiDD = $key2;       // 하지 일자 일
                break;
               case ("입추"):
                $IpchuMM = $key1;      // 입추 일자 월
                $IpchuDD = $key2;      // 입추 일자 일
                break;
               case ("추분"):
                $ChubunMM = $key1;     // 추분 일자 월
                $ChubunDD = $key2;     // 추분 일자 일
                break;
               case ("동지"):
                $DongjiMM = $key1;     // 동지 일자 월
                $DongjiDD = $key2;     // 동지 일자 일
                break;
            }
        }
    }
// ---- 간지 시작일 을 조건에 따라 설정 ----
    if ($ganjioption == "설날"):
        $GanjiStartMM = $SeolMM;
        $GanjiStartDD = $SeolDD;
    elseif ($ganjioption == "입춘"):
        $GanjiStartMM = $IpchunMM;
        $GanjiStartDD = $IpchunDD;
    elseif ($ganjioption == "동지"):
        $GanjiStartMM = $DongjiMM;
        $GanjiStartDD = $DongjiDD;
    else:
        $GanjiStartMM = $SeolMM;        // 설날로 설정
        $GanjiStartDD = $SeolDD;
    endif;

// --세차와 일진을 구한다------------------------------
    $arr_Secha = planner123_main::fn_ganji_ary($pYear, $GanjiStartMM, $GanjiStartDD);


// --- 초복, 중복, 말복 계산(하지, 추분 및 일진을 조합하여 계산-------------
// 초복, 중복: 하지 로부터 세 번째(초복), 네번째(중복) 돌아오는 경일
// 말복      : 입추로부터 첫 번째 경일
// 하지 일자 월, 일: $HajiMM  $HajiDD
// 입추 일자 월, 일: $IpchuMM $IpchuDD
// 경일:'庚午','庚辰','庚寅','庚子','庚戌','庚申'  // 문자열 자르기가 안되서 비교로 처리함

//-- 초복, 중복 --------
    $k = 0;
    For ($i = $HajiMM; $i<10; $i++) {
        For ($j = 1; $j <32; $j++ ) {
            if ($i > $HajiMM || $i == $HajiMM && $j >= $HajiDD ) {

            $temp01 = explode("-",$arr_Secha[$i][$j]);
            $wrkfld01 = $temp01[2];
            if($wrkfld01 == "庚午日" || $wrkfld01 == "庚辰日" || $wrkfld01 == "庚寅日" || $wrkfld01 == "庚子日" ||  $wrkfld01 == "庚戌日" || $wrkfld01 == "庚申日") {
                $k = $k + 1;
                if ($k == 3) {
                    $aHoli[$i][$j] = $aHoli[$i][$j]."초복";
                }
                if ($k == 4) {
                    $aHoli[$i][$j] = $aHoli[$i][$j]."중복";
                    break;
                }
            }

            }
        }
    }

// -- 말복 ---
    $k = 0;
    For ($i = $IpchuMM; $i<10; $i++) {
        For ($j = 1; $j <32; $j++ ) {
            if ($i > $IpchuMM || $i == $IpchuMM && $j >= $IpchuDD ) {

            $temp01 = explode("-",$arr_Secha[$i][$j]);
            $wrkfld01 = $temp01[2];
            if($wrkfld01 == "庚午日" || $wrkfld01 == "庚辰日" || $wrkfld01 == "庚寅日" || $wrkfld01 == "庚子日" ||  $wrkfld01 == "庚戌日" || $wrkfld01 == "庚申日") {
                $k = $k + 1;
                if ($k == 1) {
                    $aHoli[$i][$j] = $aHoli[$i][$j]."말복";
                    break;
                }
            }

            }
        }
    }

// 기타 음력절기 상 특별한날 V320에서 변경------------------------
	//(단오)
	$temp01 = explode("-",planner123_main::fn_lun2sol($pYear,5,5));
	$iLunYmd =date("Y-n-j", mktime(0, 0, 0,$temp01[1], $temp01[2], $temp01[0]));
	$temp02 = explode("-",$iLunYmd);
	$aHoli[$temp02[1]][$temp02[2]] .= "단오";
	//(칠석)
	$temp01 = explode("-",planner123_main::fn_lun2sol($pYear,7,7));
	$iLunYmd =date("Y-n-j", mktime(0, 0, 0,$temp01[1], $temp01[2], $temp01[0]));
	$temp02 = explode("-",$iLunYmd);
	$aHoli[$temp02[1]][$temp02[2]] .= "칠석";
	//(백중)
	$temp01 = explode("-",planner123_main::fn_lun2sol($pYear,7,15));
	$iLunYmd =date("Y-n-j", mktime(0, 0, 0,$temp01[1], $temp01[2], $temp01[0]));
	$temp02 = explode("-",$iLunYmd);
	$aHoli[$temp02[1]][$temp02[2]] .= "백중";

//---- 한식일자 구하기: V320에서 변경
// 한식은 전년도 동지에서 105일째 되는 날
	if($pYear >=1903 && $pYear <=2037) {
		$ind50 = planner123_main::fn_get_dongji($pYear-1)+86400*105;
		$ind51 = date('n', $ind50);
		$ind52 = date('j', $ind50);
		$aHoli[$ind51][$ind52] .= "한식";
	}

//---- 절기와 간지 합하여 어레이 리턴---
//  For ($i = 1; $i<13; $i++) {	// V220 변경
    For ($i = $pMonth; $i<$pMonth+1; $i++) {
        For ($j = 1; $j <32; $j++ ) {
            $aHoli[$i][$j] = $arr_Secha[$i][$j]."-".$arr_WolGeon[$i][$j]."-".$aHoli[$i][$j];
        }
    }
    return $aHoli;

}

//-----------------------------------------------------------------------------------
     /**
     * @function: fn_easterday($pYear)
     * @return  : string
     * @brief:    해당년 부활절 일자를 구한다
     **/
function fn_easterday($pYear) {
    $k=easter_days($pYear);
    if ($k>10) {
        $wrkYmd =date("Y-n-j", mktime(0, 0, 0,4, $k-10, $pYear));
    }
    else   {
        $wrkYmd =date("Y-n-j", mktime(0, 0, 0,3, $k+21, $pYear));
    }
    return $wrkYmd;
}

//-----------------------------------------------------------------------------------
	 /**
     * @function: fn_easterday_2($pYear)
     * @return  : string
     * @brief:    해당년 부활절 일자를 구한다 (가우스공식이용) - 간혹 PHP에서 부활절함수 지원 안될때 이용
     **/
function fn_easterday_2($pYear) {
 $M = 24; // 1900-2099 년도분만 사용
 $N = 5;  // 1900-2099 년도분만 사용
    $A = $pYear % 19;
    $B = $pYear % 4;
    $C = $pYear % 7;
    $D = (19 * $A + $M) % 30;
    $E = ((2 * $B) + (4 * $C) + (6 * $D) + $N) % 7;
    $Tag1 = (22 + $D + $E);
    $Tag2 = ($D + $E - 9);

 if($Tag1 <= 31 ) {
   $easterday = mktime(0, 0, 0, 3, $Tag1, $pYear); // 3월 부활절 일자 타임스탬프
   $wrkYmd =date("Y-n-j", $easterday);
  }
  else {
   $easterday = mktime(0, 0, 0, 4, $Tag2, $pYear); // 4월 부활절 일자 타임스탬프
   $wrkYmd =date("Y-n-j", $easterday);
  }

    return $wrkYmd;
}
//--------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------
    /**
     * @function: fn_HolidayChk($pYear, $pMonth)
     * @function: fn_MemdayChk($pYear, $pMonth)
     * @brief:    휴일 기념일 함수는 별도로 분리함
     **/
//------------------------------------------------------------------------

//------------------------------------------------------------------------
    /**
     * @function: fn_repeat_schedule($pYear, $pMonth, $plan_start, $plan_end, $plan_repeat_cycle, $plan_repeat_unit)
     * @return  : array
     * @brief:    반복일정이 적용되는 양력일자 어레이 리턴
     **/
Function fn_repeat_schedule($pYear, $pMonth, $plan_start, $plan_end, $plan_repeat_cycle, $plan_repeat_unit) {
	/******************************************************
	* 반복일정이 적용되는 일자에 "*" 삽입
	* 반복일정은 일정시작일을 기준으로 반복되며, 모든 반복일정은 일정 자체의 기간은 1일 간으로한다.
	* 그렇지 않을경우 일정자체의 기간을 지정할 2개의 추가 확장변수가 필요하게 되어 실익이 없다.
	*******************************************************/

	$aHoli = null;

	if ($plan_start == null ) {	
		return $aHoli;
	}

	$startYY = substr($plan_start,0,4);
	$startMM = ltrim(substr($plan_start,4,2), "0" );	//  앞의 "0" 제거
	$startDD = ltrim(substr($plan_start,6,2), "0" );	//  앞의 "0" 제거
	$plnstartYY = $startYY;
	$plnstartMM = $startMM;	
	$plnstartDD = $startDD;	

	$endYY = substr($plan_end,0,4);
	$endMM = ltrim(substr($plan_end,4,2), "0" );	// 일자 앞의 "0" 제거
	$endDD = ltrim(substr($plan_end,6,2), "0" );	// 일자 앞의 "0" 제거
	$plnendYY = $endYY;
	$plnendMM = $endMM;	
	$plnendDD = $endDD;	

	$plan_startdate = mktime(0, 0, 0, $startMM, $startDD, $startYY);	// 일정시작 일자 타임스탬프
	$plan_enddate = mktime(0, 0, 0, $endMM, $endDD, $endYY);			// 일정종료 일자 타임스탬프

	$k = 12;		// 1년간 반복일정 처리 (월 반복일정)

	if ($plnstartYY == $pYear && $plnstartMM == $pMonth) {				// 당월 시작 이면서 당월 이후 종료
		if ($plnendYY > $pYear || $plnendYY == $pYear && $plnendMM > $pMonth) {
					$plnendYY = $pYear;
					$plnendMM = $pMonth;
					$plnendDD = 31;
		}
	}
	elseif ($plnstartYY < $pYear || $plnstartYY == $pYear && $plnstartMM < $pMonth) {	// 당월 이전 시작 이면서 당월 종료
		if ($plnendYY == $pYear && $plnendMM == $pMonth) {
					$plnstartYY = $pYear;
					$plnstartMM = $pMonth;
					$plnstartDD = 1;
		}
		if ($plnendYY > $pYear || $plnendYY == $pYear && $plnendMM > $pMonth) { // 당월 이전시작 이면서 당월 이후 종료
					$plnstartYY = $pYear;
					$plnstartMM = $pMonth;
					$plnstartDD = 1;
					$plnendYY = $pYear;
					$plnendMM = $pMonth;
					$plnendDD = 31;
		}
	}

	// plan_repeat_cycle 또는 plan_repeat_unit 값이 null 일때********************
	if ($plan_repeat_unit == null || $plan_repeat_cycle == null ) {	
		if ( $plnstartYY == $pYear && $plnstartMM == $pMonth) {	
			For	($i = $plnstartMM; $i <= $plnendMM; $i++) {
				For ($j = $plnstartDD; $j <= $plnendDD; $j++ ) {
					$aHoli[$i][$j] =  "*";	
				}
			}
		}
	}

	// unit 값이 1.일(간격) : 몇일 간결으로 반복***********************************
	elseif (substr($plan_repeat_unit,0,1) == "1" && $plan_repeat_cycle != null ) {	
		$k = date("t", mktime(0, 0, 0, $pMonth, 1, $pYear));	// 당월 마지막 날자
		For	($i = 1; $i <= $k; $i++ ) {
			if(!(((mktime(0, 0, 0, $pMonth, $i, $pYear)-$plan_startdate)/86400)%$plan_repeat_cycle)) { 
				$wrkday= $startDD + ($plan_repeat_cycle * $i);
				$wrkYY = date("Y", mktime(0, 0, 0, $pMonth, $i, $pYear));	// 반복될 일자-년
				$wrkMM = date("n", mktime(0, 0, 0, $pMonth, $i, $pYear));	// 반복될 일자-월
				$wrkDD = date("j", mktime(0, 0, 0, $pMonth, $i, $pYear));	// 반복될 일자-일
				$work_date = mktime(0, 0, 0, $wrkMM, $wrkDD, $wrkYY);				// 반복 일자 타임스탬프
				if( $work_date >= $plan_startdate && $work_date <= $plan_enddate && $pYear == $wrkYY) {	
					$aHoli[$wrkMM][$wrkDD] =  "*";	
				}
			}
		}
	}
	// 2.개월(날자): 반복월 같은 날자**********************************************
	elseif (substr($plan_repeat_unit,0,1) == "2" && $plan_repeat_cycle != null ) {
		if(!((($pYear*12 + $pMonth)-($startYY*12 + $startMM))%$plan_repeat_cycle)) { 
			$wrkYY = date("Y", mktime(0, 0, 0, $pMonth, $startDD, $pYear));	// 반복될 일자-년
			$wrkMM = date("n", mktime(0, 0, 0, $pMonth, $startDD, $pYear));	// 반복될 일자-월
			$wrkDD = date("j", mktime(0, 0, 0, $pMonth, $startDD, $pYear));	// 반복될 일자-일
			$work_date = mktime(0, 0, 0, $wrkMM, $wrkDD, $wrkYY);				// 반복 일자 타임스탬프
			if( $work_date >= $plan_startdate && $work_date <= $plan_enddate && $pYear == $wrkYY) {	
				$aHoli[$wrkMM][$wrkDD] =  "*";	
			}
		}
	}
	// 3.개월(요일): 반복월 같은번째 요일*******************************************
	elseif (substr($plan_repeat_unit,0,1) == "3" && $plan_repeat_cycle != null ) {	
		$pYoil = date("w", mktime(0, 0, 0,$startMM, $startDD, $startYY));	//해당일의 요일번호(일=0, 토=6)
		$yoilcount = planner123_main::fn_weekdaycountofmonth($startYY,$startMM,$startDD);		// n번째 요일 숫자
		if(!((($pYear*12 + $pMonth)-($startYY*12 + $startMM))%$plan_repeat_cycle)) { 
			$wrkYY = date("Y", mktime(0, 0, 0, $pMonth, $startDD, $pYear));	// 반복될 일자-년
			$wrkMM = date("n", mktime(0, 0, 0, $pMonth, $startDD, $pYear));	// 반복될 일자-월
			$wrkDD = date("j", mktime(0, 0, 0, $pMonth, $startDD, $pYear));	// 반복될 일자-일
			$temp01 = explode("-",planner123_main::fn_nsweekday($wrkYY, $wrkMM, $yoilcount, $pYoil));	// 해당n번째요일에 대응되는 일자 얻기
			$work_date = mktime(0, 0, 0, $temp01[1], $temp01[2], $temp01[0]);			// 반복 일자 타임스탬프
			if( $work_date >= $plan_startdate && $work_date <= $plan_enddate && $pYear == $wrkYY) {	
				$aHoli[$temp01[1]][$temp01[2]] =  "*";	
			}
		}
	}
	// 4.개월(주): 반복월 같은번째 주 같은요일**************************************
	elseif (substr($plan_repeat_unit,0,1) == "4" && $plan_repeat_cycle != null ) {	
		$pYoil = date("w", mktime(0, 0, 0,$startMM, $startDD, $startYY));	//해당일의 요일번호(일=0, 토=6)
		$weekcount = planner123_main::fn_weekcountofmonth($startYY,$startMM,$startDD);			//n번째 주 숫자
		if(!((($pYear*12 + $pMonth)-($startYY*12 + $startMM))%$plan_repeat_cycle)) { 
			$wrkYY = date("Y", mktime(0, 0, 0, $pMonth, $startDD, $pYear));	// 반복될 일자-년
			$wrkMM = date("n", mktime(0, 0, 0, $pMonth, $startDD, $pYear));	// 반복될 일자-월
			$wrkDD = date("j", mktime(0, 0, 0, $pMonth, $startDD, $pYear));	// 반복될 일자-일
			$temp01 = explode("-",planner123_main::fn_nsweeknsweekday($wrkYY, $wrkMM, $weekcount, $pYoil));	// 해당주/요일에 대응되는 일자 얻기
			$work_date = mktime(0, 0, 0, $temp01[1], $temp01[2], $temp01[0]);				// 반복 일자 타임스탬프
			if( $work_date >= $plan_startdate && $work_date <= $plan_enddate && $pYear == $wrkYY) {	
				$aHoli[$temp01[1]][$temp01[2]] =  "*";	
			}
		}
	}
	// 5.개월(말일): 반복월 말일****************************************************
	elseif (substr($plan_repeat_unit,0,1) == "5" && $plan_repeat_cycle != null ) {	
		if(!((($pYear*12 + $pMonth)-($startYY*12 + $startMM))%$plan_repeat_cycle)) { 
			$wrkYY = date("Y", mktime(0, 0, 0, $pMonth, $startDD, $pYear));	// 반복될 일자-년
			$wrkMM = date("n", mktime(0, 0, 0, $pMonth, $startDD, $pYear));	// 반복될 일자-월
			$wrkDD = date("j", mktime(0, 0, 0, $pMonth, $startDD, $pYear));	// 반복될 일자-일
			$wrklastday= date("t", mktime(0, 0, 0, $wrkMM, 1, $wrkYY));	// 반복될 마지막 날자
			$work_date = mktime(0, 0, 0, $wrkMM, $wrklastday, $wrkYY);			// 반복 일자 타임스탬프
			if( $work_date >= $plan_startdate && $work_date <= $plan_enddate && $pYear == $wrkYY) {	
				$aHoli[$wrkMM][$wrklastday] =  "*";	
			}
		} 
	} 
    // 6.개월(월말부터요일차): 반복월 끝에서부터 같은번째 요일****************************************************
    elseif (substr($plan_repeat_unit,0,1) == "6" && $plan_repeat_cycle != null ) {
        $pYoil = date("w", mktime(0, 0, 0,$startMM, $startDD, $startYY));    //해당일의 요일번호(일=0, 토=6)
        $yoilcount = planner123_main::fn_weekdaycountofmonth_end($startYY,$startMM,$startDD); //해당일의 말일에서부터의 n번째 요일 숫자
		if(!((($pYear*12 + $pMonth)-($startYY*12 + $startMM))%$plan_repeat_cycle)) { 
			$wrkYY = date("Y", mktime(0, 0, 0, $pMonth, $startDD, $pYear));	// 반복될 일자-년
			$wrkMM = date("n", mktime(0, 0, 0, $pMonth, $startDD, $pYear));	// 반복될 일자-월
			$wrkDD = date("j", mktime(0, 0, 0, $pMonth, $startDD, $pYear));	// 반복될 일자-일
            $temp01 = explode("-",planner123_main::fn_nslastweekday($wrkYY, $wrkMM, $yoilcount, $pYoil)); // 끝에서 n번째요일에 대응되는 일자 얻기
            $work_date = mktime(0, 0, 0, $temp01[1], $temp01[2], $temp01[0]);           // 반복 일자 타임스탬프
            if( $work_date >= $plan_startdate && $work_date <= $plan_enddate && $pYear == $wrkYY) {
                $aHoli[$temp01[1]][$temp01[2]] =  "*";
            }
        }
    }

	return $aHoli;
}

//------------------------------------------------------------------------
    /**
     * @brief XE에 설정된 타임존을 반영한 시간값을 구함
     * @param none,  XE함수 zgap() 사용
     * @return int
     **/
function fn_xetimestamp() {
    $localtimestamp = mktime(date("H"), date("i"), date("s")+zgap(), date("m"), date("d"), date("Y"));
    return $localtimestamp;
}

//------------------------------------------------------------------------
    /**
     * @brief Array sort 설정된 타임존을 반영한 시간값을 구함
     * @param array, sort order
     * @return array
     **/
function fn_multisort($array, $sort_by) {
    foreach ($array as $key => $value) {
        $evalstring = '';
        foreach ($sort_by as $sort_field) {
            $tmp[$sort_field][$key] = $value[$sort_field];
            $evalstring .= '$tmp[\'' . $sort_field . '\'], ';
        }
    }
    $evalstring .= '$array';
    $evalstring = 'array_multisort(' . $evalstring . ');';
    eval($evalstring);
    return $array;
} 

//------------------------------------------------------------------------
    /**
     * @brief Display small calendar
     * @param Year, month, day
     * @return string
     **/
function fn_smallcalendar_ymd($pYear, $pMonth, $pDay){ 
    $year = date("Y",mktime(0,0,0,$pMonth,1,$pYear)); 
    $month = date("n",mktime(0,0,0,$pMonth,1,$pYear)); 
    if ($pDay != 0){ 
    $day = date("d",mktime(0,0,0,$pMonth,$pDay,$pYear)); 
    } 
 
    $day_max = date("t",mktime(0,0,0,$month,1,$year)); 
    $week_start = date("w",mktime(0,0,0,$month,1,$year)); 
    $i = 0; 
    $j = 0; 
    $html = "<div class='calendar_box'>\n<div class='calendar_title stoday'>".sprintf("%d-%02d-%02d",$year,$month,$day)."</div>\n<ul>"; 
    while ($j<$day_max){ 
        if ($i<$week_start) { 
            $html .= "<li class='calendar_text'>*</li>"; 
        } else { 
            if ($i%7==0){ 
                $html .= "</ul><ul>\n"; 
                $font_color = " ssunday"; 
            } 
            else if ($i%7==6) $font_color = ""; 
            else $font_color = ""; 
            if ($month==$pMonth and $day == ($j+1)) $font_weight = " stoday"; 
            else $font_weight = ""; 
            $html .= "<li class='calendar_text$font_color$font_weight'>" . ($j+1) . "</li>"; 
            $j ++; 
        } 
        $i ++; 
    } 
    while ($i%7!==0){ 
        $html .= "<li class='calendar_text'>*</li>"; 
        $i ++; 
    } 
     $html .= "</ul>\n</div>\n"; 
    return $html; 
} 

//------------------------------------------------------------------------
    /**
     * @briefGet an array of file names in directory.
     * @param file path
     * @return array
     **/
// Get an array of file names in directory. 
function fn_readFolderDirectory($dir) { 
	$listDir = array(); 
	if($handler = opendir($dir)) { 
		while (($sub = readdir($handler)) !== FALSE) { 
			if ($sub != "." && $sub != ".." && $sub != "Thumb.db" && $sub != "Thumbs.db") { 
				if(is_file($dir."/".$sub)) { 
					$listDir[] = $sub; 
                } 
			} 
		}    
		closedir($handler); 
	} 
	return $listDir;    
}

//--------------------------------------------------------------------------------------
    /**
     * @function: fn_WeekOfYear($month, $day, $year)
     * @return  : integer
     * @brief:    당일의 년초부터 일요일 기준 주간 갯수구하기 : 1월 1일 금요일인 경우 1일-2일 =1주, 3일-9일=2주)
     **/
function fn_WeekOfYear($month, $day, $year) { // week count(일요일 부터)
	$day_of_year = date('z', mktime(0, 0, 0, $month, $day, $year)); 
	/* Days in the week before Jan 1. If you want weeks to start on Monday make this (x + 6) % 7 */ 
	$days_before_year = date('w', mktime(0, 0, 0, 1, 1, $year)); 
	$days_left_in_week = 7 - date('w', mktime(0, 0, 0, $month, $day, $year)); 
	/* find the number of weeks by adding the days in the week before the start of the year, days up to $day, and the days left in this week, then divide by 7 */ 
	return ($days_before_year + $day_of_year + $days_left_in_week) / 7; 
} 

//--------------------------------------------------------------------------------------
    /**
     * @function: fn_getTextFile($skin_path, $num = 2)
     * @return  : array
     * @brief:    text file 읽기. (경구 출력을 위하여...)
     **/
function fn_getTextFile($skin_path, $num = 2){
	$file_path = $skin_path."text/epigrams.txt";
	if(!file_exists($file_path)) return 'error1 '.$skin_path;
	$fp = fopen($file_path, "r");	// 파일 열기
	if(!$fp) return 'error2';
	while(!feof($fp)) $array[] = fgets($fp, 1024);	// 파일 읽기
	fclose($fp);	// 파일 닫기
	shuffle($array);	// 배열 섞기

	if(count($array) < $num) $num = count($array);
	for($i=0; $i<$num; $i++){	// 배열에서 2개글 뽑기
		$tmp = trim($array[$i]);
		if($tmp){
		$tmp_arr = explode('||', $tmp);
		$list[$i] = $tmp_arr[1];
		}
	}
	return $list;
}

//--------------------------------------------------------------------------------------
    /**
     * @function: fn_getClientOffsetTime()
     * @return  : string
     * @brief:    client의 timezone offset 값을 서버에 넘기기위한 작업
     **/
function fn_getClientOffsetTime(){
	parse_str($_SERVER['QUERY_STRING'], $query_srt);
	if ($query_srt[offset] == null) {  
		$rurl = $_SERVER['QUERY_STRING'];  //참고: $rurl = urlencode($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
		$html = "<script type='text/javascript'>";
		$html .= "function tz_offset() {";
		$html .= "var current_date = new Date( );";
		$html .= "var client_offset = current_date.getTimezoneOffset( )*60*(-1);";
		$html .= "location.href='?$rurl&offset=' + client_offset;";
		$html .= "}";
		$html .= "onload = tz_offset;";
		$html .= "</script>";
	}
	return $html;
}

//------------------------------------------------------------------------
    /**
     * @brief Get full name of holiday function file.
     * @param file path, country-ID
     * @return string
     **/
// Check holiday file is exist in directory and return full name of holiday function file. 
function fn_getHolidayFileName($skinpath,$country_id) { 
	$filename = "class.planner123_holiday_";
	$ind_01 = is_file($skinpath."function/".$filename.$country_id.".php");
	if($ind_01) { 
		$filename .= $country_id.".php";
	} else {
		$filename .= "default".".php";
	}
		return $filename;    
}

//------------------------------------------------------------------------
    /**
     * @function: fn_term01_data()
     * @return  : array
     * @brief:    get 24terms time stamp
     **/
function fn_term01_data() {
return "-2145494299,-2144222277,-2142946304,-2141664612,-2140374141,-2139073999,-2137762347,-2136439544,-2135105464,-2133761780,-2132410204,-2131053284,-2129694212,-2128335601,-2126981256,-2125633010,-2124294206,-2122965272,-2121648282,-2120342054,-2119047126,-2117760868,-2116482530,-2115208461,-2113937169,-2112664580,-2111389116,-2110106944,-2108817059,-2107516505,-2106205438,-2104882272,-2103548669,-2102204690,-2100853364,-2099496295,-2098137195,-2096778665,-2095424041,-2094076096,-2092736850,-2091408370,-2090090888,-2088785208,-2087489788,-2086204106,-2084925272,-2083651765,-2082379968,-2081107920,-2079831943,-2078550300,-2077259892,-2075959878,-2074648259,-2073325662,-2071991476,-2070648054,-2069296132,-2067939509,-2066579888,-2065221615,-2063866679,-2062518806,-2061179511,-2059850978,-2058533655,-2057227847,-2055932691,-2054646837,-2053370069,-2052096353,-2050824762,-2049552474,-2048276641,-2046994733,-2045704454,-2044404139,-2043092721,-2041769764,-2040435945,-2039092113,-2037740776,-2036383707,-2035024789,-2033666052,-2032311772,-2030963472,-2029624683,-2028295792,-2026978813,-2025672713,-2024377802,-2023091694,-2021813341,-2020539367,-2019267981,-2017995393,-2016719755,-2015437520,-2014147423,-2012846820,-2011535551,-2010212438,-2008878680,-2007534892,-2006183454,-2004826679,-2003467472,-2002109236,-2000754493,-1999406776,-1998067415,-1996739088,-1995421493,-1994115904,-1992820373,-1991534756,-1990255821,-1988982390,-1987710503,-1986438541,-1985162459,-1983880889,-1982590362,-1981290408,-1979978701,-1978656154,-1977321971,-1975978592,-1974626810,-1973270207,-1971910837,-1970552510,-1969197829,-1967849783,-1966510663,-1965181853,-1963864624,-1962558495,-1961263409,-1959977263,-1958698819,-1957424893,-1956153518,-1954881102,-1953605552,-1952323551,-1951033570,-1949733152,-1948421999,-1947098911,-1945765285,-1944421298,-1943070041,-1941712843,-1940353904,-1938995140,-1937640782,-1936292565,-1934953647,-1933624889,-1932307733,-1931001776,-1929706662,-1928420707,-1927142167,-1925868380,-1924596870,-1923324528,-1922048833,-1920766883,-1919476737,-1918176408,-1916865019,-1915542119,-1914208134,-1912864491,-1911512748,-1910156052,-1908796546,-1907438356,-1906083435,-1904735772,-1903396388,-1902068114,-1900750596,-1899445033,-1898149600,-1896863969,-1895585093,-1894311596,-1893039705,-1891767646,-1890491541,-1889209895,-1887919392,-1886619410,-1885307807,-1883985240,-1882651223,-1881307774,-1879956201,-1878599461,-1877240320,-1875881806,-1874527352,-1873179142,-1871840251,-1870511340,-1869194315,-1867888116,-1866593177,-1865306933,-1864028568,-1862754479,-1861483128,-1860210498,-1858934965,-1857652765,-1856362851,-1855062321,-1853751311,-1852428225,-1851094764,-1849750868,-1848399708,-1847042650,-1845683686,-1844325063,-1842970516,-1841622404,-1840283186,-1838954530,-1837637086,-1836331290,-1835035961,-1833750227,-1832471526,-1831197990,-1829926331,-1828654234,-1827378369,-1826096646,-1824806321,-1823506221,-1822194685,-1820872039,-1819537957,-1818194554,-1816842730,-1815486168,-1814126577,-1812768360,-1811413347,-1810065501,-1808726040,-1807397501,-1806079976,-1804774179,-1803478860,-1802193091,-1800914445,-1799640900,-1798369305,-1797097234,-1795821420,-1794539727,-1793249441,-1791949303,-1790637828,-1789315009,-1787981100,-1786637388,-1785285974,-1783929011,-1782570045,-1781211356,-1779857030,-1778508687,-1777169835,-1775840816,-1774523760,-1773217489,-1771922517,-1770636266,-1769357917,-1768083887,-1766812607,-1765540070,-1764264621,-1762982504,-1761692628,-1760392136,-1759081067,-1757757986,-1756424375,-1755080521,-1753729181,-1752372277,-1751013145,-1749654763,-1748300066,-1746952203,-1745612831,-1744284351,-1742966691,-1741660943,-1740365317,-1739079557,-1737800552,-1736527035,-1735255160,-1733983206,-1732707252,-1731425794,-1730135481,-1728835702,-1727524223,-1726201850,-1724867813,-1723524552,-1722172769,-1720816216,-1719456711,-1718098394,-1716743514,-1715395477,-1714056151,-1712727351,-1711409924,-1710103798,-1708808519,-1707522370,-1706243743,-1704969831,-1703698308,-1702425962,-1701150337,-1699868497,-1698578534,-1697278365,-1695967305,-1694644500,-1693310988,-1691967225,-1690616034,-1689258912,-1687899961,-1686541106,-1685186679,-1683838265,-1682499275,-1681170292,-1679853104,-1678546944,-1677251840,-1675965710,-1674687205,-1673413263,-1672141807,-1670869335,-1669593723,-1668311690,-1667021688,-1665721343,-1664410182,-1663087332,-1661753633,-1660410062,-1659058583,-1657701917,-1656342561,-1654984309,-1653629367,-1652281556,-1650942015,-1649613567,-1648295847,-1646990156,-1645694560,-1644408880,-1643129914,-1641856436,-1640584509,-1639312502,-1638036388,-1636754814,-1635464318,-1634164438,-1632852862,-1631530452,-1630196482,-1628853246,-1627501714,-1626145198,-1624786045,-1623427690,-1622073129,-1620724947,-1619385847,-1618056837,-1616739556,-1615433209,-1614138041,-1612851692,-1611573183,-1610299085,-1609027685,-1607755132,-1606479610,-1605197528,-1603907644,-1602607227,-1601296251,-1599973257,-1598639853,-1597296028,-1595944976,-1594587960,-1593229141,-1591870505,-1590516091,-1589167875,-1587828716,-1586499853,-1585182373,-1583876298,-1582580882,-1581294864,-1580016105,-1578742350,-1577470725,-1576198513,-1574922786,-1573641036,-1572350911,-1571050817,-1569739478,-1568416826,-1567082895,-1565739470,-1564387749,-1563031186,-1561671656,-1560313479,-1558958478,-1557610698,-1556271180,-1554942688,-1553625023,-1552319214,-1551023677,-1549737848,-1548458955,-1547185356,-1545913551,-1544641493,-1543365560,-1542083975,-1540793664,-1539493712,-1538182251,-1536859637,-1535525714,-1534182170,-1532830685,-1531473835,-1530114777,-1528756157,-1527401767,-1526053465,-1524714593,-1523385589,-1522068535,-1520762236,-1519467242,-1518180903,-1516902486,-1515628329,-1514356957,-1513084298,-1511808787,-1510526604,-1509236742,-1507936259,-1506625291,-1505302259,-1503968799,-1502624958,-1501273755,-1499916772,-1498557726,-1497199193,-1495844544,-1494496523,-1493157193,-1491828600,-1490511007,-1489205199,-1487909660,-1486623861,-1485344932,-1484071359,-1482799531,-1481527485,-1480251554,-1478969990,-1477679705,-1476379849,-1475068424,-1473746037,-1472412076,-1471068857,-1469717112,-1468360609,-1467001039,-1465642742,-1464287702,-1462939665,-1461600142,-1460271361,-1458953768,-1457647722,-1456352350,-1455066355,-1453787696,-1452513978,-1451242437,-1449970272,-1448694598,-1447412895,-1446122838,-1444822766,-1443511583,-1442188858,-1440855231,-1439511546,-1438160279,-1436803211,-1435444205,-1434085323,-1432730836,-1431382297,-1430043240,-1428714077,-1427396841,-1426090508,-1424795418,-1423509188,-1422230791,-1420956845,-1419685576,-1418413162,-1417137765,-1415855790,-1414565981,-1413265644,-1411954624,-1410631705,-1409298100,-1407954397,-1406602988,-1405246175,-1403886876,-1402528483,-1401173545,-1399825585,-1398485969,-1397157371,-1395839529,-1394533708,-1393237997,-1391952246,-1390673233,-1389399775,-1388127914,-1386856026,-1385580076,-1384298687,-1383008390,-1381708704,-1380397273,-1379075008,-1377741069,-1376397903,-1375046272,-1373689772,-1372330434,-1370972086,-1369617318,-1368269136,-1366929819,-1365600777,-1364283279,-1362976876,-1361681509,-1360395118,-1359116451,-1357842373,-1356570893,-1355298462,-1354022968,-1352741116,-1351451353,-1350151228,-1348840405,-1347517672,-1346184388,-1344840702,-1343489686,-1342132643,-1340773774,-1339414969,-1338060486,-1336712046,-1335372844,-1334043757,-1332726266,-1331419972,-1330124557,-1328838335,-1327559592,-1326285664,-1325012299,-1323739974,-1322464388,-1321182618,-1319892736,-1318592719,-1317281698,-1315959170,-1314625562,-1313282232,-1311930742,-1310574188,-1309214714,-1307856435,-1306501320,-1305153385,-1303813665,-1302485043,-1301167178,-1299861301,-1298565601,-1297279758,-1296000733,-1294727154,-1293455249,-1292183239,-1290907247,-1289625761,-1288335453,-1287035683,-1285724297,-1284401955,-1283068151,-1281724917,-1280373522,-1279016937,-1277657872,-1276299375,-1274944849,-1273596498,-1272257395,-1270928235,-1269610948,-1268304488,-1267009323,-1265722888,-1264444385,-1263170209,-1261898818,-1260626194,-1259350703,-1258068585,-1256778776,-1255478389,-1254167528,-1252844626,-1251511351,-1250167651,-1248816688,-1247459804,-1246100990,-1244742454,-1243387952,-1242039793,-1240700468,-1239371617,-1238053924,-1236747814,-1235452160,-1234166105,-1232887134,-1231613401,-1230341636,-1229069523,-1227793734,-1226512157,-1225222045,-1223922196,-1222610947,-1221288586,-1219954797,-1218611649,-1217260065,-1215903690,-1214544236,-1213186090,-1211831076,-1210483157,-1209143533,-1207814776,-1206496958,-1205190845,-1203895179,-1202609093,-1201330155,-1200056400,-1198786466,-1197514365,-1196238611,-1194957071,-1193667011,-1192367159,-1191055992,-1189733491,-1188399863,-1187056379,-1185705106,-1184348216,-1182989234,-1181630491,-1180276062,-1178927599,-1177588600,-1176259421,-1174942194,-1173635740,-1172340591,-1171054166,-1169775670,-1168501519,-1167230171,-1165957611,-1164682215,-1163400195,-1162110487,-1160810188,-1159499342,-1158176478,-1156843067,-1155499366,-1154148132,-1152791264,-1151432113,-1150073648,-1148718840,-1147370832,-1146031325,-1144702706,-1143384938,-1142079089,-1140783392,-1139497567,-1138218505,-1136944924,-1135672983,-1134400960,-1133124955,-1131843475,-1130553192,-1129253499,-1127942153,-1126619966,-1125286128,-1123943080,-1122591489,-1121235099,-1119875706,-1118517442,-1117162553,-1115814451,-1114475004,-1113146063,-1111828472,-1110522197,-1109226770,-1107940511,-1106661780,-1105387809,-1104116232,-1102843874,-1101568250,-1100286456,-1098996561,-1097696509,-1096385592,-1095062968,-1093729649,-1092386084,-1091035076,-1089678099,-1088319238,-1086960397,-1085605902,-1084257336,-1082918126,-1081588885,-1080271432,-1078965022,-1077669720,-1076383448,-1075104880,-1073830947,-1072559574,-1071287237,-1070011815,-1068729991,-1067440225,-1066140103,-1064829168,-1063506507,-1062172982,-1060829528,-1059478131,-1058121477,-1056762073,-1055403701,-1054048581,-1052700542,-1051360738,-1050032016,-1048714026,-1047408089,-1046112293,-1044826470,-1043547437,-1042273972,-1041002146,-1039730314,-1038454438,-1037173131,-1035882907,-1034583271,-1033271889,-1031949622,-1030615736,-1029272544,-1027921002,-1026564454,-1025205214,-1023846758,-1022492050,-1021143703,-1019804408,-1018475198,-1017157717,-1015851182,-1014555857,-1013269384,-1011990794,-1010716674,-1009445303,-1008172849,-1006897474,-1005615598,-1004325945,-1003025790,-1001715053,-1000392290,-999059062,-997715362,-996364373,-995007359,-993648490,-992289748,-990935211,-989586825,-988247485,-986918403,-985600688,-984294347,-982998673,-981712408,-980433453,-979159570,-977887901,-976615731,-975340146,-974058618,-972768801,-971469066,-970158128,-968835867,-967502308,-966159173,-964807673,-963451208,-962091671,-960733376,-959378164,-958030103,-956690249,-955361408,-954043375,-952737222,-951441359,-950155262,-948876149,-947602415,-946330550,-945058527,-943782719,-942501348,-941211333,-939911750,-938600698,-937278519,-935944994,-934601791,-933250528,-931893788,-930534690,-929175928,-927821282,-926472660,-925133418,-923804039,-922486628,-921180013,-919884764,-918598235,-917319700,-916045490,-914774136,-913501554,-912226185,-910944190,-909654566,-908354351,-907043674,-905720946,-904387780,-903044202,-901693218,-900336375,-898977386,-897618803,-896264018,-894915759,-893576141,-892247207,-890929277,-889623142,-888327327,-887041303,-885762212,-884488524,-883216633,-881944563,-880668657,-879387164,-878097011,-876797335,-875486141,-874164024,-872830359,-871487451,-870136017,-868779797,-867420464,-866062326,-864707353,-863359278,-862019603,-860690585,-859372668,-858066262,-856770503,-855484149,-854205164,-852931200,-851659480,-850387239,-849111565,-847829957,-846540058,-845240215,-843929298,-842606881,-841273568,-839930201,-838579232,-837222433,-835863641,-834504900,-833150460,-831801871,-830462662,-829133268,-827815741,-826509074,-825213645,-823927082,-822648399,-821374226,-820102814,-818830345,-817554994,-816273143,-814983544,-813683456,-812372732,-811050103,-809716786,-808373323,-807022116,-805665435,-804306207,-802947820,-801592839,-800244784,-798905038,-797576274,-796258248,-794952212,-793656290,-792370318,-791091110,-789817484,-788545502,-787273553,-785997607,-784716278,-783426089,-782126539,-780815262,-779493158,-778159373,-776816357,-775464845,-774108448,-772749162,-771390846,-770036064,-768687853,-767348481,-766019385,-764701821,-763395355,-762099918,-760813459,-759534709,-758260557,-756988990,-755716494,-754440936,-753159059,-751869291,-750569210,-749258457,-747935840,-746602681,-745259135,-743908246,-742551311,-741192521,-739833749,-738479273,-737130789,-735791524,-734462333,-733144722,-731838288,-730542740,-729256392,-727977557,-726703571,-725431988,-724159678,-722884147,-721602456,-720312692,-719012810,-717701960,-716379606,-715046191,-713703028,-712351696,-710995241,-709635819,-708277519,-706922317,-705574231,-704234304,-702905453,-701587330,-700281219,-698985305,-697699312,-696420196,-695146605,-693874754,-692602865,-691327047,-690045768,-688755695,-687456163,-686145008,-684822877,-683489234,-682146112,-680794747,-679438134,-678078958,-676720318,-675365589,-674017017,-672677669,-671348268,-670030753,-668724090,-667428775,-666142239,-664863709,-663589574,-662318298,-661045855,-659770597,-658488745,-657199210,-655899087,-654588450,-653265733,-651932572,-650588929,-649237958,-647881004,-646522072,-645163367,-643808671,-642460275,-641120718,-639791620,-638473705,-637167390,-635871581,-634585406,-633306363,-632032596,-630760844,-629488792,-628213121,-626931717,-625641841,-624342260,-623031301,-621709221,-620375687,-619032739,-617681307,-616325007,-614965570,-613607374,-612252255,-610904177,-609564347,-608235355,-606917268,-605610884,-604314944,-603028618,-601749466,-600475568,-599203744,-597931644,-596655960,-595374589,-594084766,-592785226,-591474408,-590152284,-588819011,-587475845,-586124814,-584768079,-583409136,-582050329,-580695720,-579347002,-578007676,-576678157,-575360584,-574053807,-572758371,-571471703,-570193028,-568918765,-567647382,-566374863,-565099593,-563817765,-562528329,-561228343,-559917864,-558595369,-557262325,-555918937,-554567948,-553211216,-551852087,-550493520,-549138508,-547790199,-546450346,-545121348,-543803223,-542497036,-541201072,-539915028,-538635833,-537362179,-536090243,-534818288,-533542413,-532261102,-530971019,-529671538,-528360409,-527038446,-525704827,-524361999,-523010600,-521654371,-520295071,-518936838,-517581890,-516233657,-514894003,-513564815,-512246942,-510940393,-509644710,-508358243,-507079346,-505805281,-504533648,-503261304,-501985724,-500704028,-499414254,-498110763,-496800017,-495477595,-494144477,-492801123,-491450317,-490093525,-488734815,-487376072,-486021622,-484673013,-483333694,-482004251,-480686528,-479379787,-478084133,-476797516,-475518656,-474244506,-472973014,-471700655,-470425310,-469143641,-467854108,-466554262,-465243641,-463921296,-462588087,-461244906,-459893760,-458537285,-457178014,-455819694,-454464562,-453116432,-451776459,-450447515,-449129237,-447822984,-446526856,-445240714,-443961398,-442687713,-441415749,-440143868,-438868050,-437586888,-436296898,-434997550,-433686497,-432364560,-431030968,-429688012,-428336617,-426980142,-425620884,-424262372,-422907551,-421559080,-420219629,-418890264,-417572612,-416265906,-414970412,-413683776,-412405039,-411130798,-409859340,-408586849,-407311488,-406029688,-404740159,-403440178,-402129636,-400807093,-399474062,-398130542,-396779681,-395422736,-394063875,-392705075,-391350442,-390001912,-388662434,-387333202,-386015367,-384708913,-383413163,-382126824,-380847808,-379573849,-378302105,-377029857,-375754214,-374472659,-373182873,-371883218,-370572425,-369250347,-367917014,-366574103,-365222833,-363866552,-362507159,-361148938,-359793734,-358445612,-357105635,-355776636,-354458416,-353152085,-351856050,-350569817,-349290588,-348016783,-346744866,-345472834,-344197034,-342915712,-341625768,-340326294,-339015380,-337693377,-336360041,-335017039,-333665960,-332309380,-330950371,-329591638,-328236919,-326888155,-325548691,-324219059,-322901376,-321594505,-320299040,-319012350,-317733727,-316459506,-315188217,-313915764,-312640575,-311358788,-310069398,-308769405,-307458951,-306136411,-304803410,-303463554,-302112650,-300755828,-299396805,-298038119,-296683179,-295334701,-293994842,-292662034,-291343844,-290037454,-288741442,-287455263,-286176099,-284902410,-283630608,-282358697,-281083019,-279801777,-278511886,-277212438,-275901438,-274579461,-273245888,-271903029,-270551603,-269195358,-267835967,-266477749,-265122663,-263776252,-262436411,-261107219,-259789108,-258482523,-257186592,-255900104,-254621010,-253346996,-252075267,-250803090,-249527523,-248246089,-246956393,-245656792,-244346109,-243023923,-241690795,-240347574,-238996687,-237639917,-236281097,-234922286,-233567742,-232219016,-230879642,-229550052,-228232290,-226925364,-225629669,-224342852,-223063964,-221789649,-220518176,-219245731,-217970499,-216688849,-215399533,-214099783,-212789443,-211467195,-210134245,-208791075,-207440097,-206083523,-204724307,-203365811,-202010638,-200662311,-199322252,-197993154,-196674789,-195368434,-194072225,-192786002,-191506606,-190232850,-188960822,-187688900,-186413067,-185131925,-183842001,-182542771,-181231861,-179910135,-178576700,-177233976,-175882659,-174526355,-173167034,-171808600,-170453593,-169105101,-167765395,-166435964,-165118071,-163811322,-162515655,-161229029,-159950178,-158675991,-157404444,-156132030,-154856595,-153574893,-152285324,-150985477,-149674959,-148352598,-147019671,-145676349,-144325635,-142968821,-141610079,-140251272,-138896686,-137547999,-136208491,-134879003,-133561094,-132254365,-130958568,-129672012,-128393028,-127118936,-125847299,-124574981,-123299491,-122017892,-120728279,-119428587,-118117972,-116795871,-115462735,-114119839,-112768784,-111412559,-110053341,-108695169,-107340023,-105991899,-104651838,-103322774,-102004355,-100697915,-99401644,-98115315,-96835894,-95562071,-94290060,-93018109,-91742311,-90461140,-89171247,-87871953,-86561079,-85239253,-83905915,-82563088,-81211982,-79855591,-78496560,-77138010,-75783268,-74434616,-73095098,-71765478,-70447690,-69140734,-67845117,-66558292,-65279511,-64005182,-62733788,-61461314,-60186115,-58904407,-57615093,-56315240,-55004905,-53682492,-52349611,-51006209,-49655414,-48298563,-46939661,-45580915,-44226127,-42877588,-41537874,-40208589,-38892295,-37585785,-36289801,-35003448,-33724263,-32450374,-31178550,-29906468,-28630826,-27349489,-26059725,-24760273,-23449467,-22127546,-20794172,-19451374,-18100069,-16743855,-15384467,-14026269,-12671112,-11322957,-9983033,-8653945,-7335758,-6029296,-4733278,-3446894,-2167679,-893736,378144,1650271,2925987,4207350,5497151,6796621,8107347,9429338,10762470,12105481,13456377,14813000,16171875,17530655,18885289,20234075,21573517,22903180,24220936,25527895,26823506,28110315,29389082,30663383,31934748,33207203,34482368,35764059,37053327,38353131,39663402,40985696,42318531,43661743,45012576,46369218,47728312,49086928,50442056,51790560,53130655,54459937,55778356,57084832,58381039,59667282,60946587,62220278,63492155,64763984,66039656,67320728,68610529,69909728,71220575,72542292,73875714,75218413,76569763,77926015,79285419,80643796,81998955,83347425,84687351,86016809,87334951,88641733,89937609,91224207,92503169,93777218,95048765,96320938,97596298,98877715,100167201,101466792,102777278,104099467,105432428,106775676,108126456,109483281,110842087,112200979,113555614,114904454,116244010,117573713,118891681,120198656,121494505,122781288,124060271,125334509,126606043,127878387,129153653,130435168,131724473,133024044,134334346,135656377,136989279,138332210,139683147,141039504,142398713,143757059,145112277,146460568,147800751,149129952,150448526,151755083,153051525,154337954,155617524,156891404,158163498,159435423,160711199,161992231,163281994,164581047,165891737,167213281,168546479,169889068,171240169,172596434,173955612,175314152,176669141,178017864,179357645,180687359,182005372,183312409,184608205,185895091,187174018,188448382,189719891,190992355,192267617,193549243,194838536,196138224,197448437,198770625,200103314,201446514,202797123,204153901,205512700,206871556,208226350,209575144,210914941,212244540,213562731,214869530,216165564,217452139,218731307,220005357,221277112,222549316,223824854,225106276,226395898,227695386,229005994,230327883,231661010,233003711,234354773,235710875,237070123,238428267,239783464,241131664,242471791,243801005,245119487,246426090,247722399,249008871,250288300,251562239,252834244,254106289,255382068,256663306,257953142,259252463,260563211,261885023,263218163,264560957,265911837,267268224,268627068,269985665,271340311,272689057,274028595,275358375,276676305,277983480,279279293,280566327,281845254,283119709,284391144,285663647,286938789,288220444,289509629,290809367,292119528,293441773,294774482,296117683,297468363,298825022,300183928,301542564,302897505,304246055,305586037,306915435,308233854,309540522,310836819,312123298,313402721,314676638,315948586,317220570,318496220,319777351,321067042,322366233,323676935,324998614,326331921,327674576,329025877,330382073,331741489,333099772,334454964,335803291,337143260,338472573,339790808,341097504,342393547,343680136,344959329,346233418,347505211,348777412,350052977,351334352,352623961,353923424,355233955,356555965,357888941,359232018,360582812,361939534,363298365,364657236,366011883,367360742,368700246,370029965,371347826,372654824,373950564,375237411,376516329,377790686,379062209,380334707,381609983,382891645,384180930,385480605,386790815,388112901,389445654,390788628,392139408,393495833,394854928,396213377,397568559,398916967,400257156,401586425,402904982,404211520,405507899,406794252,408073739,409347543,410619576,411891470,413167237,414448289,415738087,417037179,418347916,419669464,421002705,422345241,423696396,425052575,426411848,427770302,429125432,430474104,431814058,433143751,434461919,435768912,437064788,438351555,439630475,440904650,442176107,443448358,444723580,446005028,447294335,448593915,449904196,451226341,452559113,453902311,455252973,456609789,457968603,459327548,460682328,462031267,463371045,464700829,466019010,467325995,468621987,469908693,471187739,472461825,473733360,475005509,476280764,477562099,478851438,480150880,481461271,482783202,484116208,485459031,486810051,488166304,489525572,490883843,492239112,493587397,494927637,496256902,497575529,498882168,500178625,501465102,502744636,504018516,505290538,506562427,507838118,509119107,510408784,511707818,513018424,514339985,515673093,517015731,518366719,519723055,521082102,522440720,523795593,525144404,526484133,527813989,529132061,530439308,531735226,533022317,534301313,535575784,536847237,538119680,539394756,540676252,541965273,543264774,544574704,545896709,547229191,548568658,549919195,551275901,552634777,553993620,555348611,556697447,558037504,559367173,560685637,561996109,563292396,564579020,565858390,567132410,568404267,569676314,570951827,572232965,573522449,574821573,576132002,577453545,578786561,580125458,581476551,582832649,584192032,585550323,586905673,588254100,589594348,590923788,592242327,593552703,594848991,596135576,597414926,598688931,599960813,601232877,602508487,603789688,605079306,606378553,607689052,609010793,610343693,611686469,613037172,614393639,615752424,617111186,618465891,619814830,621154492,622484433,623802497,625109766,626405670,627692735,628971716,630246179,631517653,632790152,634065299,635346900,636636015,637935614,639245633,640567650,641900185,643243102,644593637,645950026,647308887,648667349,650022390,651370908,652711106,654040588,655359286,656666096,657962667,659249274,660528909,661802878,663074946,664346885,665622563,666903558,668193194,669492174,670802741,672124161,673457272,674799674,676150757,677506786,678866040,680224327,681579495,682928029,684268100,685597744,686916127,688223169,689519331,690806204,692085421,693359678,694631370,695903609,697178956,698460268,699749586,701048943,702359168,703681073,705013779,706356788,707707400,709064108,710422876,711781790,713136503,714485467,715825158,717155026,718473148,719780288,721076281,722363212,723642312,724916654,726188251,727460629,728735890,730017370,731306612,732606098,733916290,735238200,736570963,737913764,739264574,740620845,741979983,743338311,744693539,746041877,747382128,748711409,750030063,751336689,752633194,753919671,755199290,756473209,757745348,759017305,760293117,761574159,762863922,764162942,765473568,766795021,768128107,769470569,770821553,772177713,773536823,774895321,776250322,777599087,778938968,780268814,781587005,782894223,784190197,785477220,786756236,788030624,789302106,790574488,791849633,793131105,794420226,795719727,797029748,798351750,799684265,801027314,802377810,803734525,805093322,806452242,807807166,809156151,810496176,811826041,813144494,814451553,815747798,817034545,818313798,819587870,820859549,822131612,823406935,824688105,825977440,827276647,828586982,829908656,831241622,832584249,833935310,835291487,836650862,838009184,839364590,840713031,842053406,843382869,844701584,846008385,847304856,848591427,849870904,851144816,852416731,853688613,854964180,856245151,857534711,858833743,860144239,861465831,862798830,864141536,865492414,866848859,868207826,869566589,870921441,872270414,873610192,874940210,876258373,877565748,878861742,880148917,881427955,882702486,883973952,885246428,886521475,887802956,889091897,890391335,891701161,893023067,894355454,895698390,897048866,898405419,899764289,901122986,902478053,903826800,905167018,906496695,907815407,909122379,910418966,911705717,912985360,914259451,915531492,916803505,918079086,919360074,920649526,921948413,923258741,924580024,925912924,927255209,928606212,929962210,931321563,932679911,934035311,935383928,936724263,938053955,939372566,940679597,941975936,943262754,944542113,945816292,947088105,948360247,949635688,950916861,952206223,953505379,954815582,956137235,957469874,958812629,960163178,961519727,962878501,964237425,965592244,966941375,968281214,969611319,970929557,972236913,973532948,974820025,976099086,977373510,978645020,979917441,981192594,982474100,983763214,985062707,986372727,987694617,989027154,990369916,991720481,993076728,994435666,995794038,997149205,998497693,999838035,1001167533,1002486366,1003793200,1005089877,1006376491,1007656197,1008930154,1010202275,1011474187,1012749910,1014030864,1015320517,1016619433,1017929961,1019251293,1020584302,1021926610,1023277551,1024633528,1025992636,1027350956,1028706023,1030054683,1031394727,1032724588,1034043023,1035350334,1036646573,1037933689,1039212918,1040487327,1041758928,1043031219,1044306385,1045587677,1046876757,1048176050,1049486014,1050807831,1052140294,1053483208,1054833648,1056190292,1057549004,1058907913,1060262723,1061611754,1062951679,1064281674,1065600097,1066907371,1068203655,1069490667,1070769974,1072044294,1073315977,1074588208,1075863437,1077144665,1078433802,1079732983,1081043063,1082364689,1083697412,1085040017,1086390889,1087747077,1089106340,1090464674,1091820040,1093168460,1094508838,1095838254,1097157022,1098463794,1099760378,1101046966,1102326602,1103600561,1104872644,1106144558,1107420248,1108701180,1109990775,1111289670,1112600121,1113921499,1115254435,1116596908,1117947777,1119304032,1120663059,1122021706,1123376667,1124725592,1126065465,1127395455,1128713663,1130021004,1131317012,1132604163,1133883227,1135157761,1136429283,1137701784,1138976901,1140258400,1141547384,1142846800,1144156594,1145478429,1146810703,1148153558,1149503883,1150860416,1152219152,1153577927,1154932913,1156281820,1157622007,1158951867,1160270547,1161577653,1162874154,1164160970,1165440473,1166714591,1167986475,1169258515,1170533958,1171815001,1173104345,1174403310,1175713544,1177034888,1178367688,1179709981,1181060890,1182416850,1183776169,1185134476,1186489940,1187838543,1189179034,1190508738,1191827554,1193134589,1194431107,1195717859,1196997310,1198271334,1199543156,1200815077,1202090490,1203371439,1204660794,1205959763,1207270016,1208591532,1209924271,1211266919,1212617568,1213974027,1215332876,1216691754,1218046636,1219395800,1220735713,1222065935,1223384263,1224691784,1225987899,1227275125,1228554203,1229828691,1231100113,1232372486,1233647454,1234928833,1236217718,1237517084,1238826893,1240148730,1241481116,1242823936,1244174410,1245530798,1246889676,1248248209,1249603335,1250951980,1252292321,1253621981,1254940868,1256247874,1257544641,1258831420,1260111199,1261385274,1262657393,1263929329,1265204937,1266485804,1267775247,1269073998,1270384294,1271705454,1273038307,1274380500,1275731430,1277087370,1278446610,1279804939,1281160215,1282508883,1283849147,1285179007,1286497655,1287804969,1289101416,1290388539,1291667969,1292942374,1294214145,1295486379,1296761642,1298042785,1299331865,1300630909,1301940784,1303262311,1304594659,1305937336,1307287707,1308644257,1310002987,1311361976,1312716872,1314066105,1315406120,1316736344,1318054812,1319362284,1320658561,1321945735,1323225006,1324499469,1325771102,1327043457,1328318610,1329599923,1330888929,1332188131,1333498002,1334819591,1336152047,1337494598,1338845221,1340201395,1341560510,1342918919,1344274300,1345622876,1346963407,1348293005,1349611969,1350918880,1352215624,1353502276,1354782003,1356055963,1357328085,1358599970,1359875672,1361156562,1362446158,1363744982,1365055414,1366376664,1367709557,1369051838,1370402668,1371758704,1373117743,1374476225,1375831288,1377180169,1378520242,1379850315,1381168776,1382476257,1383772500,1385059754,1386338980,1387613527,1388885118,1390157542,1391432664,1392714037,1394003003,1395302292,1396612067,1397933799,1399266033,1400608810,1401959050,1403315541,1404674154,1406032949,1407387815,1408736826,1410076953,1411407011,1412725717,1414033091,1415329668,1416616758,1417896313,1419170649,1420442500,1421714662,1422989975,1424271056,1425560207,1426859176,1428169215,1429490579,1430823222,1432165554,1433516358,1434872342,1436231603,1437589894,1438945351,1440293904,1441634441,1442964100,1444283037,1445590072,1446886786,1448173585,1449453269,1450727344,1451999370,1453271294,1454546830,1455827691,1457117080,1458415878,1459726119,1461047434,1462380181,1463722657,1465073378,1466429719,1467788670,1469147480,1470502449,1471851576,1473191533,1474521735,1475840070,1477147601,1478443730,1479731011,1481010136,1482284718,1483556213,1484828685,1486103712,1487385148,1488674032,1489973386,1491283108,1492604889,1493937131,1495279924,1496630264,1497986717,1499345510,1500704190,1502059268,1503408081,1504748385,1506078176,1507396996,1508704069,1510000738,1511287547,1512567227,1513841346,1515113392,1516385410,1517660978,1518941949,1520231359,1521530195,1522840436,1524161621,1525494390,1526836545,1528187417,1529543306,1530902580,1532260889,1533616308,1534964982,1536305450,1537635313,1538954151,1540261412,1541557974,1542844958,1544124423,1545398632,1546670406,1547942440,1549217727,1550498705,1551787853,1553086775,1554396755,1555718185,1557050634,1558393215,1559743652,1561100123,1562458900,1563817891,1565172851,1566522188,1567862280,1569192679,1570511206,1571818853,1573115130,1574402405,1575681577,1576956034,1578227475,1579499748,1580774668,1582055888,1583344681,1584643845,1585953558,1587275197,1588607551,1589950226,1591300774,1592657089,1594016135,1595374680,1596730040,1598078765,1599419351,1600749107,1602068184,1603375240,1604672104,1605958854,1607238639,1608512608,1609784674,1611056460,1612331996,1613612707,1614902089,1616200716,1617510974,1618832073,1620164899,1621507095,1622857994,1624213998,1625573197,1626931654,1628286907,1629635767,1630976044,1632306134,1633624810,1634932338,1636228794,1637516092,1638795492,1640070026,1641341712,1642614015,1643889116,1645170249,1646459093,1647758073,1649067682,1650389125,1651721226,1653063823,1654414016,1655770499,1657129148,1658488087,1659843017,1661192238,1662532407,1663862690,1665181415,1666489010,1667785598,1669072898,1670352444,1671626961,1672898759,1674171042,1675446222,1676727327,1678016243,1679315135,1680624852,1681946086,1683278394,1684620619,1685971170,1687327138,1688686310,1690044697,1691400241,1692748947,1694089670,1695419468,1696738603,1698045721,1699342604,1700629431,1701909245,1703183310,1704455431,1705727309,1707002896,1708283658,1709573034,1710871653,1712181806,1713502854,1714835473,1716177639,1717528261,1718884328,1720243272,1721601934,1722957024,1724306171,1725646349,1726976687,1728295266,1729602953,1730899273,1732186658,1733465891,1734740502,1736012035,1737284476,1738559497,1739840863,1741129705,1742428957,1743738582,1745060229,1746392300,1747734947,1749085059,1750441404,1751799967,1753158635,1754513562,1755862499,1757202784,1758532829,1759851740,1761159123,1762455911,1763743002,1765022743,1766297054,1767569058,1768841164,1770116596,1771397583,1772686808,1773985625,1775295667,1776616815,1777949391,1779291472,1780642170,1781997938,1783357085,1784715253,1786070633,1787419196,1788759745,1790089581,1791408625,1792715944,1794012792,1795299868,1796579619,1797853883,1799125866,1800397859,1801673246,1802954076,1804243240,1805541950,1806851917,1808173126,1809505579,1810847962,1812198416,1813554718,1814913492,1816272347,1817627274,1818976527,1820316574,1821646971,1822965492,1824273240,1825569581,1826857041,1828136329,1829410998,1830682546,1831954984,1833229943,1834511229,1835799956,1837099096,1838408653,1839730237,1841062399,1842405056,1843755427,1845111788,1846470686,1847829307,1849184538,1850533323,1851873796,1853203586,1854522578,1855829671,1857126503,1858413330,1859693148,1860967248,1862239383,1863511320,1864786914,1866067744,1867357123,1868655787,1869965969,1871287009,1872619733,1873961820,1875312666,1876668565,1878027812,1879386193,1880741572,1882090364,1883430781,1884760778,1886079555,1887386955,1888683473,1889970631,1891250095,1892524514,1893796302,1895068531,1896343776,1897624864,1898913866,1900212793,1901522528,1902843883,1904176046,1905518533,1906868738,1908225146,1909583797,1910942762,1912297707,1913647052,1914987237,1916317681,1917636384,1918944101,1920240591,1921527944,1922807325,1924081846,1925353456,1926625746,1927900766,1929181923,1930470729,1931769727,1933079370,1934400741,1935732979,1937075343,1938425809,1939781896,1941140999,1942499496,1943855044,1945203867,1946544677,1947874585,1949193845,1950501035,1951798008,1953084825,1954364641,1955638602,1956910634,1958182346,1959457805,1960738401,1962027681,1963326181,1964636324,1965957316,1967290020,1968632168,1969982947,1971338994,1972698122,1974056753,1975412030,1976761168,1978101541,1979431921,1980750691,1982058441,1983354924,1984642340,1985921668,1987196225,1988467755,1989740035,1991014964,1992296097,1993584809,1994883832,1996193356,1997514855,1998846894,2000189526,2001539674,2002896136,2004254765,2005613637,2006968613,2008317778,2009658089,2010988368,2012307304,2013614925,2014911733,2016199038,2017478763,2018753228,2020025139,2021297305,2022572538,2023853479,2025142411,2026441118,2027750842,2029071891,2030404217,2031746281,2033096869,2034452719,2035811926,2037170249,2038525814,2039874533,2041215307,2042545242,2043864495,2045171855,2046468888,2047755965,2049035878,2050310108,2051582211,2052854124,2054129561,2055410238,2056699366,2057997831,2059307700,2060628608,2061960965,2063303075,2064653518,2066009655,2067368537,2068727389,2070082529,2071431918,2072772218,2074102803,2075421528,2076729437,2078025899,2079313461,2080592798,2081867520,2083139079,2084411530,2085686463,2086967725,2088256377,2089555438,2090864844,2092186298,2093518232,2094860761,2096210888,2097567202,2098925921,2100284628,2101639803,2102988812,2104329366,2105659467,2106978606,2108285998,2109582947,2110869986,2112149830,2113424041,2114696112,2115968093,2117243565,2118524403,2119813639,2121112284,2122422310,2123743287,2125075837,2126417797,2127768479,2129124214,2130483377,2131841624,2133197050,2134545789,2135886402,2137216454,2138535538,2139843061,2141139913,2142427172,2143706907,2144981333";   //1902-2037 24term
}

//------------------------------------------------------------------------
    /**
     * @function: fn_term02_data()
     * @return  : array
     * @brief:    get 24terms delta-t
     **/
function fn_term02_data() {
	return "0,1,3,4,5,6,8,9,10,12,13,15,16,17,18,19,20,21,21,22,22,23,23,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,25,25,26,26,27,27,28,28,29,29,30,30,30,31,31,31,32,32,33,33,34,34,34,35,36,37,37,38,39,40,41,42,43,44,45,46,48,49,50,51,51,52,53,54,54,55,55,56,56,57,58,58,59,60,61,62,62,63,63,64,65,66,67,68,69,70,71,71,72,73,74,75,76,77,77,78,79,80,81,82,83,84,85,86,87,88,89,89,90,92,93,94,95,96,97,97,97";   // delta-t 1902-2037
}

//------------------------------------------------------------------------
    /**
     * @function: fn_get_term24($pYear)
     * @return  : array
     * @brief:    get date of 24terms
     **/
function fn_get_term24($pYear) {
    $aHoli = null;
	static $terms_name = array
    ('소한','대한','입춘','우수','경칩','춘분','청명','곡우','입하','소만','망종','하지',
	 '소서','대서','입추','처서','백로','추분','한로','상강','입동','소설','대설','동지');
	$arr_term01 = explode(",",planner123_main::fn_term01_data());
	$arr_term02 = explode(",",planner123_main::fn_term02_data());
	$j = ($pYear-1902)*24;
	for($k=$j, $ind50=0; $k<($j+24); $k++,$ind50++){
	//	$m = $m+1;
	//	$out .= "[".$m."]".date("Y-n-j H:i:s",$arr_term01[$k])."<br/>";
		$ind51= date("n",$arr_term01[$k]+date('Z')+zgap()-$arr_term02[$pYear-1902]);
		$ind52= date("j",$arr_term01[$k]+date('Z')+zgap()-$arr_term02[$pYear-1902]);
		$aHoli[$ind51][$ind52] .= $terms_name[$ind50];
	}
	return $aHoli;
}

//------------------------------------------------------------------------
    /**
     * @function: fn_get_dongji($pYear)
     * @return  : int
     * @brief:    get dongji time stamp
     **/
function fn_get_dongji($pYear) {
	if($pYear >=1903 && $pYear <=2037) {
		$arr_term01 = explode(",",planner123_main::fn_term01_data());
		$arr_term02 = explode(",",planner123_main::fn_term02_data());
		$j = ($pYear-1902)*24+23;
		$out = $arr_term01[$j]+date('Z')+zgap()-$arr_term02[$pYear-1902];
	}
	return $out;
}

//=========================================================================
} // end of class

?>