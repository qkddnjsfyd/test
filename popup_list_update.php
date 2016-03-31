<?
$sub_menu = "100200";
include_once("./_common.php");

check_demo();

auth_check($auth[$sub_menu], "w");

for ($i=0; $i<count($chk); $i++) 
{

    // 실제 번호를 넘김
    $k = $chk[$i];

	$row = sql_fetch(" select * from $g4[popup_table] where po_id = '$po_id[$k]' ");
	if (!$row[po_id]){ 
		$msg ="po_id 값이 제대로 넘어오지 않았습니다.";
    } else {
        $sql = " update $g4[popup_table]
                    set po_openchk = '$po_openchk[$k]',
						 po_popstyle = '$po_popstyle[$k]' 
                  where po_id = '$po_id[$k]' ";
        sql_query($sql);
    }
}

if ($msg)
    echo "<script type='text/JavaScript'> alert('$msg'); </script>";

goto_url("./popup_list.php?$qstr");
?>
