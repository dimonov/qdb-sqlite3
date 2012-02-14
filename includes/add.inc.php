<?php
/***************************************************************************
 *   OSQDB, includes/add.inc.php
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

/* Stop include file being called directly */
$tmp=$_SERVER['SCRIPT_FILENAME'];
if (!preg_match("/\bindex.php\b/i", "$tmp") && !preg_match("/\bupgrade.php\b/i", "$tmp")) {
die("Hacking Attempt");
}


/**
 * Adds a quote to the database, with optional comment.
 *
 * Parameters: $newquote - The quote.
 *             $comment  - Optional comment.
 */
function add($newquote, $comment){
global $lang;
global $dbc;
$error = "";
$tempid = 0;
  /* Grab the ip for future reference */
  $ip = do_hash($_SERVER['REMOTE_ADDR']);

  /* Filter 'bad' characters from quote */
  $newquote = escape_str("$newquote");
  $newquote = nl2br($newquote);
  $comment = escape_str("$comment");

  $sql = "SELECT COUNT(*) FROM quotes WHERE quote = '$newquote'";
  $result = $dbc->query($sql);
  
  /* Make sure we have found the fields and database is connected */
  if (!$result) {	
    databaseError();
    $error="yes";
  }

  /* Ensure quote does not already exist */
  if ($dbc->querySingle($sql)>0) {
    echo("<center><font size='-1'>$lang[error_comment_5]<br /><br />$lang[back_link_1]</font></center>"); 
    $error="yes";
  }

  /* If no errors have occured, add quote to database */
  if($error!="yes"){
  
    $get = $dbc->query("SELECT * FROM quotes ORDER BY id DESC LIMIT 1");
    
    while ($count = $get->fetchArray()) { 
      $tempid = $count["id"];
    }
    
    /* Increment the id */    
    $newid = $tempid+1;
    $quer1 = sprintf("insert into quotes (id, quote, comment, ip) values ('%d', '%s', '%s', '%s')", $newid, $newquote, $comment, $ip);
	$sql = $dbc->query($quer1);
    
    /* Confirm addition */
    echo("
    <font size='-1'>
      <div width=80% align=center>$lang[add_comment_4] <a href='./?$newid'>$newid</a></div>
    </font>");
  }
}


/**
 * HTML Output for the add quote form ./?add
 */
function addForm(){
global $lang;
global $dbc;
  echo("
  <div width=80% align=center>
    <form action='./?add' name='add' method='POST'>
      <font size='-1'><p>$lang[add_comment_1]</p></font>
      <p><textarea cols='80' rows='5' name='newquote' class='text'></textarea></p>
      <p><i>$lang[add_comment_2] </i><input name='comment' type='text' size='40' maxlength='127'></p>
      <input type='submit' value='$lang[add_button_1]' class='button'>
      <input type='reset' value='$lang[add_button_2]' class='button'><br><br>
      <font size='-1'>$lang[add_comment_3]</font>
    </form>
  </div>");
}

?>
