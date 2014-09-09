/**
##
## @Package:    xe_official_planner123
## @File name:	plannerXE123_skin.js
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
**/
/******************************************************************************/

/* calendar - 일정 출력 */
function doDisplaySchedule(schedule_html_json,rs_style)
{
	var $j = jQuery.noConflict();
	var tr_width, td_width, position, sg_height, cnt_low=0, cnt_day=0, udt_day=0, test_week=0,
		test_lenght=0,
		outhtml = "",
		low_pos_top = 0,
		row_height = 16,
		tr_width = $j("tr:#planner_week0").width();
//	var	arrayFromPHP = {$schedule_html_json};  // PHP에서 만든 일정 어레이를 받아서
	var	arrayFromPHP = schedule_html_json;  // PHP에서 만든 일정 어레이를 받아서
  if (arrayFromPHP && tr_width > 0) {  // 처리할 일정이 있으면 아래내용처리
	var cnt_sg = arrayFromPHP.length;		// 일정 갯수를 저장 하고

		// row 계산을위해 3차원 어레이를 만든다.
		row_arr = new Array(6);		// 한달 최대 6주
		for (i=0; i<7 ; i++) {		
			row_arr[i] = new Array(cnt_sg);	// 각주마다 예상되는 row 갯수만큼 어레이 만들고
			for (j=0; j<cnt_sg ; j++) {
				row_arr[i][j] = new Array(7);	// 각 row에 요일만큼 어레이 만든다
			}
		}

	$j.each(arrayFromPHP, function (i, elem) {	// 각 일정 마다
		var pln_week = parseInt(elem.week),		// 주 순서
			pln_weekday = parseInt(elem.weekday),	// 요일
			pln_date = parseInt(elem.date),			// 일정일자를 저장
			pln_length = parseInt(elem.length),		// 일정기간을 저장
			ind_find = "";

		// 일정기간 감안 일정이 위치할 포지션 계산.
		for (cnt_low=0; cnt_low<cnt_sg; cnt_low++) {	// 첫 row 부터 
			test_lenght = 0;	// 검사필드 클리어
			for (cnt_day = pln_weekday; cnt_day<(pln_weekday+pln_length); cnt_day++) {  //각 row의 해당 요일부터 일정기간까지 빈간 검사
				if (!row_arr[pln_week][cnt_low][cnt_day]) {		// 해당 row의 해당요일이 비어 있으면 계속 하고
					test_lenght += 1;						// 테스트 필드에 1을 더해놓고
					if (test_lenght >= pln_length) {		// 빈공간이 충분하면
						low_pos_top = row_height * cnt_low;	// 해당줄의 top 위치를 계산 

						position_week = $j("#week_schedule_" + pln_week).position();	// 주별 장기일정 콘테이너 위치 저장
						position = $j("#day_schedule_container_" + pln_date).position();	// 일별 하루일정 콘테이너 위치 저장
						outhtml += "<div id='wc-" + i + "-" + elem.pln_srl + "' class='drag' style='position: absolute; z-index:5; left:" 
							+ (position.left - position_week.left) + "px; top: " +( low_pos_top) + "px; width:" + (tr_width * pln_length/7) + "px;'>" + elem.html +"</div>";  //위치 계산후 코드생성
						ind_find = "Y";
						test_lenght = 0;

						if (pln_length == 1) {	// 하루일정일 경우 일정 높이구하고
						$j('#dummy').width(tr_width/7).empty();
						$j(elem.html).appendTo("#dummy");
						sg_height = ($j('#dummy > div').height());
						// alert (sg_height +" " +$j('#dummy').width());
						}

						// 하루일정으로 그림있는경우, 하루일정이면서 높이가 한줄 이상, 기념일, 휴일일경우 (V220: a를 c로 변경)
						if (pln_length == 1 && elem.image || pln_length == 1 && sg_height > row_height || elem.segtype == 'c' || elem.segtype == 'b') {
							if (elem.segtype == 'c' || elem.segtype == 'b')	{	// 기념일, 휴일
								outhtml = "<div>" + elem.html +"</div>";  // 코드생성
							} else {	// 일정
								outhtml = "<div id='dc-" + i + "-" + elem.pln_srl + "' class='drag' >" + elem.html +"</div>";  // 코드 생성 2
							}
							$j(outhtml).appendTo('#day_schedule_container_'+ pln_date);   // 일별 콘테이너 출력
							outhtml = null;
							break;		// 완료되어 for 빠져 나가고
						} else {
							$j(outhtml).appendTo('#week_schedule_'+ pln_week);   // 주별로 콘테이너 출력
							outhtml = null;
							 if (rs_style == "N") {  //반복일정표시 (rs_style : Y=제목한번, N=제목여러번, S=일정분리 (N일때만 해당 div있음)
								$j(".inside").css({"width":Math.floor(tr_width/7)});
								$j(".inside_end").css({"width":Math.floor(tr_width/7)-4});
							 }
							// 어레이에 해당칸을 사용했다는 표시해놓고
							for (udt_day = pln_weekday; udt_day<(pln_weekday + pln_length); udt_day++) {  
								row_arr[pln_week][cnt_low][udt_day] = "*";
							}
							// 장기일정 row 갯수 * row높이가 현재 space div 높이보다 클때 space 높이변경 (일별출력콘테이너 시작위치 조정위해)
							for (cnt_d=0; cnt_d<pln_length; cnt_d++) { 
								wrk_date = pln_date + cnt_d;
								cur_height = $j("#day_space_"+wrk_date).height()
								if ((cnt_low + 1)*row_height > cur_height ) {
									$j("#day_space_"+wrk_date).height((cnt_low + 1)*row_height);
								}
							}
							break;		// 완료되어 for 빠져 나가고
						}
					}
				}else{	// 해당 row의 해당요일이 비어있지않으면 다음 Row 검사위해 for 빠져나가고
					test_lenght = 0;
					break;
				}
			}
			if (ind_find == "Y") {	// 완료 되었으면 다음일정 처리를 위해 for 빠져 나간다.
				break;
			}
		}
	});
  }  // '처리할 일정이 있으면' 루프끝
//  div planner123을 visibility:hidden 으로 했을때 대비
$j('#planner123').css("visibility", "visible");	
//  drag & drop test
$j('.drag').draggable({ revert: 'invalid', zIndex: 6 });// 각 일정을 draggable로...
/*  drop을 위해서는 아마도 모듈단계에서 extra value update 지원이 필요할듯.. */
}

