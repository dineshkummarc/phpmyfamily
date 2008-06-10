<?php
	//phpmyfamily - opensource genealogy webbuilder
	//Copyright (C) 2002 - 2005  Simon E Booth (simon.booth@giric.com)

	//This program is free software; you can redistribute it and/or
	//modify it under the terms of the GNU General Public License
	//as published by the Free Software Foundation; either version 2
	//of the License, or (at your option) any later version.

	//This program is distributed in the hope that it will be useful,
	//but WITHOUT ANY WARRANTY; without even the implied warranty of
	//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	//GNU General Public License for more details.

	//You should have received a copy of the GNU General Public License
	//along with this program; if not, write to the Free Software
	//Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

	// include the configuration parameters and function
	include_once "modules/db/DAOFactory.php";
	include_once "inc/header.inc.php";
	include_once "modules/people/show.php";
	
	// check we have a person
	if(!isset($_REQUEST["person"])) $person = 1;
	@$person = $_REQUEST["person"];

	$peep = new PersonDetail();
	$peep->setFromRequest();
	$peep->queryType = Q_IND;
	$dao = getPeopleDAO();
	$dao->getPersonDetails($peep);
	if ($peep->numResults != 1) {
		die("error");
	}
	$per = $peep->results[0];
	
	$dao->getParents($per);
	$dao->getChildren($per);
	$dao->getSiblings($per);
	
	// if trying to access a restriced person
	if (!$per->isViewable()) {
		die(include "inc/forbidden.inc.php");
	}
		
	$config = Config::getInstance();
	do_headers_dojo($per->getDisplayName());
?>
<body class="tundra">
<script language="JavaScript" type="text/javascript">
 <!--
 function confirm_delete(year, section, url) {
 	input_box = confirm(<?php echo $strConfirmDelete; ?>);
 	if (input_box == true) {
 		window.location = url;
 	}
 }
 if (parent.addPerson) {
	<?php
	if ($per->date_of_birth != "0000-00-00") {
		$dob = "(".$strBorn." ".$per->dob.")";
	} else {
		$dob = '';
	} ?>
	parent.addPerson(<?php echo $per->person_id;?>, '<?php echo $per->getDisplayName();?>', '<?php echo $dob;?>');
 }

 <?php
 $sectionType = "";
 $containerType = "";
	switch ($config->layout) {
case 2:
$containerType = " dojotype=\"dijit.layout.TabContainer\" style=\"height:600px\"";
$sectionType = " dojotype=\"dijit.layout.ContentPane\"";
?>
        dojo.require("dijit.layout.ContentPane");
        dojo.require("dijit.layout.TabContainer");
<?php
break;
case 3:
$containerType = "";
$sectionType = " dojotype=\"dijit.TitlePane\"";
?>

 dojo.require("dijit.TitlePane");

<?php
break;
}
?>
 -->
</script>

<!--titles-->
	<table width="100%" class="header">
		<tr>
			<td width="65%" align="center" valign="top">
				<h2><?php echo $per->getDisplayName(); ?></h2>
				<h3><?php
					echo $per->getDates();
				?></h3>
			</td>
			<td width="35%" valign="top" align="right">
				<form method="get" action="people.php">
				<?php selectPeople("person", 0, "A", $per->person_id); ?>
				</form>
<?php user_opts($per->person_id); ?>
			</td>
		</tr>
	</table>

<hr />

<!--links to relations table-->
	<table width="100%">
		<tr>
			<th width="85%"><h4><?php echo $strDetails; ?></h4></th>
			<td width="15%" class="tbl_odd" align="center">
				<a href="pedigree.php?person=<?php echo $per->person_id; ?>"><?php echo $strPedigree; ?></a>
<?php
				if ($per->isEditable()) {
?>::
<a href="edit.php?func=edit&amp;area=people&amp;person=<?php echo $per->person_id; ?>"><?php echo $strEdit; ?></a>
<?php
				}
				if ($per->isDeletable()) {
?>
:: <a href="JavaScript:confirm_delete('<?php echo $per->getDisplayName(); ?>', '<?php echo strtolower($strPerson); ?>', 'passthru.php?func=delete&amp;area=people&amp;person=<?php echo $per->person_id; ?>')" class="delete"><?php echo $strDelete; ?></a>
<?php
				}
				if ($config->gedcom == true && $per->isExportable()) {
						echo "<br><a href=\"gedcom.php?person=".$_REQUEST["person"]."\">".$strGedCom."</a>\n";
				}
				if ($per->person_id) {
					?>
:: <a href="descendants.php?person=<?php echo $per->person_id;?>" class="hd_link"><?php echo $strDescendants;?></a>
:: <a href="ancestors.php?person=<?php echo $per->person_id;?>" class="hd_link"><?php echo $strAncestors;?></a></td>
			<?php
				}
				?>

		</tr>
	</table>

