<?php
##
## @Package:    xe_official_planner123 (board skin)
## @File name:	class.planner123_holiday_kor.php
## @Author:     Keysung Chung (keysung2004@gmail.com)
## @Copyright:  © 2009 Keysung Chung(keysung2004@gmail.com)
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
##  - 2011.08.01 : Ver 4.0.0. (월단위에서 시작 끝이 있는 기간 개념으로 변경)
##  - 2010.09.10 : 대한민국 휴일및 기념일 함수 분리함.
##	 * (class.planner123_main.php 파일에서 휴일과 기념일만 분리한 파일로
##	 * 편리를 위해 분리하나 사용은 class.planner123_main.php 파일과 같이 사용해야됨).
##
//--------------------------------------------------------------------------------

class planner123_holiday extends Object
{

//--------------------------------------------------------------------------------------
    /**
     * @function: fn_HolidayChk($dispStart_stamp, $dispEnd_stamp)
     * @return  : array
     * @brief:    휴일 여부
     **/
Function fn_HolidayChk($dispStart_stamp, $dispEnd_stamp) {
	/******************************************************
	*휴일은 음력에서 1.1(설)/8.15(추석)/4.8(석가탄신일) 이 있으며
	*양력으로 1.1(신정)/3.1(삼일절)/5.5(어린이날)/6.6(현충일)/8.15(광복절)/10.3(개천절)/12.25(성탄절) 이다.
	  (4.5: 2006년부터 식목일은 법정 공휴일에서 법정기념일로 바뀜)
	  (7.17: 2008년 부터 제헌절은 법정 공휴일에서 법정기념일로 바뀜)
	*설과 추석은 앞뒤로 하루씩 휴일이 더해진다.
	*******************************************************/
	$aHoli = null;
		$dispStart_stamp -= 86400 * 1;	//연휴를 고려하여 1일 이전부터 계산(중국3일)
		$dispEnd_stamp += 86400 * 1;	//연휴를 고려하여 1일 이후 까지 계산(중국2일)
		$sYear = date("Y", $dispStart_stamp);
		$sMonth = date("n", $dispStart_stamp);
		$sMMCount = $sYear*12 + $sMonth;
		$eYear = date("Y", $dispEnd_stamp);
		$eMonth = date("n", $dispEnd_stamp);
		$eMMCount = $eYear*12 + $eMonth;

//대한민국 휴일 *********************************************************************
	//양력 휴일(국경일,기념일중 휴일)
	$aHoli[1][1] .= "신정 ";
	$aHoli[3][1] .= "삼일절 ";
	$aHoli[5][5] .= "어린이날 ";
	$aHoli[6][6] .= "현충일 ";
//	$aHoli[7][17] .= "제헌절 ";	// 국경일이나 휴일아님
	$aHoli[8][15] .= "광복절 ";
	$aHoli[10][3] .= "개천절 ";
	if($sYear >= 2013){
		$aHoli[10][9] .= "한글날 ";	// 국경일이나 휴일아님 => 2013년 부터 법정공휴일
	}
	$aHoli[12][25] .= "성탄절 ";

	//음력휴일
	//(설날)
	$temp_lunDateArr = explode("-",planner123_main::fn_lun2sol($sYear,1,1));
	$tmp_lunStmp = mktime(0, 0, 0,$temp_lunDateArr[1], $temp_lunDateArr[2], $temp_lunDateArr[0]);
	$temp01 = null;
	if ($tmp_lunStmp >= $dispStart_stamp && $tmp_lunStmp <= $dispEnd_stamp) {
		$temp01 = $temp_lunDateArr;
	} else {
		$temp_lunDateArr = explode("-",planner123_main::fn_lun2sol($eYear,1,1));
		$tmp_lunStmp = mktime(0, 0, 0,$temp_lunDateArr[1], $temp_lunDateArr[2], $temp_lunDateArr[0]);
		if ($tmp_lunStmp >= $dispStart_stamp && $tmp_lunStmp <= $dispEnd_stamp) {
			$temp01 = $temp_lunDateArr;
		}
	}
	if (!empty($temp01)) {
		$iLunYmd =date("Y-n-j", mktime(0, 0, 0,$temp01[1], $temp01[2], $temp01[0]));
		$iLunYmdpre =date("Y-n-j", mktime(0, 0, 0,$temp01[1], $temp01[2]-1, $temp01[0]));
		$iLunYmdnext =date("Y-n-j", mktime(0, 0, 0,$temp01[1], $temp01[2]+1, $temp01[0]));
		$temp02 = explode("-",$iLunYmd);
		$aHoli[$temp02[1]][$temp02[2]] .= "설날";
		$temp02 = explode("-",$iLunYmdpre);
		$aHoli[$temp02[1]][$temp02[2]] .= "설연휴";
		$temp02 = explode("-",$iLunYmdnext);
		$aHoli[$temp02[1]][$temp02[2]] .= "설연휴";
	}

	//(추석)
	$temp_lunDateArr = explode("-",planner123_main::fn_lun2sol($sYear,8,15));
	$tmp_lunStmp = mktime(0, 0, 0,$temp_lunDateArr[1], $temp_lunDateArr[2], $temp_lunDateArr[0]);
	$temp01 = null;
	if ($tmp_lunStmp >= $dispStart_stamp && $tmp_lunStmp <= $dispEnd_stamp) {
		$temp01 = $temp_lunDateArr;
	} else {
		$temp_lunDateArr = explode("-",planner123_main::fn_lun2sol($eYear,8,15));
		$tmp_lunStmp = mktime(0, 0, 0,$temp_lunDateArr[1], $temp_lunDateArr[2], $temp_lunDateArr[0]);
		if ($tmp_lunStmp >= $dispStart_stamp && $tmp_lunStmp <= $dispEnd_stamp) {
			$temp01 = $temp_lunDateArr;
		}
	}
	if (!empty($temp01)) {
		$iLunYmd =date("Y-n-j", mktime(0, 0, 0,$temp01[1], $temp01[2], $temp01[0]));
		$iLunYmdpre =date("Y-n-j", mktime(0, 0, 0,$temp01[1], $temp01[2]-1, $temp01[0]));
		$iLunYmdnext =date("Y-n-j", mktime(0, 0, 0,$temp01[1], $temp01[2]+1, $temp01[0]));
		$temp02 = explode("-",$iLunYmd);
		$aHoli[$temp02[1]][$temp02[2]] .= "추석";
		$temp02 = explode("-",$iLunYmdpre);
		$aHoli[$temp02[1]][$temp02[2]] .= "추석연휴";
		$temp02 = explode("-",$iLunYmdnext);
		$aHoli[$temp02[1]][$temp02[2]] .= "추석연휴";
	}

	//(석가탄신일)
	$temp_lunDateArr = explode("-",planner123_main::fn_lun2sol($sYear,4,8));
	$tmp_lunStmp = mktime(0, 0, 0,$temp_lunDateArr[1], $temp_lunDateArr[2], $temp_lunDateArr[0]);
	$temp01 = null;
	if ($tmp_lunStmp >= $dispStart_stamp && $tmp_lunStmp <= $dispEnd_stamp) {
		$temp01 = $temp_lunDateArr;
	} else {
		$temp_lunDateArr = explode("-",planner123_main::fn_lun2sol($eYear,4,8));
		$tmp_lunStmp = mktime(0, 0, 0,$temp_lunDateArr[1], $temp_lunDateArr[2], $temp_lunDateArr[0]);
		if ($tmp_lunStmp >= $dispStart_stamp && $tmp_lunStmp <= $dispEnd_stamp) {
			$temp01 = $temp_lunDateArr;
		}
	}
	if (!empty($temp01)) {
		$iLunYmd =date("Y-n-j", mktime(0, 0, 0,$temp01[1], $temp01[2], $temp01[0]));
		$temp02 = explode("-",$iLunYmd);
		$aHoli[$temp02[1]][$temp02[2]] .= "석가탄신일";
 	}

	return $aHoli;
}

//------------------------------------------------------------------------
    /**
     * @function: fn_MemdayChk($dispStart_stamp, $dispEnd_stamp)
     * @return  : boolean
     * @brief:    기념일 여부
     **/
Function fn_MemdayChk($dispStart_stamp, $dispEnd_stamp) {
    /******************************************************
    *법정 기념일과 공휴일이 아닌 국경일
    *음력 기념일 등
    *******************************************************/
    $aHoli = null;
		$dispStart_stamp -= 86400 * 1;	//연휴를 고려하여 1일 이전부터 계산
		$dispEnd_stamp += 86400 * 1;	//연휴를 고려하여 1일 이후 까지 계산
		$sYear = date("Y", $dispStart_stamp);
		$sMonth = date("n", $dispStart_stamp);
		$sMMCount = $sYear*12 + $sMonth;
		$eYear = date("Y", $dispEnd_stamp);
		$eMonth = date("n", $dispEnd_stamp);
		$eMMCount = $eYear*12 + $eMonth;

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
//

if ($aMoon) {
    for ($x=$dispStart_stamp; $x <= $dispEnd_stamp; $x +=86400 ) {   // 해당기간만
		$wrkYY = date("Y", $x);
		$wrkMM = date("n", $x);
		$wrkDD = date("j", $x);
        $iLunYmd = explode("-",planner123_main::fn_sol2lun($wrkYY,$wrkMM,$wrkDD));   // 해당일 음력 구하고
        $iLunYY = $iLunYmd[0];
        $iLunMM = $iLunYmd[1];
        $iLunDD = $iLunYmd[2];
        $iYundal = $iLunYmd[5];
		if($aMoon[$iLunMM][$iLunDD][0] != null ) { // 음력(평달) 기념일
			if(!$iYundal) {
			$aHoli[$wrkMM][$wrkDD] .= $aMoon[$iLunMM][$iLunDD][0];
			}
		}
		if($aMoon[$iLunMM][$iLunDD][1] != null ) { // 음력(윤달) 기념일.
			if($iYundal) {
				$aHoli[$wrkMM][$wrkDD] .= $aMoon[$iLunMM][$iLunDD][1];
			}
		}
		if($aMoon[$iLunMM][$iLunDD][2] != null ) { // 음력(윤달)및 평달 기념일 (윤달 있으면 윤달, 없으면 평달)
			$tmp_arr = explode("-",planner123_main::fn_lun2sol($iLunYY,$iLunMM,$iLunDD));  // 당해년도에 윤달있는지...[4]==1
			if($tmp_arr[4] == 1) {
				if($iYundal) {
				$aHoli[$wrkMM][$wrkDD] .= $aMoon[$iLunMM][$iLunDD][2];
				}
			} else {
				if(!$iYundal) {
				$aHoli[$wrkMM][$wrkDD] .= $aMoon[$iLunMM][$iLunDD][2];
				}
			}
		}
	}
}

// 양력기념일 (기념일,국경일,법정기념일 - 휴일아닌경우)
// 개인 기념일(양력)
//  $aHoli[5][29] .= " 큰딸생일";
//  $aHoli[6][9] .= " 작은딸생일";

// 공공 기념일(양력)
    $aHoli[3][3] .= " 납세자의날";
    //$aHoli[3][xx] .= " 상공의날";          //3월셋째 수요일
    //$aHoli[4][xx] .= " 향토예비군의날";   //4월첫째 금요일
    $aHoli[4][5] .= " 식목일";
    $aHoli[4][7] .= " 보건의날";
    $aHoli[4][13] .= " 임시정부수립";
    $aHoli[4][19] .= " 4.19기념일";
    $aHoli[4][20] .= " 장애인의날";
    $aHoli[4][21] .= " 과학의날";
    $aHoli[4][22] .= " 정보통신의날";
    $aHoli[4][22] .= " 새마을의 날";
    $aHoli[4][25] .= " 법의날";
    $aHoli[4][28] .= " 충무공탄신일";
    $aHoli[5][1] .= " 근로자의날";
//  $aHoli[5][5] .= " 어린이날";
    $aHoli[5][8] .= " 어버이날";
    $aHoli[5][11] .= " 입양의날";  //개별법
    $aHoli[5][15] .= " 스승의날";
    $aHoli[5][15] .= " 가정의날";  //개별법
    $aHoli[5][18] .= " 5.18기념일";
    $aHoli[5][19] .= " 발명의날";  //개별법
    $aHoli[5][20] .= " 세계인의날"; //개별법
    $aHoli[5][21] .= " 부부의날";
    $aHoli[5][25] .= " 방재의날";  //개별법
    //$aHoli[5][xx] .= " 성년의날";      //5월셋째 월요일
    $aHoli[5][31] .= " 바다의날";
    $aHoli[6][5] .= " 환경의날";
//  $aHoli[6][6] .= " 현충일";
    $aHoli[6][10] .= " 6.10기념일";
    $aHoli[6][25] .= " 6.25사변일";
    $aHoli[7][17] .= " 제헌절";       //국경일
    $aHoli[9][1] .= " 통계의날";    //개별법
    $aHoli[9][4] .= " 태권도의날";   //개별법
    $aHoli[9][7] .= " 사회복지의날";  //개별법
    $aHoli[9][18] .= " 철도의날";
    $aHoli[10][1] .= " 국군의날";
    $aHoli[10][2] .= " 노인의날";
    $aHoli[10][5] .= " 세계한인의날";
    $aHoli[10][8] .= " 재향군인의날";
	if($sYear < 2013){
		$aHoli[10][9] .= " 한글날";    //국경일: 2013년 부터 법정공휴일로 변경
	}
    $aHoli[10][10] .= " 임산부의날";   //개별법
    $aHoli[10][15] .= " 체육의날";
    //$aHoli[10][xx] .= " 문화의날";     //10월셋째 토요일
    $aHoli[10][21] .= " 경찰의날";
    $aHoli[10][24] .= " 국제연합일";
    $aHoli[10][28] .= " 교정의날";
    //$aHoli[10][xx] .= " 저축의날";     //10월마지막화요일
    $aHoli[11][3] .= " 학생독립운동기념일";
    $aHoli[11][9] .= " 소방의날";  //개별법
    $aHoli[11][11] .= " 농업인의날";
    $aHoli[11][17] .= " 순국선열의날";
    $aHoli[11][30] .= " 무역의날";
    $aHoli[12][3] .= " 소비자의날";
    $aHoli[12][5] .= " 자원봉사자의날";   //개별법

    // 몇월 몇번째 무슨요일 형식 기념일 설정 (예: 상공의날- 3월 셋째 수요일은 ($sYear, 월=3, 일=3, 수=3) 형식으로)
	For($wrkYY = $sYear; $wrkYY <= $eYear; $wrkYY++) {
	    $temp02 = explode("-",planner123_main::fn_nsweekday($wrkYY, 3, 3, 3));
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
			$aHoli[$temp02[1]][$temp02[2]] .= " 상공의날";
		}
	    $temp02 = explode("-",planner123_main::fn_nsweekday($wrkYY, 4, 1, 5));
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
		    $aHoli[$temp02[1]][$temp02[2]] .= " 향군의날";
		}
	    $temp02 = explode("-",planner123_main::fn_nsweekday($wrkYY, 5, 3, 1));
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
		    $aHoli[$temp02[1]][$temp02[2]] .= " 성년의날";
		}
	    $temp02 = explode("-",planner123_main::fn_nsweekday($wrkYY, 10, 3, 6));
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
		    $aHoli[$temp02[1]][$temp02[2]] .= " 문화의날";
		}
	}

    // 몇월, 끝에서 몇번째 주, 무슨요일 형식 기념일 설정 (예: 저축의날- 10월마지막화요일)
	For($wrkYY = $sYear; $wrkYY <= $eYear; $wrkYY++) {
		$temp02 = explode("-",planner123_main::fn_nslastweekday($wrkYY, 10, 1, 2));
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
			$aHoli[$temp02[1]][$temp02[2]] .= " 저축의날";
		}
	}

