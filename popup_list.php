<?
$sub_menu = "100200";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$sql_common = " from $g4[popup_table] ";

$sql_search = " where 1=1";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        default :
            $sql_search .= " ($sfl like '$stx%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst = "po_datetime";
    $sod = "desc";
}

$sql_order = " order by $sst $sod ";

$sql = " select count(*) as cnt
         $sql_common
         $sql_search
         $sql_order ";
$row = sql_fetch($sql);
$total_count = $row[cnt];

$rows = $config[cf_page_rows];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if (!$page) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

// 사용중
$sql = " select count(*) as cnt
         $sql_common
         $sql_search
            and po_openchk = '1'
         $sql_order ";
$row = sql_fetch($sql);
$start_count = $row[cnt];

// 미사용
$sql = " select count(*) as cnt
         $sql_common
         $sql_search
            and po_openchk <> '1'
         $sql_order ";
$row = sql_fetch($sql);
$end_count = $row[cnt];

$listall = "<a href='$_SERVER[PHP_SELF]' class=tt>처음</a>";

$g4[title] = "팝업관리";
include_once("./admin.head.php");

$sql = " select *
          $sql_common
          $sql_search
          $sql_order
          limit $from_record, $rows ";
$result = sql_query($sql);

$colspan = 15;
?>

<script language="javascript" src="<?=$g4[path]?>/js/sideview.js"></script>
<script language="JavaScript">
var list_update_php = "popup_list_update.php";
var list_delete_php = "popup_list_delete.php";
</script>

<table width=100%>
<form name=fsearch method=get>
<tr>
    <td width=50% align=left><?=$listall?> 
        (총문의수 : <?=number_format($total_count)?>, 
        <font color=orange> 미사용 : <?=number_format($end_count)?></font></a>, 
        <font color=crimson>사용중 : <?=number_format($start_count)?></font></a>)
    </td>
    <td width=50% align=right>
        <select name=sfl class=cssfl>
            <option value='po_subject'>제목</option>
            <option value='po_content'>내용</option>
            <option value='po_email'>시작일시</option>
            <option value='po_tel'>종료일시</option>
            <option value='po_datetime'>입력일시</option>
        </select>
        <input type=text name=stx required itemname='검색어' value='<? echo $stx ?>'>
        <input type=image src='<?=$g4[admin_path]?>/img/btn_search.gif' align=absmiddle></td>
</tr>
</form>
</table>

<table width=100% cellpadding=0 cellspacing=0>
<form name=onlinelist method=post>
<input type=hidden name=sst  value='<?=$sst?>'>
<input type=hidden name=sod  value='<?=$sod?>'>
<input type=hidden name=sfl  value='<?=$sfl?>'>
<input type=hidden name=stx  value='<?=$stx?>'>
<input type=hidden name=page value='<?=$page?>'>
<input type=hidden name=kind value='<?=$kind?>'>
<colgroup width=30>
<colgroup width=60>
<colgroup width=70>
<colgroup width=160>
<colgroup width=100>
<colgroup width=100>
<colgroup width=50>
<colgroup width=70>
<tr><td colspan='<?=$colspan?>' class='line1'></td></tr>
<tr class='bgcol1 bold col1 ht center'>
    <td><input type=checkbox name=chkall value='1' onclick='check_all(this.form)'></td>
    <td><?=subject_sort_link('po_openchk')?>사용여부</a></td>
    <td><?=subject_sort_link('po_popstyle')?>레이어체크</a></td>
    <td><?=subject_sort_link('po_subject')?>제목</a></td>
    <td><?=subject_sort_link('po_start_date')?>시작일시</a></td>
    <td><?=subject_sort_link('po_end_date')?>종료일시</a></td>
    <td><?=subject_sort_link('po_expirehours')?>시간</a></td>
    <td><?=subject_sort_link('po_datetime')?>입력일시</a></td>
	<td><a href="./popup_form.php"><img src='<?=$g4[admin_path]?>/img/icon_insert.gif' border=0 title='추가'></a></td>
</tr>
<tr><td colspan='<?=$colspan?>' class='line2'></td></tr>
<?
for ($i=0; $row=sql_fetch_array($result); $i++) {

	$s_mod = "<a href=\"./popup_form.php?$qstr&w=u&po_id=$row[po_id]\"><img src='img/icon_modify.gif' border=0 title='수정'></a>";
	$s_del = "<a href=\"javascript:del('./popup_delete.php?$qstr&w=d&po_id=$row[po_id]');\"><img src='img/icon_delete.gif' border=0 title='삭제'></a>";
	$s_view = "<a href=\"javascript:win_open('../skin/popup/$row[po_skin]/viewpop.skin.php?po_id=$row[po_id]','popuptext','width=$row[po_width] ,height=$row[po_height],scrollbars=$row[po_scrollbar]');\"><img src='img/icon_view.gif' border=0 title='보기'></a>";

    $list = $i%2;

	$openchk = "";
	if($row[po_openchk] == "1")
		$openchk = "checked";

	$popchk = "";
	if($row[po_popstyle] == "1")
		$popchk = "checked";

    echo "
    <input type=hidden name=po_id[$i] value='$row[po_id]'>
    <tr class='list$list col1 ht center'>
        <td><input type=checkbox name=chk[] value='$i'></td>
        <td><nobr style='display:block; overflow:hidden; width:60px;'><input type=checkbox name=po_openchk[$i] value='1' $openchk></nobr></td>
        <td><nobr style='display:block; overflow:hidden; width:70px;'><input type=checkbox name=po_popstyle[$i] value='1' $popchk></nobr></td>
        <td><nobr style='display:block; overflow:hidden; width:160px;'>$row[po_subject]</nobr></td>
        <td><nobr style='display:block; overflow:hidden; width:100px;'>$row[po_start_date]</nobr></td>
        <td><nobr style='display:block; overflow:hidden; width:100px;'>$row[po_end_date]</nobr></td>
        <td><nobr style='display:block; overflow:hidden; width:50px;'>$row[po_expirehours]</nobr></td>
        <td><nobr style='display:block; overflow:hidden; width:70px;'>".substr($row[po_datetime],0,11)."</nobr></td>
        <td>$s_mod $s_del $s_view</td>
    </tr>";
}

if ($i == 0)
    echo "<tr><td colspan='$colspan' align=center height=100 class=contentbg>자료가 없습니다.</td></tr>";

echo "<tr><td colspan='$colspan' class='line2'></td></tr>";
echo "</table>";

$pagelist = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page=");
echo "<table width=100% cellpadding=3 cellspacing=1>";
echo "<tr><td width=50%>";
echo "<input type=button class='btn1' value='선택수정' onclick=\"btn_check(this.form, 'update')\">&nbsp;";
echo "<input type=button class='btn1' value='선택삭제' onclick=\"btn_check(this.form, 'delete')\">";
echo "</td>";
echo "<td width=50% align=right>$pagelist</td></tr></table>\n";

if ($stx)
    echo "<script language='javascript'>document.fsearch.sfl.value = '$sfl';</script>\n";
?>
</form>


* 사용여부 ' 미체크 ' 시 팝업은 동작 하지 않습니다.

<?
include_once ("./admin.tail.php");
?>
