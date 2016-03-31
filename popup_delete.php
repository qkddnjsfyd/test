<?
$sub_menu = "100200";
include_once("./_common.php");

check_demo();

auth_check($auth[$sub_menu], "d");

$row = sql_fetch(" select * from $g4[popup_table] where po_id = '$po_id' ");
if (!$row[po_id]){ 
	$msg ="po_id 값이 제대로 넘어오지 않았습니다.";
} else {
	sql_query(" delete from $g4[popup_table] where po_id = '$po_id' ");
}

if ($url)
    goto_url("{$url}?$qstr&w=u&po_id=$po_id");
else
    goto_url("./popup_list.php?$qstr");
?>
