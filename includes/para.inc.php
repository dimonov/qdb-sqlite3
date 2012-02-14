<?php
/***************************************************************************
 *   OSQDB, includes/para.inc.php
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
$quotenum = 0;
/* Stop include file being called directly */
$tmp = $_SERVER['SCRIPT_FILENAME'];
if (!preg_match("/\bindex.php\b/i", "$tmp") && !preg_match("/\bupgrade.php\b/i", "$tmp")) { error("Hacking Attempt"); }

/**
 * This code is a little awful at present, but there are so many options,
 * it's extremely difficult to do any other way.
 */

 
$boardClosedCheck = qdbCloseCheck();
if($boardClosedCheck==1 && !isset($_GET['admin'])){
 $sql = $dbc->query("SELECT * FROM config WHERE id='4'");
 $sql2 = $sql->fetchArray();
  echo("<div width=80% align=center>");
  echo("Board is currently closed.<br />\n");
  if (!is_null($sql2["extra"])) { printf("%s<br />\n", $sql2["extra"]); }
  echo("</div>");
} else {
  	
  if (isset($_GET['latest']))       { latest(); }
  else if (isset($_GET['about']))   { about(); }
  else if (isset($_GET['qnum']))   { $quotenum=$_GET['qnum']; view($quotenum); }
  else if (isset($_GET['random']))  { random("0"); }
  else if (isset($_GET['random1'])) { random("1"); }
  else if (isset($_GET['top']))     { top("0"); }
  else if (isset($_GET['top2']))    { top("1"); }
  else if (isset($_GET['worst']))   { worst(); }
  else if (isset($_GET['logout']))  { logout($loggedin); }
  else if (isset($_GET['vote']))    { vote($_POST['r']); }
  else if (isset($_GET['rox']))     { updateVote($rox, "upgraded"); }  
  else if (isset($_GET['sox']))     { updateVote($sox, "downgraded"); }  
  else if (isset($_GET['sux']))     { sux($_GET['sux']); }

  else if (isset($_GET['browse'])) { 
    if (isset($_GET['page'])) { browse($_GET['page']); }
    else { browse(1); }
  }

  else if (isset($_GET['queue'])) { 
    if (isset($_GET['page'])) { queue($_GET['page']); }
    else { queue(1); } 
  }

  else if (isset($_GET['searchq'])) {
    if (isset($_GET['sort'])) { $search_sort = $_GET['sort']; }
    else { $search_sort = 0; }
    
    if (isset($_GET['show'])) { $search_show = $_GET['show']; }
    else { $search_show = 25; }
    
    searchResults($search_sort, $search_show, $_GET['searchq']); 
  }

  else if (isset($_GET['add'])) {
    $check = qdbSubmissionsClosedCheck();
    if($check == 1){
    $sql = $dbc->query("SELECT * FROM config WHERE id='5'");
    $sql2 = $sql->fetchArray();
      echo("<div width=80% align=center>");
      echo("Quote submissions on this board have been temporarily closed.<br>\n");  // TODO: Move to language file
  	if (!is_null($sql2["extra"])) { printf("%s<br/>\n", $sql2["extra"]); }
      echo("</div>");
    } else {
      if(isset($_POST['newquote']) && isset($_POST['comment'])) { add($_POST['newquote'], $_POST['comment']); }
      else { addForm(); }
    }
  }

  else if (isset($_GET['search'])) {
    if (!isset($_GET['sort']) && !isset($_GET['show']) && !isset($_GET['searchq'])){ searchForm(); }
    else { searchResults($_GET['sort'], $_GET['show'], $_GET['searchq']); }
  }
  
  else if(isset($admin)) {
    if ($loggedin == "false") { adminForm(); } // If not logged in, display login request
    else {

      // Admin accepting/rejecting quotes    
      if (isset($_GET['c'])) { 
        if (isset($_GET['accept'])) { adminAction("accepted", $_GET['accept'], $_GET['c']); }
        else if (isset($_GET['reject'])) { adminAction("rejected", $_GET['reject'], $_GET['c']); }
	else { admin($_GET['c']); }
      }
      
      // Admin deleting user
      else if(isset($_GET['del'])) {
  	if ($_GET['del'] == "user") {      
	  if (!isset($_GET['id'])){ userEditList(); } else { userDel($_GET['id']); }
	}
      }
      
      else if(isset($_GET['addn'])) {
	      
        // Admin adding news
        if($_GET['addn'] == "news"){
          if(isset($_POST['name']) && isset($_POST['body'])){ newsAdd($_POST['name'], $_POST['body']); }
          else { newsAddForm(); }
        }
      
        // Admin adding user
        else if($_GET['addn'] == "user"){
          if (isset($_POST['newusername']) && isset($_POST['newpassword'])) { userAdd($_POST['newusername'], $_POST['newpassword'], $_POST['newstatus']); }
          else { userAddForm(); }
        }
      }
      
      else if(isset($_GET['addt'])){
	// Admin adding template      
        if ($_GET['addt'] == "template") {
      	  if ($_GET['foldername'] != "") { templateAdd($_GET['foldername']); } 
	  else { templateAddForm(); }
        }
      }
      
      else if (isset($_GET['template'])) {
	// Admin lists available templates
        if ($_GET['template'] == "list" || $_GET['template'] == "") { templateSelectList(); } 
	else {
	  // Admin selects template from list
          if ($_GET['use'] == "yes") { templateAction($_GET['template'], "use"); } 
	  
	  // Admin deletes template from list
	  else if ($_GET['delete'] == "yes") { templateAction($_GET['template'], "del"); } 
	  
	  // Default action, display template list
	  else { templateSelectList(); }
        }
      }

      // Admin accepts review
      else if (isset($_GET['acceptr'])) { reviewSuxAction("accepted", $_GET['acceptr']); reviewSux(); }

      // Admin rejects review
      else if (isset($_GET['rejectr'])) { reviewSuxAction("rejected", $_GET['rejectr']); reviewSux(); }


      else if (isset($_GET['edit'])) {
        // Admin edits news post
        if($edit=="news"){
          if(isset($_GET['hide']) && isset($_GET['id'])){ newsHideToggle($_GET['id'], $_GET['hide']); }
          else if(!isset($_GET['hide']) && isset($_GET['id']) && !isset($body)){ newsEditForm($_GET['id']); }
          else if(!isset($_GET['hide']) && isset($_GET['id']) && isset($body)){ newsEdit($_GET['id'], $_POST['body'], $_POST['author']); }
          else { newsEditDisplay(); }
        }

	// Admin edits main page
        else if ($_GET['edit'] == "main") {
          if ($_POST['body'] == "") { mainEditForm(); } 
	  else { mainEdit($_POST['body']); }
        }

        else if($edit=="userPassword"){
          if(!isset($_POST['changepwd'])){
		changeUserPasswordForm();
	  } else {
	 	changeUserPassword($_GET['id'], $_POST['newpassword'], $_POST['changepwd']);
	  }
        }

	// Admin edits user details

        else if($edit=="user"){
          if(!isset($_GET['id']) && !isset($_POST['newusername']) && !isset($_POST['newpassword'])){ userEditList(); }
          else if(isset($_GET['id']) && !isset($_POST['newusername']) && !isset($_POST['newpassword'])){ userEditForm($_GET['id']); }
          else if(isset($_GET['id']) && isset($_POST['newusername']) && isset($_POST['newpassword'])){ userEdit($_GET['id'], $_POST['newusername'], $_POST['newpassword'], $_POST['newstatus'], $_POST['changepwd']); }
          else { userEditList(); }
        }
        else if ($_GET['edit'] == "review") { reviewSux(); }
        else if($_GET['edit']=="close"){
		if(!isset($_POST['formChecked'])){
			closeForm(); 
		} else {
			closeThing($_POST['closeSubmission'], $_POST['closeSubmissionReason'], $_POST['closeBoard'], $_POST['closeBoardReason']);
		}
	}
      }

      // Admin adds IP ban
      else if ($_GET['ban'] == "add") {
        if (!isset($_POST['banip'])){ banAddForm(); }
        else { banIP($_POST['banip'], $_POST['banreason']); }
      }

      // Admin removes IP ban
      else if ($_GET['ban'] == "del") {
        if (!isset($_POST['unbanip'])) { unbanIpForm(); }
        else { unbanIP($_POST['unbanip']); }
      }

      // Admin lists IP bans
      else if ($_GET['ban'] == "list") { banList(); }
      else { admin($_GET['c']); }
    }
  }

  else if($quotenum == ""){ main(); }
  else { view($quotenum); }

}
?> 