/***
    // 몇월 몇번째 무슨요일 형식 기념일 설정 (예: 상공의날- 3월 셋째 수요일은 ($sYear, 월=3, 일=3, 수=3) 형식으로)
	For($wrkYY = $sYear; $wrkYY <= $eYear; $wrkYY++) {
	    $temp02 = explode("-",planner123_main::fn_nsweekday($wrkYY, 3, 3, 3));
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
			$aHoli[$temp02[1]][$temp02[2]] .= " 상공의날";
		}
	}
***/
/***
    // 몇월, 몇번째 주, 무슨요일 형식 기념일 설정 (예: 10월 4번째주 금요일) -현재 해당 기념일 없음.
    //테스트용 (예: 10월 4쨰주 금요일)
	For($wrkYY = $sYear; $wrkYY <= $eYear; $wrkYY++) {
	    $temp02 = explode("-",planner123_main::fn_nsweeknsweekday($wrkYY, 10, 4, 5));
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
			$aHoli[$temp02[1]][$temp02[2]] .= " 현재없음테스트";
		}
	}
***/
/***
    // 매월, 끝에서 몇번째 주, 무슨요일 형식 기념일 설정
    // 테스트용(마지막 주 화요일)
	For($x = $sMMCount; $x <= $eMMCount; $x++) {
		$wrkYY = floor(($x-1)/12);	// 년
		$wrkMM = ($x-1)%12 + 1;	// 월
		$wrkDD = $startDD;	// 일
	    $temp02 = explode("-",planner123_main::fn_nslastweekday($wrkYY, $wrkMM, 1, 2));
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
			$aHoli[$temp02[1]][$temp02[2]] .= " 마지막 화요일".$temp02[0].$temp02[1].$temp02[2];
		}
	}
***/
/***
    // 매월, 몇번째 주, 무슨요일 형식 기념일 설정
    // 테스트용( 4번째주 금요일)
	For($x = $sMMCount; $x <= $eMMCount; $x++) {
		$wrkYY = floor(($x-1)/12);	// 년
		$wrkMM = ($x-1)%12 + 1;	// 월
		$wrkDD = $startDD;	// 일
		$temp02 = explode("-",planner123_main::fn_nsweeknsweekday($wrkYY, $wrkMM, 4, 5));
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
			$aHoli[$temp02[1]][$temp02[2]] .= " <font color=brown>결산4번째주금</font>";
		}
	}
***/
/***
    // 매월, 몇번째 무슨요일 형식 기념일 설정 (예-옵션만기일: 매월 2번째 목요일)
	For($x = $sMMCount; $x <= $eMMCount; $x++) {
		$wrkYY = floor(($x-1)/12);	// 년
		$wrkMM = ($x-1)%12 + 1;	// 월
		$wrkDD = $startDD;	// 일
		$temp02 = explode("-",planner123_main::fn_nsweekday($wrkYY, $wrkMM, 2, 4));
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
		    $aHoli[$temp02[1]][$temp02[2]] .= " <font color=brown>옵션만기일</font>";
		}
	}
***/
/***
    // 매월, 음력 날자 끝자리가 같은 형식 (예: 손없는날: 음력일자가 9 또는 0 으로 끝나는날)
	For	($x = $dispStart_stamp; $x <= $dispEnd_stamp; $x += 86400) {	// 출력 기간
		$wrkYY = date("Y", $x);	// 일자-년
		$wrkMM = date("n", $x);	// 일자-월
		$wrkDD = date("j", $x);	// 일자-일
		$temp02 = explode('-',planner123_main::fn_sol2lun($wrkYY,$wrkMM,$wrkDD));  // 각 출력일의 음력날자
		if(substr($temp02[2], -1) == 9 || substr($temp02[2], -1) == 0) {
			$aHoli[$wrkMM][$wrkDD] .=  " 손없는날";
		}
	}
***/

    //(부활절)
	For($wrkYY = $sYear; $wrkYY <= $eYear; $wrkYY++) {
	    if (function_exists('easter_days')) {	// 부활절함수 있으면...
			$temp01 = explode("-",planner123_main::fn_easterday($wrkYY));
		} else {
			$temp01 = explode("-",planner123_main::fn_easterday_2($wrkYY));
		}
		$iYmd =date("Y-n-j", mktime(0, 0, 0,$temp01[1], $temp01[2], $temp01[0]));
	    $temp02 = explode("-",$iYmd);
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
		    $aHoli[$temp02[1]][$temp02[2]] .= " 부활절";
		}
	}