<!--BDM-->
	<table width="100%">
		<tr>
			<th width="5%" valign="top"><?php echo $strBorn; ?>:</th>
			<td width="38%" class="tbl_odd" valign="top"><?php
			if ($per->date_of_birth != "0000-00-00") {
				echo $per->dob.$per->birth_place->getAtDisplayPlace();
			} else {
				echo $per->birth_place->getDisplayPlace();
			}
			?></td>
			<td class="tbl_odd" valign="top"><?php echo $strCertified; ?><input type="checkbox" name="birthcert" disabled="disabled"<?php if ($per->birth_cert == "Y") echo " checked=\"checked\"" ?> /></td>
			<th width="5%" valign="top"><?php echo $strFather; ?>:</th>
			<td width="40%" class="tbl_odd" valign="top"><?php
		// the query for father
		if ($per->father->hasRecord()) {
			echo $per->father->getFullLink();
		} else {
			if ($per->isCreatable()) {?>
<a href="edit.php?func=add&area=people&gender=M&cid=<?php echo $per->person_id;?>"><?php echo $strInsert." ".$strFather;?></a><?php } 
		}
?></td>
		</tr>
		<tr>
			<th width="5%" valign="top"><?php echo $strDied; ?>:</th>
			<td width="20%" class="tbl_odd" valign="top"><?php
				if ($per->date_of_death != "0000-00-00" && $per->death_reason != "")
					echo $per->dod." ".$strOf." ".$per->death_reason;
				elseif ($per->date_of_death != "0000-00-00")
					echo $per->dod;
				else
					echo $per->death_reason;
			?></td>
			<td class="tbl_odd" valign="top"><?php echo $strCertified; ?><input type="checkbox" name="deathcert" disabled="disabled"<?php if ($per->death_cert == "Y") echo " checked=\"checked\""; ?> /></td>
			<th valign="top"><?php echo $strMother; ?>:</th>
			<td class="tbl_odd" valign="top"><?php
		// the query for mother
		if ($per->mother->hasRecord()) {
			echo $per->mother->getFullLink();
		} else {
			if ($per->isCreatable()) {?>
<a href="edit.php?func=add&area=people&gender=F&cid=<?php echo $per->person_id;?>"><?php echo $strInsert." ".$strMother;?></a><?php } 
		}
?></td>
		</tr>
		<tr>
			<!--Children-->
			<th valign="top"><?php echo $strChildren; ?>:</th>
			<td valign="top" class="tbl_even" colspan="2">
<?php
foreach ($per->children AS $child) {
	echo $child->getFullLink();
	echo "<br>\n";
}
?>
			</td>
			<!--Siblings-->
			<th valign="top"><?php echo $strSiblings; ?>:</th>
			<td valign="top" class="tbl_even">
<?php
foreach ($per->siblings AS $sibling) {
	echo $sibling->getFullLink();
	echo "<br>\n";
}
?>
			</td>
		</tr>
	</table>

	<div <?php echo $containerType;?>>
<?php 
 $config = Config::getInstance();
 $modules = $config->getActiveModules();
 foreach ($modules as $mod) {
	 include_once "modules/".$mod."/show.php";
	 
	 ob_start();
	 if ($config->layout < 2) {
		 echo "<hr />";
		 call_user_func("show_".$mod."_title", $per);
	 } else {
		 if ($per->isEditable()) {
			 echo "<div class=\"insert\">".call_user_func("get_".$mod."_create_string", $per)."</div>\n";
		 }
	 }
	 $numrecs = call_user_func("show_".$mod, $per);
	 
	 $contents = ob_get_contents();
	 ob_end_clean();
	 if ($numrecs > 0 || $per->isEditable()) {
		 echo "<div id=\"".$mod."\" ".$sectionType." title=\"".call_user_func("get_".$mod."_title").'"';
		 if ($numrecs == 0) { echo ' open="false" ';}
		 echo ">\n";
		 echo $contents;
		 echo "</div>\n";
	 }
 }

?>
</div>
<br /><br />
<?php
	include "inc/footer.inc.php";

	// eof
?>

