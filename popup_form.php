<?
$sub_menu = "100200";
include_once("./_common.php");
include_once("$g4[path]/lib/cheditor4.lib.php");

auth_check($auth[$sub_menu], "w");

if ($w == "") 
{
    $html_title = "등록";
	$row[po_skin] = "basic";
}
else if ($w == "u") 
{
	$row = sql_fetch(" select * from $g4[popup_table] where po_id = '$po_id' ");
    if (!$row[po_id])
        alert("자료가 존재 하지 않습니다..");
	sql_query("update $g4[popup_table] set po_datetime = '$g4[time_ymdhis]' where po_id = '$po_id'");

    $html_title = "수정";
} 
else 
    alert("제대로 된 값이 넘어오지 않았습니다.");

$g4[title] = "팝업관리 " . $html_title;
include_once("./admin.head.php");


echo "<script src='$g4[cheditor4_path]/cheditor.js'></script>";
echo cheditor1('po_content', '100%', '250');

?>

<form name=popup method=post onsubmit="return popup_submit(this);" enctype="multipart/form-data" autocomplete="off">
<input type=hidden name=w    value='<?=$w?>'>
<input type=hidden name=sfl  value='<?=$sfl?>'>
<input type=hidden name=stx  value='<?=$stx?>'>
<input type=hidden name=sst  value='<?=$sst?>'>
<input type=hidden name=sod  value='<?=$sod?>'>
<input type=hidden name=page value='<?=$page?>'>
<input type=hidden name=po_id value='<?=$row[po_id]?>'>
<input type=hidden name=kind value='<?=$kind?>'>
<table width=100% align=center cellpadding=0 cellspacing=0>
<colgroup width=20% class='col1 pad1 bold right'>
<colgroup width=30% class='col2 pad2'>
<colgroup width=20% class='col1 pad1 bold right'>
<colgroup width=30% class='col2 pad2'>
<tr>
    <td colspan=4 class=title align=left><img src='<?=$g4[admin_path]?>/img/icon_title.gif'> <?=$g4[title]?></td>
</tr>
<tr><td colspan=4 class=line1></td></tr>
<tr class='ht'>
    <td>스킨</td>
    <td colspan="3">
		<select name=po_skin required itemname="스킨 디렉토리" onChange="skin_change(this.value);">
        <?
        $arr = get_skin_dir("popup");
        for ($i=0; $i<count($arr); $i++) {
			$selected = "";
			if($row[po_skin] == "$arr[$i]")
				$selected = "selected";
            echo "<option value='$arr[$i]' $selected>$arr[$i]</option>\n";
        }
        ?>
		</select>
		<br>
		<br>
		<!-- 스킨 이미지 불러오기 이미지 원본 사이즈는 499로 하는것이 좋습니다. -->
		<img src="../skin/popup/<?=$row[po_skin];?>/img/default_img.gif" id="skin_img" width="150" height="150" style="cursor:hand;" onClick="image_window(this);" tmp_width="499" tmp_height="499">
	</td>
</tr>
<tr class='ht'>
    <td>팝업표현경로</td>
    <td colspan="3">
		<select name="table_name" onchange="document.popup.po_dir.value = this.value">
			<option value="">게시판선택</option>
			<? 
				$result = sql_query("select  bo_subject, bo_table from $g4[board_table] where 1=1");
				while($bo_row = sql_fetch_array($result)){
					echo "<option value='{$bo_row[bo_table]}'>{$bo_row[bo_subject]}</option>";
				}
			?>
		</select>
		<input type=text class=ed name=po_dir size=50 value='<? echo $row[po_dir] ?>' itemname="팝업표현경로"> ex) <?=$_SERVER['PHP_SELF'];?>
		<br><font color=red>입력이 없을시에는 자동적으로 모든 페이지 적용합니다.</font>
	</td>
