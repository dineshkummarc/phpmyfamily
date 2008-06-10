<?php
include_once "modules/db/DAOFactory.php";

$func = "edit";
if (isset($_REQUEST["func"])) { $func = $_REQUEST["func"]; }
if (isset($_REQUEST["area"])) { 
	$area = $_REQUEST["area"]; 
	if ($area == "marriage") { $area = "relations"; }
}

// if you are not logged in then you shouldn't be here!
if ($_SESSION["editable"] == "N" && ($func != "login" && $func != "lang" && $func != "logout" && $func != "change")) {
	die(include "inc/forbidden.inc.php");
}

	switch ($func) {
		case "track":
			switch ($_REQUEST["action"]) {
				case "do":
					$query = "INSERT INTO ".$tblprefix."tracking (person_id, email) VALUES ('".$_REQUEST["person"]."', '".$_SESSION["email"]."')";
					break;
				case "dont":
					$query = "DELETE FROM ".$tblprefix."tracking WHERE person_id = '".$_REQUEST["person"]."' AND email = '".$_SESSION["email"]."'";
					break;
			}
			$result = mysql_query($query) or die(mysql_error());
			echo "<meta http-equiv=refresh content='0; url=people.php?person=".$_REQUEST["person"]."' />\n";
			break;
		case "login":
			$loggedIn = $currentRequest->login($_POST["pwdUser"], md5($_POST["pwdPassword"]));
			if ($loggedIn) {
				@$remember = $_POST["pwdRemember"];
				if ($remember != "") {
					setcookie("fam_name", $_POST["pwdUser"], time() + 2592000);
					setcookie("fam_passwd", md5($_POST["pwdPassword"]), time() + 2592000);
				}
			}
			echo "<meta http-equiv=refresh content='0; url=index.php' />\n";
			break;
		case "logout":
			$currentRequest->logout();
			echo "<meta http-equiv=refresh content='0; url=index.php' />\n";
			break;
		case "change":
			$fcheck1 = "SELECT * FROM ".$tblprefix."users WHERE id = '".$_SESSION["id"]."' AND password = '".md5($_POST["pwdOld"])."'";
			$rcheck1 = mysql_query($fcheck1) or die($err_change);
			if (mysql_num_rows($rcheck1) == 0)
				echo "<meta http-equiv=refresh content='0; url=my.php?reason=".$err_pwd_incorrect."' />\n";
			elseif ($_POST["pwdPwd1"] <> $_POST["pwdPwd2"])
				echo "<meta http-equiv=refresh content='0; url=my.php?reason=".$err_pwd_match."' />\n";
			else {
				$fchange = "UPDATE ".$tblprefix."users SET password = '".md5($_POST["pwdPwd1"])."' WHERE id = '".$_SESSION["id"]."'";
				$rchange = mysql_query($fchange) or die($err_update);
				echo "<meta http-equiv=refresh content='0; url=my.php?reason=".$err_pwd_success."' />\n";
			}
			break;
			case "lang":
			$_SESSION["lang"] = $_REQUEST["trans"];
			@$page = $_SERVER["HTTP_REFERER"];
			if ($page == "")
				echo "<meta http-equiv=refresh content='0; url=index.php' />\n";
			else
				echo "<meta http-equiv=refresh content='0; url=".$page."' />\n";
			break;
		default:
			include_once "modules/".$area."/process.php";
			break;
	}
?>
