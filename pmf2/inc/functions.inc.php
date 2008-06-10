<?php
	//phpmyfamily - opensource genealogy webbuilder
	//Copyright (C) 2002 - 2007  Simon E Booth (simon.booth@giric.com)
	//Contributions (C)2004 Ken Joyce (ken@poweringon.com)

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

	// function: str_rand (aidan@php.net - http://aidan.dotgeek.org/lib/?file=function.str_rand.php)
	// generate a random string
	function str_rand($length = 8, $seeds = 'abcdefghijklmnopqrstuvwxyz0123456789')
	{
		$str = '';
		$seeds_count = strlen($seeds);
 
		// Seed
		list($usec, $sec) = explode(' ', microtime());
		$seed = (float) $sec + ((float) $usec * 100000);
		mt_srand($seed);
 
		// Generate
		for ($i = 0; $length > $i; $i++) {
		    $str .= $seeds{mt_rand(0, $seeds_count - 1)};
		}
 
		return $str;
	}	// end of str_rand()

	// function: liststyles
	// list styles to choose
	// "Ken Joyce"
	function liststyles($form, $style) {

		$config = Config::getInstance();
		
		if ($handle = opendir($config->styledir)) {
			echo "<select name=\"$form\">\n";
			while (false !== ($file = readdir($handle)) ) {
				if ( strrpos($file, "css.php")>1) {
					$filebits = explode(".",$file);
					echo "<option value=\"".$filebits[0]."\"" ;
					if ($style == $file ) echo " selected=\"selected\"";
					echo ">".$filebits[0]."</option>\n";
				}
			}
		echo "</select>\n";
		closedir($handle);
		}
	} // end of liststyles()

	function listlangs($form, $lang) {
		$ret = "";
		if ($handle = opendir('lang')) {
			$ret .= "<select name=\"$form\">\n";
			while (false !== ($file = readdir($handle)) ) {
				if ( strrpos($file, "inc.php")>1) {
					$filebits = explode(".",$file);
					$ret .= "<option value=\"".$filebits[0]."\"" ;
					if ($lang == $filebits[0] ) $ret .= " selected=\"selected\"";
					$ret .= ">".$filebits[0]."</option>\n";
				}
			}
		$ret .= "</select>\n";
		closedir($handle);
		}
		return ($ret);
	} // end of listslangs()
	
	// function: send password
	// sends a new password to a user who has forgotten
	function send_password($email) {
		global $tblprefix;
		global $ePwdSubject, $ePwdBody;
		
		$config = Config::getInstance();

		// check we have a valid email address
		// just drop out if we don't
		$query = "SELECT * FROM ".$tblprefix."users WHERE email = ".quote_smart($email);
		$result = mysql_query($query) or die(mysql_error());
		if (mysql_num_rows($result) != 1)
			return 0;
		while ($qrow = mysql_fetch_array($result)) {	
			$username = $qrow["username"];
		}
		mysql_free_result($result);

		// generate a new password
		$password = str_rand();

		// update the table
		// just drop out if it doesn't work out right
		$uquery = "UPDATE ".$tblprefix."users SET password = '".md5($password)."' WHERE email = '".$email."'";
		$uresult = mysql_query($uquery) or die(mysql_error());
		if (mysql_affected_rows() != 1)
			return 0;

		// email to user
		// Set up the headers to be meaningful
		$headers = "Content-type: text/plain; charset=iso-8859-1\r\n";
		$headers .= "From: <".$config->trackemail.">\r\n";
		$headers .= "X-Mailer: PHP/" . phpversion();
		$subject = $ePwdSubject;
		$body = str_replace("$1", $username."/".$password, $ePwdBody);

		// fire off the email
		mail($email, $subject, $body, $headers);
	}	// end of send_password()


	// function: fmod
	// return the modulus of two numbers
//	function fmod($x, $y) {
//		$d = floor($x / $y);
//		return $x - $d * $y;
//	}	// end of fmod()

	// eof
?>
