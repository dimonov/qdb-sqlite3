<?php
/***************************************************************************
 *   OSQDB, includes/sessions.inc.php
 *   (C) 2005 OSQDB Team, http://www.sourceforge.net/projects/osqdb/
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program; if not, write to the Free Software
 *   Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 ***************************************************************************/
 
global $dbc;
/* Stop include file being called directly */
$tmp=$_SERVER['SCRIPT_FILENAME'];
if (!preg_match("/\bindex.php\b/i", "$tmp") && !preg_match("/\bupgrade.php\b/i", "$tmp")) {
die("Hacking Attempt");
}

session_start();

/* Temporary measure */
if(isset($pwd2)){ $pwd=md5($pwd2); }


if(!isset($uid)) { $loggedin = 'false'; } 
else {
  session_register("uid");
  session_register("pwd");

$uid = escape_str($uid);
$pwd = escape_str($pwd);

  $sql = "SELECT * FROM admins WHERE username = '$uid' AND password = '$pwd'";
  $result = $dbc->query($sql);
  $r_sql = $dbc->querySingle("select count(*) from ($sql)");
  
  if (!$result) { databaseError(); }

  if($uid==""){
    session_unregister("uid");
    session_unregister("pwd");
    $incorrectlogin="yes";
    $loggedin = 'false';
  }

  //if (mysql_num_rows($result) == 0) {
   if ($r_sql == NULL || $r_sql == FALSE){ /* FIXME */
    session_unregister("uid");
    session_unregister("pwd");
    $incorrectlogin="yes";
    $loggedin = 'false';
  }

  if($incorrectlogin!='yes'){ 
    $loggedin = 'true'; 
//    $userid = mysql_result($result,0,"id"); /* FIXME */
    $userid = NULL;
    $loginStatus = mysql_result($result,0,"status");
    if($loginStatus=="ADM"){ $loginStatus="MAN"; }
  }

}
?>
