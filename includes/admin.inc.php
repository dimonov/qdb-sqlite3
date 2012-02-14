<?php
/***************************************************************************
 *   OSQDB, includes/admin.inc.php
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
 * Provide selection of links on admin page.
 */
function adminLinks(){
global $lang;
global $loginStatus;
 if($loginStatus=="MAN"){
  echo("
  <div width=80% align=center> 
    :: <a href='./?admin'>$lang[admin_links_1]</a>
    :: <a href='./?admin&edit=review'>$lang[admin_links_13]</a>
    :: <a href='./?admin&addn=news'>$lang[admin_links_2]</a>	 
    :: <a href='./?admin&edit=news'>$lang[admin_links_3]</a>
    :: <a href='./?admin&edit=main'>$lang[admin_links_4]</a>
    :: <a href='./?admin&addn=user'>$lang[admin_links_7]</a>
    :: <a href='./?admin&edit=user'>$lang[admin_links_8]</a>
    :: <a href='./?logout'>$lang[admin_links_9]</a> <br />
    :: <a href='./?admin&ban=add'>$lang[admin_links_10]</a>
    :: <a href='./?admin&ban=del'>$lang[admin_links_11]</a>
    :: <a href='./?admin&ban=list'>$lang[admin_links_12]</a>
    :: <a href='./?admin&addt=template'>$lang[admin_links_14]</a>
    :: <a href='./?admin&template=list'>$lang[admin_links_15]</a> <br />
    :: <a href='./?admin&edit=close'>$lang[admin_links_16]</a>
    :: <a href='./?admin&edit=userPassword'>$lang[admin_links_17]</a> ::
  </div>");
 } else if ($loginStatus=="MOD"){
  echo("
  <div width=80% align=center> 
    :: <a href='./?admin'>$lang[admin_links_1]</a>
    :: <a href='./?admin&edit=review'>$lang[admin_links_13]</a>
    :: <a href='./?logout'>$lang[admin_links_9]</a> <br />
    :: <a href='./?admin&ban=add'>$lang[admin_links_10]</a>
    :: <a href='./?admin&ban=del'>$lang[admin_links_11]</a>
    :: <a href='./?admin&ban=list'>$lang[admin_links_12]</a>
    :: <a href='./?admin&edit=userPassword'>$lang[admin_links_17]</a> ::
  </div>");

 }
}


/**
 * Generic function; outputs admin links, and lists quotes for approval
 *
 * Parameters: $c - Amount of quotes shown per page.
 */
function admin($c){
global $lang;
global $dbc;
$c = escape_str($c);
  /* Display admin links */
  adminLinks();
  
  /* If parameter is not passed, set default */
  if($c==""){ $c=25; }

  $result = $dbc->query("SELECT * FROM quotes WHERE approved='0' ORDER BY id ASC LIMIT $c");
  
  while ( $row = $result->fetchArray()) {
  
    echo("
      <blockquote>
        <a href='./?qnum=$row[id]' title='Permanent link to this quote.'><b>#$row[id]</b></a> 
        <a href='./?admin&c=$c&accept=$row[id]' class='qa'>$lang[admin_main_comment_1]</a>
        <a href='./?admin&c=$c&reject=$row[id]' class='qa'>$lang[admin_main_comment_2]</a>
        <font size=-1>
        <br />$row[quote]<br />");
          
    if($row[comment]!=""){ echo("<i>$lang[output_comment_1]</i>$row[comment]<br />"); }
    echo("</font></blockquote>");
  }
}


/**
 * Accept or reject quote. 
 * 
 * Parameters: $action - Contains result (Accepted/Rejected).
 *             $id     - Quote ID.
 *             $c      - Amount of quotes to show on admin page.
 */
function adminAction($action, $id, $c){
global $lang;
global $dbc;
$id = escape_str($id);
  if($action=="accepted"){
    $sql = $dbc->query("UPDATE quotes SET approved = '1' WHERE id ='$id'");
    echo("<font size='-1'>$lang[admin_accept_1] #$id</font>");
  } else if($action=="rejected"){
    $sql = $dbc->query("UPDATE quotes SET approved = '2' WHERE id ='$id'");
    echo("<font size='-1'>$lang[admin_reject_1] #$id</font>");
  }
  admin($c);
}


/**
 * HTML Output for Login form.
 */
function adminForm(){
global $lang;
  echo("
    <div width=80% align=center>
      <form action='./?admin' method='POST' name='admin'>
        <table border=0>
          <tr>
            <td align=left>$lang[admin_username_comment_1]</td>
            <td><input type='input' name='uid' size='16' class='text'></td>
          </tr>
          <tr>
            <td align=left>$lang[admin_password_comment_1]</td>
            <td><input type='password' name='pwd2' size='16' class='text'></td>
          </tr>
          <tr>
            <td align=center colspan=2>
              <select name='c'>
                <option value='10'>10</option>
                <option value='25' selected>25</option>
                <option value='50'>50</option>
                <option value='100'>100</option>
              </select>
              <input type='submit' value='$lang[admin_login_1]' class='button'>
             </td>
          </tr>
	</table>
      </form>
    </div>");
}


/**
 * Form used for banning users from site.
 */
function banAddForm(){
global $lang;
  adminLinks();

  echo("
    <br />
    <center>
      <form method=post action='./?admin&ban=add'>
        IP: <input type='text' name='banip' length=15 maxlength=15><br /> 
        $lang[admin_ban_comment_1] <input type='text' name='banreason' length=200><br />
        <input type=reset value='$lang[reset_button_1]' />
        <input type=submit name='submitok' value='$lang[submit_button_1]' />
      </form>
    </center>");
}


/**
 * Adds IP bans to database.
 *
 * Parameters: $banip - The IP being banned.
 *             $banreason - The reason for user being banned.
 */
function banIP($banip, $banreason){
  adminLinks();
global $lang;
global $dbc;
  $get = $dbc->query("SELECT * FROM bans ORDER BY id DESC LIMIT 1");
  
  while ($count = $get->fetcharray()) { 
    $tempid = $count["id"];
  }
  
  /* Increment the userid */    
  $newid = $tempid++;
  
  /* Clean up the body */
  $body = nl2br($body);
  $date = $date_now = date("d/m/y (H:i)");
  $banreason = escape_str($banreason); 
  $banip = escape_str($banip); 
 
  /* SQL Input */
  $sql = $dbc->query("INSERT INTO bans SET id = '$newid'");
  $sql = $dbc->query("UPDATE bans SET ip = '$banip' WHERE id = '$newid'");
  $sql = $dbc->query("UPDATE bans SET reason = '$banreason' WHERE id = '$newid'");
  
  updated();
}


/**
 * Display current bans, with option to remove.
 */
function banList(){
global $lang;
global $dbc;
  adminLinks();
  $result = $dbc->query("SELECT * FROM bans WHERE hidden='0' ORDER BY id ASC");
  echo("<blockquote><font size=-1>");
  
  while ( $row = $result->fetchArray()) {
    echo("$row[ip] : $row[reason] -- <a href='./?admin&ban=del&unbanip=$row[ip]'>$lang[admin_ban_comment_2]</a><br />");
  }
}

function closeThing($closeSubmission, $closeSubmissionReason, $closeBoard, $closeBoardReason){
global $dbc;

//$closeSubmission = escape_str($closeSubmission);
//$closeBoard = escape_str($closeBoard);
$closeSubmissionReason = escape_str($closeSubmissionReason);
$closeBoardReason = escape_str($closeBoardReason); 

 if($closeBoard=="on"){
    $sql = $dbc->query("UPDATE config SET info = '1' WHERE id = '4'");
    $sql = $dbc->query("UPDATE config SET extra = '$closeBoardReason' WHERE id = '4'");
  } else {
    $sql = $dbc->query("UPDATE config SET info = '0' WHERE id = '4'");
 }

 if($closeSubmission=="on"){
    $sql = $dbc->query("UPDATE config SET info = '1' WHERE id = '5'");
    $sql = $dbc->query("UPDATE config SET extra = '$closeSubmissionReason' WHERE id = '5'");
  } else {
    $sql = $dbc->query("UPDATE config SET info = '0' WHERE id = '5'");
 }

 updated();

}

function changeUserPassword($id, $newpassword, $changepwd){
global $lang;
global $dbc;
  adminLinks();
 if($changepwd=="on"){
  $newpassword=md5($newpassword);
  $id = escape_str($id);
  $sql = $dbc->query("UPDATE admins SET password = '$newpassword' WHERE id ='$id'");
 }
  updated();
}


function changeUserPasswordForm(){
global $lang, $userid;
global $dbc;
  adminLinks();
global $loginStatus;
  $sql = $dbc->query("SELECT * FROM admins WHERE id='$userid'");
  $sql2 = $sql->fetchArray();
  $username = $sql2["username"];

  echo("
    <blockquote><font size=-1>
      <form method=post action='./?admin&edit=userPassword&id=$userid'>");
	echo("$lang[admin_username_comment_1] $username<br />
	$lang[admin_edituser_comment_1] <input type=password name='newpassword' size=50 maxlength=50 />
	<input type=checkbox name='changepwd'>$lang[admin_edituser_comment_2]<br />
	<center>
        <input type=reset value='$lang[reset_button_1]' />
        <input type=submit name='submitok' value='$lang[submit_button_1]' />
        </center>
      </form>
    </font></blockquote>");
}


function closeForm(){
global $lang;
global $dbc;
  adminLinks();

  $sql = $dbc->query("SELECT * FROM config WHERE id='4'");
  $sql2 = $sql->fetchArray();

  $qdbClosed = $sql2["info"];
  $qdbClosedReason = $sql2["extra"];

  $sql = $dbc->query("SELECT * FROM config WHERE id='5'");
  $sql2 = $sql->fetchArray();

  $qdbSubClosed = $sql2["info"];
  $qdbSubClosedReason = $sql2["extra"];

  echo("
    <center>
    <table width=50%>
      <form method=post action='./?admin&edit=close'>
	<input type=hidden name=formChecked value=yes>
	<tr><td align=center>$lang[admin_close_1]</td><td align=center>$lang[admin_close_3]</td><tr>
	<tr>");

	if($qdbClosed==1){
	  echo("<td align=center><input type=checkbox name='closeBoard' checked=yes></td>
	  <td align=center><input type=text name='closeBoardReason' value='$qdbClosedReason'></td>");
	} else {
	echo("<td align=center><input type=checkbox name='closeBoard'></td>
	  <td align=center><input type=text name='closeBoardReason'></td>");
	}

  echo("</tr>
	<tr><td align=center>$lang[admin_close_2]</td><td align=center>$lang[admin_close_3]</td><tr>
	<tr>");

	if($qdbSubClosed==1){
	  echo("<td align=center><input type=checkbox name='closeSubmission' checked=yes></td>
	  <td align=center><input type=text name='closeSubmissionReason' value='$qdbSubClosedReason'></td>");
	} else {
	echo("<td align=center><input type=checkbox name='closeSubmission'></td>
	  <td align=center><input type=text name='closeSubmissionReason'></td>");
	}

  echo("</tr>
	</table>
        <input type=reset value='$lang[reset_button_1]' />
        <input type=submit name='submitok' value='$lang[submit_button_1]' />
      </form>
    </center>");
}

/**
 * Log admin out (checks to see if user is logged in first).
 * 
 * Parameters: $loggedin - Boolean to check if user is currently logged in.
 */
function logout($loggedin){
global $lang;
  if($loggedin=="true"){  			  
    session_unregister("uid");
    session_unregister("pwd");
    $loggedin = 'false';
    echo("<div width=80% align=center><font size='-1'>$lang[admin_logout_comment_1]<br /><br /><a href='./'>[Back]</a></font></div>");
  } else {
    echo("<div width=80% align=center><font size='-1'>$lang[admin_logout_error_1]<br /><br /><a href='./'>[Back]</a></font></div>"); 
  }
}


/**
 * Editor for the information shown in the left column of the front page.
 * 
 * Parameters: $body - The information to be added to front page.
 */
function mainEdit($body){
global $lang;
global $dbc;
  adminLinks();
global $loginStatus;
 if($loginStatus!="MAN"){ echo("$lang[error_comment_7]"); } else {
  $body = nl2br(escape_str($body));
  $sql = $dbc->query("UPDATE config SET info = '$body' WHERE id ='1'");
  updated();
 }
}


/** 
 * HTML Output Form for the main - left side of main page. 
 */
function mainEditForm(){
global $lang;
global $dbc;
  adminLinks();
global $loginStatus;
 if($loginStatus!="MAN"){ echo("$lang[error_comment_7]"); } else {
  $sql = $dbc->query("SELECT * FROM config WHERE id='1'");
  $sql2 = $sql->fetchArray();

  $body = $sql2["info"];
  $body = str_replace("<br />", "", $body);
  $body = str_replace("&lt;br /&gt;", "", $body);

  echo("
    <center>
      <form method=post action='./?admin&edit=main'>
        <textarea id='body' name='body' rows='15' cols='60'>$body</textarea><br />
        <input type=reset value='$lang[reset_button_1]' />
        <input type=submit name='submitok' value='$lang[submit_button_1]' />
      </form>
    </center>");
 }
}

/**
 * SQL Input for new addition - NOTE not currently set up for session username.
 * 
 * Parameters: $name - Author of news post.
 *             $body - News post text.
 */
function newsAdd($name, $body){
global $lang;
global $dbc;
  adminLinks();
global $loginStatus;
 if($loginStatus!="MAN"){ echo("$lang[error_comment_7]"); } else {
  $get = $dbc->query("SELECT * FROM news ORDER BY id DESC LIMIT 1");
  
  while ($count = $get->fetchArray()) { 
    $tempid = $count["id"];
  }

  /* Increment the userid */    
  $newid = $tempid++;

  /* Clean up the body */
  $body = nl2br(escape_str($body));
  $name = escape_str($name);
  $date = date("$lang[date_layout]");
  
  /* SQL Input */
  $sql = $dbc->query("INSERT INTO news SET id = '$newid'");
  $sql = $dbc->query("UPDATE news SET author = '$name' WHERE id = '$newid'");
  $sql = $dbc->query("UPDATE news SET news = '$body' WHERE id = '$newid'");
  $sql = $dbc->query("UPDATE news SET date = '$date' WHERE id = '$newid'");
  
  /* Called updated output function */
  updated();
 }
}


/** 
 * HTML Output Form for news addition.
 */
function newsAddForm(){
global $lang;
global $dbc;
  adminLinks();
global $loginStatus;
 if($loginStatus!="MAN"){ echo("$lang[error_comment_7]"); } else {
  echo("
    <center>
      <form method=post action='./?admin&addn=news'>
        $lang[admin_author_1] <input type=text name='name' size=50 maxlength=50 /><br />
        $lang[admin_news_1] <textarea id='body' name='body' rows='15' cols='60'></textarea><br />
        <input type=reset value='$lang[reset_button_1]' />
        <input type=submit name='submitok' value='$lang[submit_button_1]' />
      </form>
    </center>");
 }
}


/**
 * HTML Output Form for news editing.
 * 
 * Parameters: $id - ID of news item
 */
function newsEditForm($id){
global $lang;
global $dbc;
  adminLinks();
global $loginStatus;
$id = escape_str($id);
 if($loginStatus!="MAN"){ echo("$lang[error_comment_7]"); } else {
  $sql = $dbc->query("SELECT * FROM news WHERE id='$id'");
  $sql2 = $sql->fetchArray();

  $body = $sql2["news"];
  $body = str_replace("<br />", "", $body);
  $body = str_replace("&lt;br /&gt;", "", $body);

  printf("
    <center>
      <form method=post action='./?admin&edit=news&id=%s'>
        <input type='text' id='author' name='author' length='50'
        maxlength='50' value='%s'><br />
        <textarea id='body' name='body' rows='15' cols='60'>%s</textarea><br />
        <input type=reset value='%s' />
        <input type=submit name='submitok' value='%s' />
      </form>
    </center>", $id, $sql2["author"], $body, $lang[reset_button_1],
		$lang[submit_button_1]);
 }
}


/**
 * SQL input for editing news.
 * 
 * Parameters: $id - ID of news post to be edited.
 *             $body - Text of news post to be edited.
 *             $author - Author of the news post to be edited.
 */
function newsEdit($id, $body, $author){
global $lang;
global $dbc;
  adminLinks();
global $loginStatus;
 if($loginStatus!="MAN"){ echo("$lang[error_comment_7]"); } else {
  $body = nl2br(escape_str($body));
  $id = escape_str($id);
  $author = escape_str($author);
  $sql = $dbc->query("UPDATE news SET news = '$body' WHERE id ='$id'");
  $sql = $dbc->query("UPDATE news SET author = '$author' WHERE id ='$id'");
  updated();
 }
}


/**
 * Toggles hiding a news post.
 *
 * Parameters: $id - ID of news post to be hidden.
 *             $type - Whether to hide or unhide post.
 */
function newsHideToggle($id, $type){
global $lang;
global $dbc;
  adminLinks();
global $loginStatus;
$id = escape_str($id);
$type = escape_str($type);
 if($loginStatus!="MAN"){ echo("$lang[error_comment_7]"); } else {
  $sql = $dbc->query("UPDATE news SET hide = '$type' WHERE id ='$id'");
  updated();
 }
}


/**
 * HTML Form for editing news posts
 */
function newsEditDisplay(){
global $lang;
global $dbc;
  adminLinks();
global $loginStatus;
 if($loginStatus!="MAN"){ echo("$lang[error_comment_7]"); } else {
  $result = $dbc->query("SELECT * FROM news ORDER BY id DESC");
  echo("<blockquote><font size=-1>");
  
  while ( $row = $result->fetchArray()) {
  
    echo("
      <b>$row[date]</b> - <i>Posted by: $row[author]</i> <br /> $row[news] <br />
      <a href='./?admin&edit=news&id=$row[id]'>edit</a> / ");
      
    if($row[hide]==0){
      echo("<a href='./?admin&edit=news&hide=1&id=$row[id]'>$lang[admin_hide_comment_1]</a><br /><br />");
    } else {
      echo("<a href='./?admin&edit=news&hide=0&id=$row[id]'>$lang[admin_hide_comment_2]</a><br /><br />");
    }
  }
  echo("</font></blockquote>");
 }
}

function reviewSux(){
global $lang;
global $dbc;
  adminLinks();
  $result = $dbc->query("SELECT * FROM quotes WHERE approved='1' && sux='1' ORDER BY id DESC");
  echo("<blockquote><font size=-1>");
  
  while ( $row = $result->fetchArray()) {
	echo("<br><a href='./?qnum=$id' title='$lang[output_comment_2]'><b>#$row[id]</b></a>
	($row[score])<font size=-1><br />$row[quote]<br />");
  	if($comment!=""){ echo("<i>$lang[output_comment_1] </i>$comment<br>");	}
	echo("<a href='./?admin&acceptr=$row[id]' class='qa'>$lang[admin_main_comment_3]</a> / 
              <a href='./?admin&rejectr=$row[id]' class='qa'> $lang[admin_main_comment_4]</a>");
  }
}

function reviewSuxAction($action, $id){
global $lang;
global $dbc;
$id = escape_str($id);
  if($action=="accepted"){
    $sql = $dbc->query("UPDATE quotes SET sux = '0' WHERE id ='$id'");
    echo("<font size='-1'>$lang[admin_accept_1] #$id</font>");
  } else if($action=="rejected"){
    $sql = $dbc->query("UPDATE quotes SET approved = '2' WHERE id ='$id'");
    echo("<font size='-1'>$lang[admin_reject_1] #$id</font>");
  }
}

function templateAddForm(){
global $lang;
global $dbc;
  adminLinks();
global $loginStatus;
 if($loginStatus!="MAN"){ echo("$lang[error_comment_7]"); } else {
  echo("<br /><center>
        <form method=post action='./?admin&addt=template'>
	  $lang[template_comment_1]<input type='text' name='foldername' length=60 maxlength=200 /><br />
	  $lang[template_comment_2]<br />
          <input type=reset value='$lang[reset_button_1]' />
          <input type=submit name='submitok' value='$lang[submit_button_1]' />
        </form>
	</center>");
 }
}

function templateAdd($foldername){
global $lang;
global $dbc;
global $loginStatus;
$foldername = escape_str($foldername);
 if($loginStatus!="MAN"){ echo("$lang[error_comment_7]"); } else {
  $get = $dbc->query("SELECT * FROM templates ORDER BY id DESC LIMIT 1");  
  while ($count = $get->fetchArray()) { 
    $tempid = $count["id"];
  }
    
  $newid = $tempid+1;
  $sql = $dbc->query("INSERT INTO templates SET id = '$newid'");
  $sql = $dbc->query("UPDATE templates SET folder_name = '$foldername' WHERE id = '$newid'");
  updated();
 }
}

function templateSelectList(){
global $lang;
global $dbc;
  adminLinks();
global $loginStatus;
 if($loginStatus!="MAN"){ echo("$lang[error_comment_7]"); } else {
  $result = $dbc->query("SELECT * FROM templates ORDER BY id ASC");
  echo("<blockquote><font size=-1>");
  
  while ( $row = $result->fetchArray()) {
   if($row[used]==1){
    echo("$row[folder_name] : <u>$lang[template_comment_5]</u><br />");
   } else {
    echo("$row[folder_name] : <a href='./?admin&template=$row[id]&use=yes'>$lang[template_comment_3]</a> / 
	  <a href='./?admin&template=$row[id]&delete=yes'>$lang[template_comment_4]</a><br />");
   }
  }
  echo("</font></blockquote>");
 }
}

function templateAction($id, $action){
global $dbc;
global $lang;
global $loginStatus;
$id = escape_str($id);
 if($loginStatus!="MAN"){ echo("$lang[error_comment_7]"); } else {
  if($action=="use"){
    $sql = $dbc->query("SELECT * FROM templates WHERE id='$id'");
    $sql2 = $sql->fetcharray();
    $filename = "./templates/";
    $filename .= $sql2["folder_name"];
    $filename .= "/index.php";
    if(file_exists($filename)){
     $sql = $dbc->query("SELECT * FROM templates WHERE used='1'");
     $sql2 = $sql->fetchArray();
     $cid = $sql2["id"];
     $sql = $dbc->query("UPDATE templates SET used = '0' WHERE id = '$cid'");
     $sql = $dbc->query("UPDATE templates SET used = '1' WHERE id = '$id'");
     updated();
    } else {
      echo("<center>$lang[template_error_2] <br />$lang[back_link_1]</center>");
    }
  } else if($action=="del"){
    $sql = $dbc->query("SELECT * FROM templates WHERE id='$id'");
    $sql2 = $sql->fetchArray();
    $used = $sql2["used"];
   if($used=="1"){ 
      echo("<center>$lang[template_error_1] <br />$lang[back_link_1]</center>");
   } else {
    if($id=="1"){ echo("<center>$lang[template_error_3] <br /> $lang[back_link_1]</center>"); } else {
      $sql = $dbc->query("DELETE FROM templates WHERE id=$id");
      updated();
    }
   }
  }
 }
}

/**
 * HTML Form for unbanning an IP address from site.
 */
function unbanIpForm(){
global $lang;
  adminLinks();
  echo("
    <br />
    <center>
      <form method=post action='./?admin&ban=del'>
        IP: <input tpye='text' name='unbanip' length=15 maxlength=15 /><br />
        <input type=reset value='$lang[reset_button_1]' />
        <input type=submit name='submitok' value='$lang[submit_button_1]' />
      </form>
    </center>");
}


/**
 * SQL input for unbanning an IP address from the site.
 *
 * Parameters: $ip - The IP address to be unbanned.
 */
function unbanIP($ip){
global $lang;
global $dbc;
  adminLinks();
$ip = escape_str($ip);
	$sql = $dbc->query("SELECT * FROM bans WHERE ip='$ip'");
  $sql2 = $sql->fetchArray();
	
	if($sql2["id"]==""){ 
		echo("$lang[admin_ban_comment_3]");
	} else {
		$sql = $dbc->query("UPDATE bans SET hidden = '1' WHERE id ='$sql2[id]'");
		updated();
	}
}


/**
 * Simply lets admin know settings are updated, and gives option to go back.
 */
function updated(){
global $lang;
   echo("<br /><font size='-1'><center><i>$lang[admin_update_comment_1]</i><br /><br />
	 $lang[back_link_2]<br /></font></center>");
}


/**
 * Add an admin to the database.
 *
 * Parameters: $newusername - The username of the new admin.
 *             $newpassword - The password of the new admin.
 */
function userAdd($newusername, $newpassword, $newstatus){
global $dbc;
global $lang;
  adminLinks();
global $loginStatus;
$newusername = escape_str($newusername);
 if($loginStatus!="MAN"){ echo("$lang[error_comment_7]"); } else {
  $get = $dbc->query("SELECT * FROM admins ORDER BY id DESC LIMIT 1");
  
  while ($count = $get->fetchArray()) { 
    $tempid = $count["id"];
  }
   
  if($newstatus != "MOD" && $newstatus != "MAN"){ $newstatus = "MOD"; }
 
  $newid = $tempid++;
  $newpassword=md5($newpassword);
  $sql = $dbc->query("INSERT INTO admins SET id = '$newid'");
  $sql = $dbc->query("UPDATE admins SET username = '$newusername' WHERE id = '$newid'");
  $sql = $dbc->query("UPDATE admins SET password = '$newpassword' WHERE id = '$newid'");
  $sql = $dbc->query("UPDATE admins SET status = '$newstatus' WHERE id = '$newid'");
  updated();
 }
}


/**
 * HTML Form for adding admin to site.
 */
function userAddForm(){
global $lang;
  adminLinks();
global $loginStatus;
 if($loginStatus!="MAN"){ echo("$lang[error_comment_7]"); } else {
  echo("
    <blockquote><font size=-1>
      <form method=post action='./?admin&addn=user'>
        $lang[admin_username_comment_1] <input type=text name='newusername' size=50 maxlength=50 /><br />
        $lang[admin_edituser_comment_1] <input type=password name='newpassword' size=50 maxlength=50 /><br />
	<select name='newstatus'><option value='MOD'>$lang[moderator_comment_1]</option><option value='MAN'>$lang[manager_comment_1]</option></select><br />
        <center>
        <input type=reset value='$lang[reset_button_1]' />
        <input type=submit name='submitok' value='$lang[submit_button_1]' />
      </form>
    </font></blockquote>");
 }
}

function userDel($id){
global $dbc;
global $lang;
global $loginStatus;
$id = escape_str($id);
 if($loginStatus!="MAN"){ echo("$lang[error_comment_7]"); } else {
  if($id==1){
    echo("<center> $lang[error_comment_6] <br /> $lang[back_link_1]</center>");
  } else {
    $sql = $dbc->query("DELETE FROM admins WHERE id=$id");
    updated();
  }
 }
}

/**
 * SQL input for updating admin details.
 * 
 * Parameters: $id - ID of administrator to be edited.
 *             $newusername - New username.
 *             $newpassword - New password.
 */
function userEdit($id, $newusername, $newpassword, $newstatus, $changepwd){
global $dbc;
global $lang;
  adminLinks();
global $loginStatus;
$id = escape_str($id);
$newusername = escape_str($newusername);
 if($id == "1"){ $newstatus = "ADM"; }
 if($loginStatus!="MAN"){ echo("$lang[error_comment_7]"); } else {
  if($newstatus != "MOD" && $newstatus != "MAN"){ $newstatus = "MOD"; }
  if($id == "1") { $newstatus = "ADM"; }
  if($id == "1" && $newusername !="admin"){ echo("$lang[error_comment_8]"); 
    $sql = $dbc->query("UPDATE admins SET username = 'admin' WHERE id ='$id'");
    $sql = $dbc->query("UPDATE admins SET status = '$newstatus' WHERE id = '$id'");
  } else {
    $sql = $dbc->query("UPDATE admins SET username = '$newusername' WHERE id ='$id'");
    $sql = $dbc->query("UPDATE admins SET status = '$newstatus' WHERE id = '$id'");
  }

 if($changepwd=="on"){
  $newpassword=md5($newpassword);
  $sql = $dbc->query("UPDATE admins SET password = '$newpassword' WHERE id ='$id'");
 }
  updated();
 }
}

/**
 * HTML Form for editing admin details.
 *
 * Parameters: $id - ID of administrator to be edited.
 */
function userEditForm($id){
global $dbc;
global $lang;
  adminLinks();
global $loginStatus;
$id = escape_str($id);
 if($loginStatus!="MAN"){ echo("$lang[error_comment_7]"); } else {
  $sql = $dbc->query("SELECT * FROM admins WHERE id='$id'");
  $sql2 = $sql->fetchArray();
  $username = $sql2["username"];

  echo("
    <blockquote><font size=-1>
      <form method=post action='./?admin&edit=user&id=$id'>");
  if($id=='1'){
	echo("$lang[admin_username_comment_1] $username<br />");
	echo("<input type=hidden name='newusername' value='admin'>");
  } else {
    echo("$lang[admin_username_comment_1] <input type=text name='newusername' size=50 maxlength=50 / value=$username><br />");
  }
    echo("$lang[admin_edituser_comment_1] <input type=password name='newpassword' size=50 maxlength=50 />
	<input type=checkbox name='changepwd'>$lang[admin_edituser_comment_2]<br />
      ");
if($sql2["status"]=="MOD"){
  echo("<select name='newstatus'><option value='MOD' selected='true'>$lang[moderator_comment_1]</option><option value='MAN'>$lang[manager_comment_1]</option></select><br />");
} else if($sql2["status"]=="MAN"){
  echo("<select name='newstatus'><option value='MOD'>$lang[moderator_comment_1]</option><option value='MAN' selected='true'>$lang[manager_comment_1]</option></select><br />");
}
echo(" <center>
        <input type=reset value='$lang[reset_button_1]' />
        <input type=submit name='submitok' value='$lang[submit_button_1]' />
        </center>
      </form>
    </font></blockquote>");
 }
}

/**
 * Display list of admins with option to modify.
 */
function userEditList(){
global $dbc;
global $lang;
  adminLinks();
global $loginStatus;
 if($loginStatus!="MAN"){ echo("$lang[error_comment_7]"); } else {
  $result = $dbc->query("SELECT * FROM admins ORDER BY id ASC");
  echo("<blockquote><font size=-1>");
  
  while ( $row = $result->fetchArray()) {
    echo("$row[username] : <a href='./?admin&edit=user&id=$row[id]'>$lang[admin_edit_comment_1]</a> /
	  <a href='./?admin&del=user&id=$row[id]'> $lang[admin_del_1]</a><br />");
  }
  
  echo("</font></blockquote>");
 }
}

?>