/******************************************************************************/
/* calendar - 일정폭 조정 */
function doResizeScheduleWidth(schedule_html_json) {
	var $j = jQuery.noConflict();
	var	tr_width = $j("tr:#planner_week0").width();
//    var arrayFromPHP = {$schedule_html_json};  // PHP에서 만든 일정 어레이를 받아서
	var	arrayFromPHP = schedule_html_json;  // PHP에서 만든 일정 어레이를 받아서
	if (arrayFromPHP) {  // 처리할 일정이 있으면 아래내용처리
		$j.each(arrayFromPHP, function (i, elem) {	// 각 일정 마다
			if ($j('#wc-' + i + '-' + elem.pln_srl).length){
				var pln_week = parseInt(elem.week),		// 주 순서
					pln_weekday = parseInt(elem.weekday),	// 요일
					pln_date = parseInt(elem.date),			// 일정기간을 저장
					pln_length = parseInt(elem.length);		// 일정기간을 저장
				var pln_width_new = tr_width * pln_length/7;
				position_week = $j("#week_schedule_" + pln_week).position();	// 주별 장기일정 콘테이너 위치 저장
				position = $j("#day_schedule_container_" + pln_date).position();	// 일별 하루일정 콘테이너 위치 저장
				$j('#wc-' + i + '-' + elem.pln_srl).width(pln_width_new);  // 각장기 일정 폭 조정
				$j('#wc-' + i + '-' + elem.pln_srl).css({left: (position.left - position_week.left) + "px"});  // 각장기 left 위치 조정
				// alert("day:" + elem.date + " tr:" + tr_width + " td:" + tr_width/7 +" width:" + pln_width_new);	// 검사용
			}
		});
	}
}

/******************************************************************************/
/* 카테고리 이동 (simple, standard, list, weekly)*/
function doChgCategory(category_srl) {
	if (!category_srl) {
    location.href = decodeURI(current_url).setQuery('category','');
	} else {
    location.href = decodeURI(current_url).setQuery('category',category_srl);
	}
}

/******************************************************************************/
/* weekly - 주간계획 작성 */
function doUpdateMyplan(module_name, module_srl, document_srl, week_count, weekday) { 
	var $j = jQuery.noConflict();
	//alert(module_name +"-"+ module_srl +"-"+ document_srl +"-"+ week_count +"-"+ weekday);
	var content = $j('#myplan_content').val();
	var title = $j('#myplan_title').val();
	var content_arr = new Array();
	content_arr = explode('|=@=|',$j('#myplan_content').val());
	var content_arr_week = explode('|@|',content_arr[week_count]);
	var sharpen = str_replace("'","`", $j('#sharpen').val());
	var role = str_replace("'","`", $j('#role').val());
	var remark = str_replace("'","`", $j('#remark').val());
	var task = str_replace("'","`", $j('#task').val());
	content_arr_week[7] = sharpen;
	content_arr_week[8] = role;
	content_arr_week[9] = remark;
	content_arr_week[weekday] = task;
	var new_content_week = implode('|@|',content_arr_week);
	content_arr[week_count] = new_content_week;
	var new_content = implode('|=@=|',content_arr);

	var new_doc = new Array();
	new_doc['module_srl'] = module_srl;
	new_doc['document_srl'] = document_srl;
	new_doc['title'] = title;
	new_doc['content'] = new_content;
	new_doc['is_secret'] = "Y";
	new_doc['extra_vars'] = "X";
    switch (module_name){
        case 'board':
			exec_xml('board', 'procBoardInsertDocument', new_doc, completeCallModuleAction);
        break;
        case 'bodex':
			exec_xml('bodex', 'procBoardInsertDocument', new_doc, completeCallModuleAction);
        break;
        default:
			exec_xml('board', 'procBoardInsertDocument', new_doc, completeCallModuleAction);
        break;
    }
}
