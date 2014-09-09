<?php
##
## @Package:    xe_official_planner123 (board skin)
## @File name:	class.planner123_holiday_can.php
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
##  - Keysung Chung
##  - http://chungfamily.woweb.net/
##
## [changes]
##  - 2010.10.10 : 캐나다 휴일및 기념일 함수.
##	 * (class.planner123_main.php 파일에서 휴일과 기념일만 분리한 파일로
##	 * 편리를 위해 분리하나 사용은 class.planner123_main.php 파일과 같이 사용해야됨).
##
//--------------------------------------------------------------------------------

class planner123_holiday extends Object
{

//--------------------------------------------------------------------------------------
    /**
     * @function: fn_HolidayChk($pYear, $pMonth, $skinpath)
     * @return  : array
     * @brief:    휴일 여부
     **/
Function fn_HolidayChk($pYear, $pMonth, $skinpath) {
	/******************************************************
	*휴일은 음력에서 1.1(설)/8.15(추석)/4.8(석가탄신일) 이 있으며
	*양력으로 1.1(신정)/3.1(삼일절)/5.5(어린이날)/6.6(현충일)/8.15(광복절)/10.3(개천절)/12.25(성탄절) 이다.  
	  (4.5: 2006년부터 식목일은 법정 공휴일에서 법정기념일 기년일로바뀜)
	  (7.17: 2008년 부터 제헌절은 법정 공휴일에서 법정기념일 기년일로바뀜)
	*설과 추석은 앞뒤로 하루씩 휴일이 더해진다.
	*******************************************************/
	$aHoli = null;

//Canada 휴일 *********************************************************************
	// 양력 휴일(국경일,기념일중 휴일) 
	$aHoli[1][1] .= "New Year's Day ";
	$aHoli[7][1] .= "Canada Day ";
	$aHoli[11][11] .= "Remembrance Day ";
	$aHoli[12][25] .= "Christmas ";
	$aHoli[12][26] .= "Boxing Day ";

    // 몇월 몇번째 무슨요일 형식 기념일 설정 (예: 상공의날- 3월 셋째 수요일은 ($pYear, 월=3, 일=3, 수=3) 형식으로)
    $temp02 = explode("-",planner123_main::fn_nsweekday($pYear, 8, 1, 1));  // 8월 8째 월요일
    $aHoli[$temp02[1]][$temp02[2]] .= " Civic Holiday";
    $temp02 = explode("-",planner123_main::fn_nsweekday($pYear, 9, 1, 1));  // 9월 1째 월요일
    $aHoli[$temp02[1]][$temp02[2]] .= " Labor Day";
    $temp02 = explode("-",planner123_main::fn_nsweekday($pYear, 10, 2, 1));  // 10월 2째 월요일
    $aHoli[$temp02[1]][$temp02[2]] .= " Thanksgiving Day";

    // 몇월, 끝에서 몇번째 무슨요일 형식 기념일 설정  (예: Victoria Day=끝에서 1번째 월요일)
    $temp02 = explode("-",planner123_main::fn_nslastweekday($pYear, 5, 2, 1));	// 5월 마지막 2째주 월요일
    $aHoli[$temp02[1]][$temp02[2]] .= " Victoria Day";

    //(부활절)
    if (function_exists('easter_days')) {	// 부활절함수 있으면...
		$temp01 = explode("-",planner123_main::fn_easterday($pYear));
	} else {
		$temp01 = explode("-",planner123_main::fn_easterday_2($pYear));
	}
    $iYmd =date("Y-n-j", mktime(0, 0, 0,$temp01[1], $temp01[2], $temp01[0]));
    $iYmdpre =date("Y-n-j", mktime(0, 0, 0,$temp01[1], $temp01[2]-2, $temp01[0]));
    $iYmdnext =date("Y-n-j", mktime(0, 0, 0,$temp01[1], $temp01[2]+1, $temp01[0]));

	$temp02 = explode("-",$iYmdpre);
	$aHoli[$temp02[1]][$temp02[2]] .= "Good Friday";
    $temp02 = explode("-",$iYmd);
    $aHoli[$temp02[1]][$temp02[2]] .= " Easter Sunday";
	$temp02 = explode("-",$iYmdnext);
	$aHoli[$temp02[1]][$temp02[2]] .= "Easter Monday";

	return $aHoli;
}

//------------------------------------------------------------------------
    /**
     * @function: fn_MemdayChk($pYear, $pMonth, $skinpath)
     * @return  : boolean
     * @brief:    기념일 여부
     **/
Function fn_MemdayChk($pYear, $pMonth, $skinpath) {
    /******************************************************
    *법정 기념일과 공휴일이 아닌 국경일
    *음력 기념일 등
    *******************************************************/
    $aHoli = null;

//음력 기념일  $aMoon[월][일][평달=0, 윤달=1, 윤달및평달=2] 형식으로....
//음력은 일년에 같은 월 같은 날이 두번 들어 있을 수 있음.
//  $aMoon[11][9][0] .= "<B>조부기일</B><BR>";  // 음력11월 9일 (평달)
//  $aMoon[8][26][0] .= "조카생일<BR>";         // 음력8월 26일 (평달)
//  $aMoon[5][16][1] .= " <B>**윤달테스트**</B>";    // 음력윤달 (윤달만 적용)
//  $aMoon[3][20][2] .= " <B>**윤달및평달테스트**</B>";    // 윤달및 평달: (윤달, 윤달 없으면 평달)
//  $aMoon[11][24][0] .= " <B>**중복테스트**</B>";  // 1년에 두번 예:2008년

// 개인 기념일(음력)
//  $aMoon[11][9][0] .= "<B>조부기일</B><BR>";  // 음력11월 9일 (평달)

// 공공 기념일(음력)
// 없음

if ($aMoon) {
    $iLunYmd_arr = planner123_main::fn_sol2lun_ary($pYear, $pMonth); // 당월 양력에 해당되는 음력일자 어레이 얻기
    foreach($aMoon as $key1 => $value1) {
		foreach($value1 as $key2 => $value2) {
            for($k=$pMonth; $k < $pMonth+1; $k++ ) {
                for($d=1; $d < 32; $d++ ) {
                    $iLunYmd = explode("-",$iLunYmd_arr[$k][$d]);   // 음력 어레이를 읽어서
                    $iLunMM = $iLunYmd[1];
                    $iLunDD = $iLunYmd[2];
                    $Yundal = $iLunYmd[3];

					if ($iLunMM == $key1  && $iLunDD == $key2) {
	                    if ($aMoon[$key1][$key2][0] != null ) {     // 음력(평달) 기념일에 해당되는 양력 날자에..
		                    if ($iLunMM == $key1  && $iLunDD == $key2 && $Yundal == null ) {
			                    $aHoli[$k][$d] .= $aMoon[$key1][$key2][0];
				            }
					    } elseif ($aMoon[$key1][$key2][1] != null ) {     // 음력(윤달) 기념일에 해당되는 양력 날자에..
						    if ($iLunMM == $key1  && $iLunDD == $key2 && $Yundal != null ) {
							    $aHoli[$k][$d] .= $aMoon[$key1][$key2][1];
	                        }
		                } elseif ($aMoon[$key1][$key2][2] != null ) {     // 음력(윤달)및 평달: 윤달 있으면 윤달, 없으면 평달 기념일에 해당되는 양력 날자에..
							$tmp_arr = explode("-",planner123_main::fn_lun2sol($pYear,$key1,$key2));  // 당월에 윤달있는지...[4]==1
							if ($tmp_arr[4] == 1) {
								if ($iLunMM == $key1  && $iLunDD == $key2 && $Yundal != null ) {
						            $aHoli[$k][$d] .= $aMoon[$key1][$key2][2];
								}
							} else {
								if ($iLunMM == $key1  && $iLunDD == $key2 && $Yundal == null ) {
									$aHoli[$k][$d] .= $aMoon[$key1][$key2][2];
								}
							}
						}
					}   // end if
				}   // end for
            }       // end for
        }   // end foreach
    }   // end foreach
}   // end if


// 양력기념일 (기념일,국경일,법정기념일 - 휴일아닌경우)

// 개인기념일(양력)
//  $aHoli[5][29] = $aHoli[5][29]." 큰딸생일";
//  $aHoli[6][9] = $aHoli[6][9]." 작은딸생일";

// 공공기념일(양력)
    $aHoli[2][14] .= $aHoli[2][14]." St. Valentines Day";
    $aHoli[3][17] .= $aHoli[3][17]." St. Patrick's Day";
    $aHoli[10][31] .= $aHoli[10][31]." Halloween Day";

    // 몇월 몇번째 무슨요일 형식 기념일 설정 (예: 상공의날- 3월 셋째 수요일은 ($pYear, 월=3, 일=3, 수=3) 형식으로)
    $temp02 = explode("-",planner123_main::fn_nsweekday($pYear, 2, 3, 1));	// 2월 3째 월요일
    $aHoli[$temp02[1]][$temp02[2]] .= " Family Day";
    $temp02 = explode("-",planner123_main::fn_nsweekday($pYear, 5, 2, 0));	// 5월 2째 일요일
    $aHoli[$temp02[1]][$temp02[2]] .= " Mother's Day";
    $temp02 = explode("-",planner123_main::fn_nsweekday($pYear, 6, 3, 0));	// 6월 3째 일요일
    $aHoli[$temp02[1]][$temp02[2]] .= " Father's Day";
    $temp02 = explode("-",planner123_main::fn_nsweekday($pYear, 3, 2, 0));	// 3월 2째 일요일
    $aHoli[$temp02[1]][$temp02[2]] .= " <B>DST begins</B>";
    $temp02 = explode("-",planner123_main::fn_nsweekday($pYear, 11, 1, 0));	// 11월 1째 일요일
    $aHoli[$temp02[1]][$temp02[2]] .= " <B>DST ends</B>";

/***
    // 몇월, 끝에서 몇번째 주, 무슨요일 형식 기념일 설정 (예: 저축의날- 10월마지막화요일)
    //상공의날
    $temp02 = explode("-",planner123_main::fn_nslastweekday($pYear, 10, 1, 2));
    $aHoli[$temp02[1]][$temp02[2]] .= " 저축의날";
***/
/***
    // 몇월, 몇번째 주, 무슨요일 형식 기념일 설정 (예: 10월 4번째주 금요일) -현재 해당 기념일 없음.
    //테스트용 (예: 10월 4쨰주 금요일)
    $temp02 = explode("-",planner123_main::fn_nsweeknsweekday($pYear, 10, 4, 5));
    $aHoli[$temp02[1]][$temp02[2]] .= " 현재없음-테스트";
***/
/***
    // 매월, 몇번째 주, 무슨요일 형식 기념일 설정 (예: 4번째주 금요일) -현재 해당 기념일 없음.
    for ($k=1; $k < 13; $k++ ) {
    $temp02 = explode("-",planner123_main::fn_nsweeknsweekday($pYear, $k, 4, 5));
    $aHoli[$temp02[1]][$temp02[2]] .= " <font color=brown>월말결산</font>";
    }
***/
/***
    // 매월, 몇번째 무슨요일 형식 기념일 설정 (예-옵션만기일: 매월 2번째 목요일)
    for ($k=1; $k < 13; $k++ ) {
    $temp02 = explode("-",planner123_main::fn_nsweekday($pYear, $k, 2, 4));
    $aHoli[$temp02[1]][$temp02[2]] .= " <font color=brown>옵션만기일</font>";
    }
***/
/***
    // 몇월, 끝에서 몇번째 무슨요일 형식 기념일 설정  (예: Victoria Day=끝에서 2번째 월요일)
    $temp02 = explode("-",planner123_main::fn_nslastweekday($pYear, 5, 2, 1));
    $aHoli[$temp02[1]][$temp02[2]] .= " Victoria Day";
***/
/***
    //(부활절)
    if (function_exists('easter_days')) {	// 부활절함수 있으면...
		$temp01 = explode("-",planner123_main::fn_easterday($pYear));
	} else {
		$temp01 = explode("-",planner123_main::fn_easterday_2($pYear));
	}
    $iYmd =date("Y-n-j", mktime(0, 0, 0,$temp01[1], $temp01[2], $temp01[0]));
    $iYmdpre =date("Y-n-j", mktime(0, 0, 0,$temp01[1], $temp01[2]-2, $temp01[0]));
    $iYmdnext =date("Y-n-j", mktime(0, 0, 0,$temp01[1], $temp01[2]+1, $temp01[0]));

//  $temp02 = explode("-",$iYmdpre);
//  $aHoli[$temp02[1]][$temp02[2]] .= "Good Friday";
    $temp02 = explode("-",$iYmd);
    $aHoli[$temp02[1]][$temp02[2]] .= " 부활절";
//  $temp02 = explode("-",$iYmdnext);
//  $aHoli[$temp02[1]][$temp02[2]] .= "Easter Monday";
***/

//대한민국 휴일(국경일,기념일중 휴일) 
	//양력 휴일(국경일,기념일중 휴일)
	$aHoli[1][1] .= "<b>신정</b> ";
	$aHoli[3][1] .= "<b>삼일절</b> ";
	$aHoli[5][5] .= "<b>어린이날</b> ";
	$aHoli[6][6] .= "<b>현충일</b> ";
//	$aHoli[7][17] .= "제헌절 ";	// 국경일이나 휴일아님
	$aHoli[8][15] .= "<b>광복절</b> ";
	$aHoli[10][3] .= "<b>개천절</b> ";
//	$aHoli[10][9] .= "한글날 ";	// 국경일이나 휴일아님
	$aHoli[12][25] .= "<b>성탄절</b> ";

	//음력휴일
	//(설날)
	$temp01 = explode("-",planner123_main::fn_lun2sol($pYear,1,1));
	$iLunYmd =date("Y-n-j", mktime(0, 0, 0,$temp01[1], $temp01[2], $temp01[0]));
	$iLunYmdpre =date("Y-n-j", mktime(0, 0, 0,$temp01[1], $temp01[2]-1, $temp01[0]));
	$iLunYmdnext =date("Y-n-j", mktime(0, 0, 0,$temp01[1], $temp01[2]+1, $temp01[0]));
	$temp02 = explode("-",$iLunYmd);
	$aHoli[$temp02[1]][$temp02[2]] .= "<b>설날</b>";
	$temp02 = explode("-",$iLunYmdpre);
	$aHoli[$temp02[1]][$temp02[2]] .= "<b>설연휴</b>";
	$temp02 = explode("-",$iLunYmdnext);
	$aHoli[$temp02[1]][$temp02[2]] .= "<b>설연휴</b>";

	//(추석)
	$temp01 = explode("-",planner123_main::fn_lun2sol($pYear,8,15));
	$iLunYmd =date("Y-n-j", mktime(0, 0, 0,$temp01[1], $temp01[2], $temp01[0]));
	$iLunYmdpre =date("Y-n-j", mktime(0, 0, 0,$temp01[1], $temp01[2]-1, $temp01[0]));
	$iLunYmdnext =date("Y-n-j", mktime(0, 0, 0,$temp01[1], $temp01[2]+1, $temp01[0]));
	$temp02 = explode("-",$iLunYmd);
	$aHoli[$temp02[1]][$temp02[2]] .= "<b>추석</b>";
	$temp02 = explode("-",$iLunYmdpre);
	$aHoli[$temp02[1]][$temp02[2]] .= "<b>추석연휴</b>";
	$temp02 = explode("-",$iLunYmdnext);
	$aHoli[$temp02[1]][$temp02[2]] .= "<b>추석연휴</b>";

	//(석가탄신일)
	$temp01 = explode("-",planner123_main::fn_lun2sol($pYear,4,8));
	$iLunYmd =date("Y-n-j", mktime(0, 0, 0,$temp01[1], $temp01[2], $temp01[0]));
	$temp02 = explode("-",$iLunYmd);
	$aHoli[$temp02[1]][$temp02[2]] .= "<b>석가탄신일</b>";

	return $aHoli;
}
//------------------------------------------------------------------------

} // end of class

?>
