<?php
/***************************************************************************
 *   OSQDB, index.php
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

/* Define path, include necessary files */
//error_reporting(0); 
$osqdb_path = './';
include_once ($osqdb_path . 'config.php');
include_once ($osqdb_path . 'includes/sessions.inc.php');
include_once ($osqdb_path . 'includes/style.inc.php');

global $dbc;
/**
 * Check if user is banned from site.
 */
function bancheck(){
global $dbc;
  $ip = do_hash($_SERVER['REMOTE_ADDR']);
  $sql = $dbc->query("SELECT * FROM bans WHERE ip='$ip' and hidden='0'");
  $sql2 = $sql->fetchArray();

  if($sql2["id"]!=""){ 
    echo('You are currently banned from this website<br />Reason: $sql2["reason"]');
    exit();
  }

}

function qdbCloseCheck(){
global $dbc;
  $sql = $dbc->query("SELECT * FROM config WHERE id=4");
  $sql2 = $sql->fetchArray();
  $qdbClosed=$sql2["info"];
  return $qdbClosed; //should return a value of 0 or 1
}

function qdbSubmissionsClosedCheck(){
global $dbc;
  $sql = $dbc->query("SELECT * FROM config WHERE id=5");
  $sql2 = $sql->fetchArray();
  $qdbSubClosed=$sql2["info"];
  return $qdbSubClosed; //should return a value of 0 or 1
}

/**
 * Main information output.
 */
function main(){
global $dbc;
  $sql = $dbc->query("SELECT * FROM config WHERE id='1'");
  $sql2 = $sql->fetchArray();

  printf("
    <table cellpadding='0' cellspacing='9' width='80%%' align=center>
      <tr><td width='50%%' valign='top'>
      <font size='-1'>%s</font>
        </td>
        <td width='1' bgcolor='#6e7b8b'><font size='1' color='#c08000'>|</font></td>
        <td width='50%%' valign='top'>
        <p>
      <font size='-1'>", $sql2["info"]);

  $result = $dbc->query("SELECT * FROM news WHERE hide='0' ORDER BY id DESC");
  
  while ( $row = $result->fetchArray() ) {
    echo("<b>$row[date]</b> - <i>Posted by: $row[author]</i> <br /> $row[news] <br /><br />");
  }

  echo("<br />Managers: ");
  $result = $dbc->query("SELECT * FROM admins WHERE status='MAN' ORDER BY username ASC");
  while ( $row = $result->fetchArray() ) {
    echo("$row[username] ");
  }

  echo("<br />Moderators: ");
  $result = $dbc->query("SELECT * FROM admins WHERE status='MOD' ORDER BY username ASC");
  while ( $row = $result->fetchArray() ) {
    echo("$row[username] ");
  }
  echo("</font></td></tr></table>");
}

?>
