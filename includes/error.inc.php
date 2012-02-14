<?php
/***************************************************************************
 *   OSQDB, includes/error.inc.php
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
 * Returns error message if SQL connect is unsuccessful.
 */
function databaseError(){
global $lang;
  echo("
  <div width=80% align=center><font size='-1'>
    $lang[error_comment_1]<br /><br />
    $lang[back_link_1]
  </font></div>
  ");
}


/** 
 * Returns error message if a problem is encountered.
 */
function problem(){
global $lang;
  echo("
  <div width=80% align=center>
    <font size='-1'>
      $lang[error_comment_2]<br /><br />
      $lang[back_link_1]<br />
    </font>
  </div>
  ");
}


?>