<?php
/***************************************************************************
 *   OSQDB, includes/voting.inc.php
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
 * Mod comment down.
 * 
 * Parameter: $id - Quote ID being modified.
 */
function sux($id){
global $dbc;
global $lang;
$id = escape_str($id);
  $sql = $dbc->query("SELECT * FROM quotes WHERE id='$id'");
  $sql2 = $sql->fetchArray();

  if($sql2["quote"]=="" || $sql2["approved"]=="0"){ problem(); } 
  else {
    $ip = hash("sha1", $_SERVER['REMOTE_ADDR']);
    $sql = $dbc->query("UPDATE quotes SET sux = '1' WHERE id ='$id'");
    voteUpdated("sux", $id);
  }
}

function vote($r){
global $dbc;
global $lang;
$voted = "no";
 if($r!=""){
  end($r);
  $end=key($r);
  reset($r);
  $ip = do_hash($_SERVER['REMOTE_ADDR']);

  $tmp=0;

//This is kind of a temp fix, there's a problem with the voting script that will randomly decide how many items need processed.
//So this has been changed from $end (length of array) to 100 (the max number that will need processed i.e. 100 in search).
//I'll fix this at a later stage when we get an adsl connection in the house (blame BT and PLUS.net) because I need on php.net.
  //while($tmp!=$end){
  while($tmp!=100){
    $pos=escape_str(key($r));
    $sql = $dbc->query("SELECT * FROM quotes WHERE id='$pos'");
    $sql2 = $sql->fetchArray();

    if($sql2["quote"]=="" && $pos!=0 && $pos!=""){ problem(); } 
    else {
      if($sql2["approved"]=="0"){
        echo("
          <div width=80% align=center>
          <font size='-1'>$lang[vote_comment_1]</font></div>");
      }
      $result = $dbc->query("SELECT * FROM votes WHERE ip='$ip' and qid='$pos' ORDER BY vid");
      
      while ( $row = $result->fetchArray()) {
        if($row["qid"]==$pos){ $voted="yes"; }
      }
    }

    if(strcmp($voted, "yes") != 0){
      if($r["$pos"]==-1){
        $newscore = $sql2["score"]--;
        $sql = $dbc->query("UPDATE quotes SET score = '$newscore' WHERE id ='$pos'");
        voteUpdated("downgraded", $pos);
        $updateAlreadyVoted=yes;
      } else if($r["$pos"]==1){
        $newscore = $sql2["score"]++;
        $sql = $dbc->query("UPDATE quotes SET score = '$newscore' WHERE id ='$pos'");
        voteUpdated("upgraded", $pos);
        $updateAlreadyVoted="yes";
      } else {
        $updateAlreadyVoted="no";
      }

      if($updateAlreadyVoted=="yes"){
        $get = $dbc->query("SELECT * FROM votes ORDER BY vid DESC LIMIT 1");
        
        while ($count = $get->fetchArray()) { 
          $tempid = $count["vid"];
        }
        
        $newvid = $tempid++;
        $sql = $dbc->query("INSERT INTO votes (vid, ip, qid) values
        ('$newvid', '$ip', '$pos')");
      }
    } else {
        if($pos!=0 && $pos!=""){
          if($r["$pos"]!=0){ votedAlready($pos); }
        }
      }

    $tmp++;
    unset($voted);
    next($r);
  }
  echo("<br /><center>$lang[back_link_1]</center>");
 }
}


/**
 * Informing the user that voting twice is not permitted.
 * 
 * Parameters: $id - ID of quote they are trying to mod.
 */
function votedAlready($id){
global $dbc;
global $lang;
echo("<center><font size='-1'>
	$lang[update_vote_4] <a href='./?qnum=$id'>#$id</a>.
      <br /></font> </center>");
}


/**
 * Informing the user quote has been updated.
 *
 * Parameters: $type - Whether it is up or downgraded.
 *             $id - ID of quote being modified.
 */
function voteUpdated($type, $id){
global $lang;
  echo("<center><font size='-1'>");
    if($type=="downgraded"){
	echo("$lang[update_vote_1] <a href='./?qnum=$id'>#$id</a> $lang[update_vote_2]<br />");
    } else if($type=="upgraded"){
	echo("$lang[update_vote_1] <a href='./?qnum=$id'>#$id</a> $lang[update_vote_3]<br />");
    } else if($type=="sux"){
	echo("$lang[update_vote_5]<br />");
    }
  echo("</font></center>");
}

?>