</tr>
<tr class='ht'>
    <td>사용여부</td>
    <td><input type=checkbox name=po_openchk value="1" <? echo ($row[po_openchk] == "1") ? "checked" : ""; ?>> * 체크시 팝업 사용
	</td>
    <td>팝업형식</td>
    <td>
		<input type=checkbox name=po_popstyle value="1" <? echo ($row[po_popstyle] == "1") ? "checked" : ""; ?> onclick="if (this.checked == true) document.getElementById('act_layer').style.display = 'block'; else document.getElementById('act_layer').style.display = 'none';"> * 체크시 레이어 출력
		<div style="display:<? echo ($row[po_popstyle] == "1") ? "" : "none"; ?>" id="act_layer">
		<b>레이어열기효과</b><br>
		<input type=radio name=po_act value="" checked> 효과없음
		<input type=radio name=po_act value="ts_flickeringly" <? echo ($row[po_act] == "ts_flickeringly") ? "checked" : ""; ?>> 깜빡깜빡<br>
		<input type=radio name=po_act value="ts_fadeIn" <? echo ($row[po_act] == "ts_fadeIn") ? "checked" : ""; ?>> 페이드인
		<input type=radio name=po_act value="ts_slideLeft" <? echo ($row[po_act] == "ts_slideLeft") ? "checked" : ""; ?>> 슬라이드가로<br>
		<input type=radio name=po_act value="ts_slideDown" <? echo ($row[po_act] == "ts_slideDown") ? "checked" : ""; ?>> 슬라이드세로 
		<input type=radio name=po_act value="ts_slideLeftDown" <? echo ($row[po_act] == "ts_slideLeftDown") ? "checked" : ""; ?>> 슬라이드전체<br>
		<input type=text class=ed name='po_delay' itemname='딜레이시간' numeric value='<? echo $row[po_delay] ?>' size="4"> 딜레이시간(1000 = 1초)
		<b>레이어열기닫기</b><br>
		<input type=radio name=po_actc value="" checked> 효과없음
		<input type=radio name=po_actc value="ts_fadeInBack" <? echo ($row[po_actc] == "ts_fadeInBack") ? "checked" : ""; ?>> 페이드인
		<input type=radio name=po_actc value="ts_slideLeftBack" <? echo ($row[po_actc] == "ts_slideLeftBack") ? "checked" : ""; ?>> 슬라이드가로<br>
		<input type=radio name=po_actc value="ts_slideDownBack" <? echo ($row[po_actc] == "ts_slideDownBack") ? "checked" : ""; ?>> 슬라이드세로 
		<input type=radio name=po_actc value="ts_slideLeftDownBack" <? echo ($row[po_actc] == "ts_slideLeftDownBack") ? "checked" : ""; ?>> 슬라이드전체<br>
		</div>
	</td>
</tr>
<tr class='ht'>
    <td>시간</td>
    <td><input type=text class=ed name='po_expirehours' maxlength=4 minlength=2 required itemname='시간' value='<? echo $row[po_expirehours] ?>' size="4">
        <input type=radio name=po_date_chk value="24" onclick="if (this.checked == true) this.form.po_expirehours.value=this.form.po_date_chk[0].value; else this.form.po_expirehours.value = this.form.po_date_chk[0].value;">하루 
        <input type=radio name=po_date_chk value="168" onclick="if (this.checked == true) this.form.po_expirehours.value=this.form.po_date_chk[1].value; else this.form.po_expirehours.value = this.form.po_date_chk[1].value;">일주일 
        <input type=radio name=po_date_chk value="1176" onclick="if (this.checked == true) this.form.po_expirehours.value=this.form.po_date_chk[2].value; else this.form.po_expirehours.value = this.form.po_date_chk[2].value;">한달
	</td>
    <td>스크롤바</td>
    <td><input type=checkbox name='po_scrollbar' value='1' <? echo ($row[po_scrollbar] == "1") ? "checked" : ""; ?>> * 스크롤바 사용시 체크</td>
</tr>
<tr class='ht'>
    <td>시작일시</td>
    <td>
        <input type=text class=ed name=po_start_date size=21 maxlength=19 value='<? echo $row[po_start_date] ?>' required itemname="시작일시">
        <input type=checkbox name=po_start_chk value="<? echo date("Y-m-d 00:00:00", $g4[server_time]); ?>" onclick="if (this.checked == true) this.form.po_start_date.value=this.form.po_start_chk.value; else this.form.po_start_date.value = this.form.po_start_date.defaultValue;">오늘
	</td>
    <td>종료일시</td>
    <td>
        <input type=text class=ed name=po_end_date size=21 maxlength=19 value='<? echo $row[po_end_date] ?>' required itemname="종료일시">
        <input type=checkbox name=po_end_chk value="<? echo date("Y-m-d 23:59:59", $g4[server_time]+(60*60*24*7)); ?>" onclick="if (this.checked == true) this.form.po_end_date.value=this.form.po_end_chk.value; else this.form.po_end_date.value = this.form.po_end_date.defaultValue;">오늘+7일
	</td>
