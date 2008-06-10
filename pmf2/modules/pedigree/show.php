<?php

function show_pedigree($per) {	
	// if trying to access a restriced person
	if (!$per->isViewable())
		die(include "inc/forbidden.inc.php");

	$dao = getPeopleDAO();
	$dao->getParents($per);
	// Use an array to get people references
	$ids = array_fill(1, 15, 0);
	$ids[1] = $per;
	$ids[2] = $per->father;
	$ids[3] = $per->mother;

	for ($i = 2; $i < 8; $i++) {
		$p = $ids[$i];
		$dao->getParents($p);
		$ids[($i * 2)] 	   = $p->father;
		$ids[($i * 2 + 1)] = $p->mother;
	}

	

?>

<!--Main body-->

<table width="100%" cellspacing="0">
  <tbody>
    <tr> <!--row 1-->
      <td width="22%">  </td>
      <td width="4%">  </td>
      <td width="22%">  </td>
      <td width="4%">  </td>
      <td width="22%">  </td>
      <td width="4%"<?php if($ids[8] != 0) echo " class=\"tr\""; ?>> </td>
<?php
	if ($ids[8] == 0)
		echo "\t<td></td>\n";
	else
		person_disp($ids[8]);
?>
    </tr>
    <tr> <!--row 2-->
      <td>  </td>
      <td>  </td>
      <td>  </td>
      <td<?php if($ids[4] != 0) echo " class=\"tr\""; ?>></td>
<?php
	if ($ids[4] == 0)
		echo "\t<td></td>\n";
	else
		person_disp($ids[4]);
?>
      <td<?php
		if ($ids[8] != 0 && $ids[9] != 0)
			echo " class=\"outer\"";
		elseif ($ids[8] != 0)
			echo " class=\"rt\"";
		elseif ($ids[9] != 0)
			echo " class=\"rb\"";
?>>  </td>
      <td>  </td>
    </tr>
    <tr> <!--row 3-->
      <td>  </td>
      <td>  </td>
      <td>  </td>
      <td<?php if($ids[4] != 0) echo " class=\"vert\""; ?>>  </td>
      <td>  </td>
      <td<?php if($ids[9] != 0) echo " class=\"br\""; ?>></td>
<?php
	if ($ids[9] == 0)
		echo "\t<td></td>\n";
	else
		person_disp($ids[9]);
?>
    </tr>
    <tr> <!--row 4-->
      <td>  </td>
      <td<?php if($ids[2] != 0) echo " class=\"tr\""; ?>></td>
<?php
	if ($ids[2] == 0)
		echo "\t<td></td>\n";
	else
		person_disp($ids[2]);
?>
      <td<?php
		if ($ids[4] != 0 && $ids[5] != 0)
			echo " class=\"outer\"";
		elseif ($ids[4] != 0)
			echo " class=\"rt\"";
		elseif ($ids[5] != 0)
			echo " class=\"rb\"";
?>> </td>
      <td>  </td>
      <td>  </td>
      <td>  </td>
    </tr>
    <tr> <!--row 5-->
      <td>  </td>
      <td<?php if($ids[2] != 0) echo " class=\"vert\""; ?>></td>
      <td>  </td>
      <td<?php if($ids[5] != 0) echo " class=\"vert\""; ?>>  </td>
      <td>  </td>
      <td<?php if($ids[10] != 0) echo " class=\"tr\""; ?>></td>
<?php
	if ($ids[10] == 0)
		echo "\t<td></td>\n";
	else
		person_disp($ids[10]);
?>
    </tr>
    <tr> <!--row 6-->
      <td>  </td>
      <td<?php if($ids[2] != 0) echo " class=\"vert\""; ?>></td>
      <td>  </td>
      <td<?php if($ids[5] != 0) echo " class=\"br\""; ?>></td>
<?php
	if ($ids[5] == 0)
		echo "\t<td></td>\n";
	else
		person_disp($ids[5]);
?>
      <td<?php
		if ($ids[10] != 0 && $ids[11] != 0)
			echo " class=\"outer\"";
		elseif ($ids[10] != 0)
			echo " class=\"rt\"";
		elseif ($ids[11] != 0)
			echo " class=\"rb\"";
?>>  </td>
      <td>  </td>
    </tr>
    <tr> <!--row 7-->
      <td>  </td>
      <td<?php if($ids[2] != 0) echo " class=\"vert\""; ?>></td>
      <td>  </td>
      <td>  </td>
      <td>  </td>
      <td<?php if($ids[11] != 0) echo " class=\"br\""; ?>> </td>
<?php
	if ($ids[11] == 0)
		echo "\t<td></td>\n";
	else
		person_disp($ids[11]);
?>
    </tr>
    <tr> <!--row 8-->
      <?php person_disp($ids[1]); ?>
      <td<?php
		if ($ids[2] != 0 && $ids[3] != 0)
			echo " class=\"outer\"";
		elseif ($ids[2] != 0)
			echo " class=\"rt\"";
		elseif ($ids[3] != 0)
			echo " class=\"rb\"";
?>> </td>
      <td>  </td>
      <td>  </td>
      <td>  </td>
      <td>  </td>
      <td>  </td>
    </tr>
    <tr> <!--row 9-->
      <td>  </td>
      <td<?php if($ids[3] != 0) echo " class=\"vert\""; ?>> </td>
      <td>  </td>
      <td>  </td>
      <td>  </td>
      <td<?php if($ids[12] != 0) echo " class=\"tr\""; ?>></td>
<?php
	if ($ids[12] == 0)
		echo "\t<td></td>\n";
	else
		person_disp($ids[12]);
?>
    </tr>
    <tr> <!--row 10-->
      <td>  </td>
      <td<?php if($ids[3] != 0) echo " class=\"vert\""; ?>></td>
      <td>  </td>
      <td<?php if($ids[6] != 0) echo " class=\"tr\""; ?>></td>
<?php
	if ($ids[6] == 0)
		echo "\t<td></td>\n";
	else
		person_disp($ids[6]);
?>
      <td<?php
		if ($ids[12] != 0 && $ids[13] != 0)
			echo " class=\"outer\"";
		elseif ($ids[12] != 0)
			echo " class=\"rt\"";
		elseif ($ids[13] != 0)
			echo " class=\"rb\"";
?>></td>
      <td>  </td>
    </tr>
    <tr> <!--row 11-->
      <td>  </td>
      <td<?php if($ids[3] != 0) echo " class=\"vert\""; ?>></td>
      <td>  </td>
      <td<?php if($ids[6] != 0) echo " class=\"vert\""; ?>>  </td>
      <td>  </td>
      <td<?php if($ids[13] != 0) echo " class=\"br\""; ?>></td>
<?php
	if ($ids[13] == 0)
		echo "\t<td></td>\n";
	else
		person_disp($ids[13]);
?>
    </tr>
    <tr> <!--row 12-->
      <td>  </td>
      <td<?php if($ids[3] != 0) echo " class=\"br\""; ?>> </td>
<?php
	if ($ids[3] == 0)
		echo "\t<td></td>\n";
	else
		person_disp($ids[3]);
?>
      <td<?php
		if ($ids[6] != 0 && $ids[7] != 0)
			echo " class=\"outer\"";
		elseif ($ids[6] != 0)
			echo " class=\"rt\"";
		elseif ($ids[7] != 0)
			echo " class=\"rb\"";
?>></td>
      <td>  </td>
      <td>  </td>
      <td>  </td>
    </tr>
    <tr> <!--row 13-->
      <td>  </td>
      <td>  </td>
      <td>  </td>
      <td<?php if($ids[7] != 0) echo " class=\"vert\""; ?>>  </td>
      <td>  </td>
      <td<?php if($ids[14] != 0) echo " class=\"tr\""; ?>></td>
<?php
	if ($ids[14] == 0)
		echo "\t<td></td>\n";
	else
		person_disp($ids[14]);
?>
    </tr>
    <tr> <!--row 14-->
      <td>  </td>
      <td>  </td>
      <td>  </td>
      <td<?php if($ids[7] != 0) echo " class=\"br\""; ?>></td>
<?php
	if ($ids[7] == 0)
		echo "\t<td></td>\n";
	else
		person_disp($ids[7]);
?>
      <td<?php
		if ($ids[14] != 0 && $ids[15] != 0)
			echo " class=\"outer\"";
		elseif ($ids[14] != 0)
			echo " class=\"rt\"";
		elseif ($ids[15] != 0)
			echo " class=\"rb\"";
?>> </td>
      <td>  </td>
    </tr>
    <tr> <!--row 15-->
      <td>  </td>
      <td>  </td>
      <td>  </td>
      <td>  </td>
      <td>  </td>
      <td<?php if($ids[15] != 0) echo " class=\"br\""; ?>></td>
<?php
	if ($ids[15] == 0)
		echo "\t<td></td>\n";
	else
		person_disp($ids[15]);
?>
    </tr>
  </tbody>
</table>

<!--End of main body-->
<?php
	} //End of show pedigree
	
	function person_disp($per) {
		global $datefmt;
		global $tblprefix;
		global $err_person;
		global $strBorn, $strOf;
		global $strDied, $strAt;
		
		if ($per->gender == "M") {
			$class = "tbl_odd";
		} else {
			$class = "tbl_even";
		}
		echo "<td bgcolor=\"#FFFFFF\" width=\"22%\" class=\"".$class."\">";
		echo $per->getLink();
		echo "<br />";
		echo $per->getBirthDetails();
		echo "<br/>\n";
		echo $per->getDeathDetails();
		echo "<br/>\n";
		echo "</td>\n";
		
	}
?>
