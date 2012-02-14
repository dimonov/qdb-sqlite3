<?php
/***************************************************************************
 *   OSQDB, includes/sytle.inc.php
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
$folder_name="bash_org";
$tmp=$_SERVER['SCRIPT_FILENAME'];
if (!preg_match("/\bindex.php\b/i", "$tmp") && !preg_match("/\bupgrade.php\b/i", "$tmp")) {
die("Hacking Attempt");
}

$osqdb_path = './';

$sql = $dbc->query("SELECT * FROM templates WHERE used='1'");
$sql2 = $sql->fetchArray();
include($osqdb_path . "templates/" . $folder_name . "/index.php");

include_once ($osqdb_path . "includes/sql.inc.php");
include_once ($osqdb_path . "includes/add.inc.php");
include_once ($osqdb_path . "includes/admin.inc.php");
include_once ($osqdb_path . "includes/error.inc.php");
include_once ($osqdb_path . "includes/search.inc.php");
include_once ($osqdb_path . "includes/view.inc.php");
include_once ($osqdb_path . "includes/voting.inc.php");

global $lang;

bancheck();

  htmltop();
  include_once ($osqdb_path . "includes/para.inc.php");
  htmlbottom();

?>