/***
// 이슬람력 기념일  $arr_Islam[월][일] 형식으로....
	$arr_Islam[1][1] .= "<font color=blue>Islamic New Year</font><BR>";	// (Islamic New Year)
	$arr_Islam[1][10] .= "<font color=blue>Ashura</font><BR>";  // (10th day of Muharram)
	$arr_Islam[3][12] .= "<font color=blue>Mawlid an Nabi</font><BR>";	// (Muhammad's Birthday)
	$arr_Islam[7][26] .= "<font color=blue>Laylat al Miraj</font><BR>";  //
	$arr_Islam[8][14] .= "<font color=blue>Laylat al Baraat</font><BR>";	//( Night of Emancipation)
	$arr_Islam[9][1] .= "<font color=blue>Ramadan begins</font><BR>";  // (Ramadan begins)
	$arr_Islam[9][26] .= "<font color=blue>Laylat al Qadr</font><BR>";  // (Holy night)
	$arr_Islam[10][1] .= "<font color=blue>Eid al Fitr</font><BR>";	// (Ramadan ends)
	$arr_Islam[12][8] .= "<font color=blue>Hajj days</font><BR>";  // (Hajj days)
	$arr_Islam[12][10] .= "<font color=blue>Eid al Adha</font><BR>";  // (Festival of Sacrifice)

    for($x=$dispStart_stamp; $x <= $dispEnd_stamp; $x +=86400 ) {   // 기간 동안만
		$wrkYY = date("Y", $x);
		$wrkMM = date("n", $x);
		$wrkDD = date("j", $x);
		$islam_date = planner123_main::fn_GregorianToIslamic_ksc($wrkMM, $wrkDD, $wrkYY);
		$wrk_arr = explode("-",$islam_date);
		$islam_yy = $wrk_arr[0];
		$islam_mm = $wrk_arr[1];
		$islam_dd = $wrk_arr[2];
		if($arr_Islam[$islam_mm][$islam_dd]) {
			$aHoli[$wrkMM][$wrkDD] .= $arr_Islam[$islam_mm][$islam_dd];
		}
	}
***/
//미국 휴일 *********************************************************************
	// 양력 휴일(국경일,기념일중 휴일)
	$aHoli[1][1] .= "<font color=brown>(미)New Year's Day</font> ";
	$aHoli[7][4] .= "<font color=brown>(미)Independence Day</font> ";
	$aHoli[11][11] .= "<font color=brown>(미)Veteran's Day</font> ";
	$aHoli[12][25] .= "<font color=brown>(미)Christmas</font> ";

    // 몇월 몇번째 무슨요일 형식 기념일 설정 (예: 상공의날- 3월 셋째 수요일은 ($sYear, 월=3, 일=3, 수=3) 형식으로)
	For($wrkYY = $sYear; $wrkYY <= $eYear; $wrkYY++) {
	    $temp02 = explode("-",planner123_main::fn_nsweekday($wrkYY, 1, 3, 1));  // 1월 3째 월요일
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
			$aHoli[$temp02[1]][$temp02[2]] .= " <font color=brown>(미)Martin Luther King's Day</font>";
		}
	    $temp02 = explode("-",planner123_main::fn_nsweekday($wrkYY, 2, 3, 1));  // 2월 3째 월요일
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
		    $aHoli[$temp02[1]][$temp02[2]] .= " <font color=brown>(미)President Day</font>";
		}
	    $temp02 = explode("-",planner123_main::fn_nsweekday($wrkYY, 9, 1, 1));  // 9월 1째 월요일
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
		    $aHoli[$temp02[1]][$temp02[2]] .= " <font color=brown>(미)Labor Day</font>";
		}
	    $temp02 = explode("-",planner123_main::fn_nsweekday($wrkYY, 10, 2, 1));  // 10월 2째 월요일
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
		    $aHoli[$temp02[1]][$temp02[2]] .= " <font color=brown>(미)Columbus Day</font>";
		}
	    $temp02 = explode("-",planner123_main::fn_nsweekday($wrkYY, 11, 4, 4));  // 11월 4째 목요일
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
		    $aHoli[$temp02[1]][$temp02[2]] .= " <font color=brown>(미)Thanksgiving Day</font>";
		}
	}

    // 몇월, 끝에서 몇번째 무슨요일 형식 기념일 설정  (예: Victoria Day=끝에서 1번째 월요일)
 	For($wrkYY = $sYear; $wrkYY <= $eYear; $wrkYY++) {
	    $temp02 = explode("-",planner123_main::fn_nslastweekday($wrkYY, 5, 1, 1));
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
			$aHoli[$temp02[1]][$temp02[2]] .= " <font color=brown>(미)Memorial Day</font>";
		}
	}

    //(부활절)
 	For($wrkYY = $sYear; $wrkYY <= $eYear; $wrkYY++) {
	    if (function_exists('easter_days')) {	// 부활절함수 있으면...
			$temp01 = explode("-",planner123_main::fn_easterday($wrkYY));
		} else {
			$temp01 = explode("-",planner123_main::fn_easterday_2($wrkYY));
		}
		$iYmd =date("Y-n-j", mktime(0, 0, 0,$temp01[1], $temp01[2], $temp01[0]));
	    $temp02 = explode("-",$iYmd);
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
		    $aHoli[$temp02[1]][$temp02[2]] .= " <font color=brown>(미)Easter Sunday</font>";
		}
	}

	return $aHoli;

}  // end of function
//------------------------------------------------------------------------

//========================================================================
} // end of class

?>