</tr>
<tr class='ht'>
    <td>창위치 가로 가운데정렬</td>
    <td>
        <input type=checkbox name=po_leftcenter value="1" <? echo ($row[po_leftcenter] == "1") ? "checked" : ""; ?>> * 체크시 화면 가로 가운데 부터 px 단위
	</td>
    <td>창위치 세로 가운데정렬</td>
    <td>
        <input type=checkbox name=po_topcenter value="1" <? echo ($row[po_topcenter] == "1") ? "checked" : ""; ?>>  * 체크시 화면 세로 가운데 부터 px 단위
	</td>
</tr>
<tr class='ht'>
    <td>PC 창위치 왼쪽</td>
    <td>
        <input type=text class=ed name=po_left size=4 maxlength=4 value='<? echo $row[po_left] ?>' required itemname="창위치 왼쪽"> * 화면 왼쪽으로 부터 px 단위
	</td>
    <td>PC 창위치 위</td>
    <td>
        <input type=text class=ed name=po_top size=4 maxlength=4 value='<? echo $row[po_top] ?>' required itemname="창위치 위">  * 화면 위으로 부터 px 단위
	</td>
</tr>
<tr class='ht'>
    <td>모바일 창위치 왼쪽</td>
    <td>
        <input type=text class=ed name=m_po_left size=4 maxlength=4 value='<? echo $row[m_po_left] ?>' required itemname="창위치 왼쪽"> * 화면 왼쪽으로 부터 px 단위
	</td>
    <td>모바일 창위치 위</td>
    <td>
        <input type=text class=ed name=m_po_top size=4 maxlength=4 value='<? echo $row[m_po_top] ?>' required itemname="창위치 위">  * 화면 위으로 부터 px 단위
	</td>
</tr>
<tr class='ht'>
    <td>창크기 가로</td>
    <td>
        <input type=text class=ed name=po_width size=4 maxlength=4 value='<? echo $row[po_width] ?>' required itemname="창위치 왼쪽"> * 이미지사용시 이미지 가로 입력
	</td>
    <td>창크기 세로</td>
    <td>
        <input type=text class=ed name=po_height size=4 maxlength=4 value='<? echo $row[po_height] ?>' required itemname="창위치 위"> * 이미지사용시 이미지 세로 입력<br><font color=red>다시 열지 않음 버튼이 안보일시 px를 더 추가해주세요.</font>
	</td>
</tr>
<tr class='ht'>
    <td>제목</td>
    <td colspan="3"><input type=text class=ed name='po_subject' required itemname='제목' value='<? echo $row[po_subject] ?>' style="width:100%;"></td>
</tr>
<tr class='ht'>
    <td>내용</td>
    <td colspan=3>
	<?=cheditor2('po_content', $row[po_content], '100%', '350');?></td>
</tr>
<? if ($w == "u") {?>
<tr class='ht'>
    <td>등록일</td>
    <td colspan="3"><?=$row[po_datetime]?></td>
</tr>
<?}?>
<tr><td colspan=4 class=line1></td></tr>
</table>

<p align=center>
    <input type=submit class=btn1 accesskey='s' value='  확    인  '>&nbsp;
    <input type=button class=btn1 value='  목  록  ' onclick="document.location.href='./popup_list.php?<?=$qstr?>';">&nbsp;
    
    <? if ($w != '') { ?>
    <input type=button class=btn1 value='  삭  제  ' onclick="del('./popup_delete.php?<?=$qstr?>&w=d&po_id=<?=$row[po_id]?>&url=<?=$_SERVER[PHP_SELF]?>');">&nbsp;
    <? } ?>
</form>

<script language='Javascript'>
function popup_submit(f)
{
    <?=cheditor3('po_content');?>
    f.action = './popup_form_update.php';
    return true;
}
function skin_change(img_name){
	document.getElementById("skin_img").src = "../skin/popup/" + img_name + "/img/default_img.gif";
}
</script>

<?
include_once("./admin.tail.php");
?>
