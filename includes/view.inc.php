<?php
/***************************************************************************
 *   OSQDB, includes/view.inc.php
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
 * Quote browsing.
 * 
 * Parameters: $page - Current page of quotes to browse.
 */
function browse($page){
global $dbc;
global $lang;
$approved = 0;
$page = escape_str($page);
  echo("<div width=80% align=center>");

  $perPage=10;

  $get = $dbc->query("SELECT * FROM quotes WHERE approved='1'");
  /* Find out how many approved quotes there are */
  while ($count = $get->fetchArray()) { 
    $approved++;
  }
  if($approved==0){
    echo("$lang[error_comment_9]");
  } else {
  
  echo("<font size='-1'>$lang[browse_comment_1]<br>");

  $numofpages = $approved/$perPage;
  $numofpages = ceil($numofpages);
  
  if(is_null($page) || $page>$numofpages){$page=1;}
  
  if($page>1){
    echo("<a href='./?browse&page=1'><</a>&nbsp;");
  }

  //left side first
  $tmp=3;
  while($tmp!=0){
   $tmp2 = $page - $tmp;
    if($tmp2>0 && $tmp2!=$page){ echo("<a href='./?browse&page=$tmp2'>$tmp2</a>&nbsp;"); }
    $tmp--;
  }

  echo("$page&nbsp;");

  //now the right side
  $tmp=0;
  while($tmp!=4){
   $tmp2 = $page + $tmp;
    if($tmp2<=$numofpages && $tmp2!=$page){ echo("<a href='./?browse&page=$tmp2'>$tmp2</a>&nbsp;"); }
    $tmp++;
  }
  if($page!=$numofpages){
    echo("<a href='./?browse&page=$numofpages'>></a>&nbsp;");
  }

  echo("<br></div>");

  /* Onto the actual output */
  /* Set the vars for output */
  $lowerpage=$page*$perPage-$perPage;
  /* Open our Voting form open */
  outputOpen();
  $place=0;
  
  $result = $dbc->query("SELECT * FROM quotes WHERE approved='1' ORDER BY id ASC LIMIT $lowerpage,$perPage");
  while ( $row = $result->fetchArray()) {
    $id = $row["id"];
    $quote = $row["quote"];
    $vote = $row["score"];
    $comment = $row["comment"];
    $place++;
    output($id, $quote, $vote, $comment, $place);
  }
  outputClose();
  }
}

/**
 * Displays the latest approved quotes.
 */
function latest(){
global $dbc;
global $lang;
$approved = 0;
  $get = $dbc->query("SELECT * FROM quotes WHERE approved='1'");
  /* Find out how many approved quotes there are */
  while ($count = $get->fetchArray()) { 
    $approved++;
  }
  if($approved==0){
    echo("<div width=80% align=center>");
    echo("$lang[error_comment_9]");
    echo("</div>");
  } else {
  outputOpen();
  $place=0;
  $result = $dbc->query("SELECT * FROM quotes WHERE approved='1' ORDER BY id DESC LIMIT 10");
  
  while ( $row = $result->fetchArray()) {
    $id = $row["id"];
    $quote = $row["quote"];
    $vote = $row["score"];
    $comment = $row["comment"];
    $place++;
    output($id, $quote, $vote, $comment, $place);
  }
  outputClose();
}
}

/**
 * Simply opens form for voting.
 */
function outputOpen(){
  echo("<form action='./?vote' method='POST' name='voting'>");
}


/**
 * Simply ends form and provides submit button
 */
function outputClose(){
global $lang;
  echo("<center><input type='submit' value='$lang[submit_button_1]' class='button'>
		<input type='reset' value='$lang[reset_button_1]' class='button'>
	</center></form>");
}


/**
 * Outputs quote detail on single page.
 * 
 * Parameters: $id - ID of quote.
 *             $quote - Quote text.
 *             $vote - The amount of votes quote has received.
 *             $comment - Submitter-defined comment.
 *             $place
 */
function output($id, $quote, $vote, $comment, $place){
global $lang;
  echo("<blockquote><a href='./?qnum=$id' title='$lang[output_comment_2]'><b>#$id</b></a>\n");

  echo(" ($vote) <a href='./?sux=$id'>[X]</a><font size=-1><br />$quote<br />\n");
  
	if($comment!=""){ echo("<i>$lang[output_comment_1] </i>$comment<br>\n");	}
  
	echo(" -<input type='radio' name='r[$id]' tabindex='2' value='-1'>\n
		<input type='radio' name='r[$id]' tabindex='2' value='0' checked>\n
		<input type='radio' name='r[$id]' tabindex='2' value='1'>+\n");
	echo("</font></blockquote>\n");
}

/**
 * Outputs quote detail on single page.
 * 
 * Parameters: $id - ID of quote.
 *             $quote - Quote text.
 *             $vote - The amount of votes quote has received.
 *             $comment - Submitter-defined comment.
 *             $place
 */
function output_queue($id, $quote, $vote, $comment, $place){
global $lang;
  echo("<blockquote><a href='./?qnum=$id' title='$lang[output_comment_2]'><b>#$id</b></a>");

  echo(" ($vote) <a href='./?sux=$id'>[X]</a><font size=-1><br />$quote<br />");
  
	if($comment!=""){ echo("<i>$lang[output_comment_1] </i>$comment<br>");	}
  
	echo("</font></blockquote>");
}

/**
 * Displays quotes currently in submission queue.
 */
function queue($page){
global $dbc;
global $lang;
$approved = 0;
$page = escape_str($page);
  echo("<div width=80% align=center>");

  $perPage=10;

  $get = $dbc->query("SELECT * FROM quotes WHERE approved='0'");
  /* Find out how many approved quotes there are */
  while ($count = $get->fetchArray()) { 
    $approved++;
  }
  if($approved==0){
    echo("$lang[error_comment_10]");
  } else {
  
  echo("<font size='-1'>$lang[browse_comment_1]<br>");

  $numofpages = $approved/$perPage;
  $numofpages = ceil($numofpages);
  
  if(is_null($page) || $page>$numofpages){$page=1;}
  
  if($page>1){
    echo("<a href='./?queue&page=1'><</a>&nbsp;");
  }

  //left side first
  $tmp=3;
  while($tmp!=0){
   $tmp2 = $page - $tmp;
    if($tmp2>0 && $tmp2!=$page){ echo("<a href='./?queue&page=$tmp2'>$tmp2</a>&nbsp;"); }
    $tmp--;
  }

  echo("$page&nbsp;");

  //now the right side
  $tmp=0;
  while($tmp!=4){
   $tmp2 = $page + $tmp;
    if($tmp2<=$numofpages && $tmp2!=$page){ echo("<a href='./?queue&page=$tmp2'>$tmp2</a>&nbsp;"); }
    $tmp++;
  }
  if($page!=$numofpages){
    echo("<a href='./?queue&page=$numofpages'>></a>&nbsp;");
  }

  echo("<br></div>");

  /* Onto the actual output */
  /* Set the vars for output */
  $lowerpage=$page*$perPage-$perPage;
  /* Open our Voting form open */
  outputOpen();
  $place=0;
  
  echo("</div>");

  $result = $dbc->query("SELECT * FROM quotes WHERE approved='0' ORDER BY id ASC LIMIT $lowerpage,$perPage");
  while ( $row = $result->fetchArray()) {
    $id = $row["id"];
    $quote = $row["quote"];
    $vote = $row["score"];
    $comment = $row["comment"];
    $place++;
    output_queue($id, $quote, $vote, $comment, $place);
  }
  }
}


/**
 * Returns a page of random quotes.
 *
 * Parameters: $type - Whether quotes should have score greater than 0 or not.
 */
function random($type){
global $dbc;
global $lang;
$approved = 0;
  $get = $dbc->query("SELECT * FROM quotes WHERE approved='1'");
  /* Find out how many approved quotes there are */
  while ($count = $get->fetchArray()) { 
    $approved++;
  }
  if($approved==0){
    echo("<div width=80% align=center>");
    echo("$lang[error_comment_9]");
    echo("</div>");
  } else {
  $approved=0;
  outputOpen();
  $place=0;
  $rnd = 0;

  if($type == "1"){
    $get = $dbc->query("SELECT * FROM quotes WHERE approved='1' && score>'0'");
  } else {
    $get = $dbc->query("SELECT * FROM quotes WHERE approved='1'");
  }
  
  while ($count = $get->fetchArray()) { 
    $approved++;
    $overallcount=$count["id"];
  }

  if($approved>10){
    $approved=10;
  }

  $loop=0;
  $used=array();

  while($loop!=$approved){
    $rnd += rand(2^2,2^20);
    mt_srand(microtime() + $rnd--);
    $tempid=mt_rand( 1, $overallcount );
    $sql = $dbc->query("SELECT * FROM quotes WHERE id='$tempid'");
    $sql2 = $sql->fetchArray();

  if($type == "1"){
    if($sql2["approved"]==1 && score>'0'){
      if (in_array($tempid, $used)) {
        $usedalready=1;
      } else {
        $used[$loop]=$tempid;      

        $id=$sql2["id"];
        $quote=$sql2["quote"];
        $vote=$sql2["score"];
        $comment = $sql2["comment"];
  
        output($id, $quote, $vote, $comment);

        $loop++;
        $usedalready=0;
      }
    }
  }else{
    if($sql2["approved"]==1){
      if (in_array($tempid, $used)) {
        $usedalready=1;
      } else {
        $used[$loop]=$tempid;      

        $id=$sql2["id"];
        $quote=$sql2["quote"];
        $vote=$sql2["score"];
        $comment = $sql2["comment"];
        $place++;
        output($id, $quote, $vote, $comment, $place);

        $loop++;
        $usedalready=0;
      }
    }
   }
  }
  outputClose();
}
}

function stats(){
global $dbc;
global $lang;

 $approved=0;
 $pending=0;
 $sux=0;

  $get = $dbc->query("SELECT * FROM quotes WHERE approved='1'");
  while ($count = $get->fetchArray()) { 
	$approved++;
  }

  $get2 = $dbc->query("SELECT * FROM quotes WHERE approved='0'");
  while ($count2 = $get2->fetchArray()) { 
	$pending++;
  }

  echo("$approved $lang[stats_comment_1], $pending $lang[stats_comment_2] &nbsp;");
disconnect(); /* close the db gracefully when done */
}

/**
 * Displays list of the highest ranked quotes.
 * 
 * Parameters: $type - Defines whether to list top 25 or top 50.
 */
function top($type){
global $dbc;
global $lang;
$approved = 0;
  $get = $dbc->query("SELECT * FROM quotes WHERE approved='1'");
  /* Find out how many approved quotes there are */
  while ($count = $get->fetchArray()) { 
    $approved++;
  }
  if(($approved==0) || ($type==1 && $approved<26)){
    echo("<div width=80% align=center>");
    echo("$lang[error_comment_10]");
    echo("</div>");
  } else {
  outputOpen();
  $place=0;
  
  if($type=="1"){
    $result = $dbc->query("SELECT * FROM quotes WHERE approved='1' ORDER BY score DESC LIMIT 25,50");
  } else {
    $result = $dbc->query("SELECT * FROM quotes WHERE approved='1' ORDER BY score DESC LIMIT 0,25");
  }
  
  while ( $row = $result->fetchArray()) {
    $id=$row["id"];
    $quote=$row["quote"];
    $vote=$row["score"];
    $comment = $row["comment"];
  
    $place++;
    output($id, $quote, $vote, $comment, $place);
  }
    outputClose();
 }
}

/**
 * View individual quote.
 *
 * Parameters: $quote - Desired quote.
 */
function view($quote){
global $dbc;
global $lang;
	if($quote=="" || !isset($quote)){ problem(); }
$quote = escape_str($quote);

  /* Just to filter out any sql injections */
  $quote = str_replace("'", "", $quote);
  $quote = str_replace("\"", "", $quote);
  $quote = str_replace(")", "", $quote);

  if(!is_numeric($quote)){ problem(); } 
  else {
    $sql = $dbc->query("SELECT * FROM quotes WHERE id='$quote'");
    $sql2 = $sql->fetchArray();
    
    if($sql2["quote"]==""){
      echo("<div width=80% align=center><font size='-1'>$lang[error_comment_3]<br /><br />$lang[back_link_1]</font></div>");
    } else if($sql2["approved"]!="1"){
      echo("<div width=80% align=center><font size='-1'>$lang[vote_comment_1]</font></div>");
      $id=$sql2["id"];
      $quote=$sql2["quote"];
      $vote=$sql2["score"];
      $comment=$sql2["comment"];
      outputOpen();
      $place=1;
      output($id, $quote, $vote, $comment, $place);
      outputClose();
    } else {
      $id=$sql2["id"];
      $quote=$sql2["quote"];
      $vote=$sql2["score"];
      $comment = $sql2["comment"];
      outputOpen();
      $place=1;
      output($id, $quote, $vote, $comment, $place);
      outputClose();  
    }
  }
}

/**
 * Display list of worst quotes.
 */
function worst(){
global $dbc;
global $lang;
$approved = 0;
  $get = $dbc->query("SELECT * FROM quotes WHERE approved='1'");
  /* Find out how many approved quotes there are */
  while ($count = $get->fetchArray()) { 
    $approved++;
  }
  if($approved==0){
    echo("<div width=80% align=center>");
    echo("$lang[error_comment_9]");
    echo("</div>");
  } else {
  $result = $dbc->query("SELECT * FROM quotes WHERE approved='1' ORDER BY score ASC LIMIT 0,25");
  outputOpen();
  $place=0;
  while ( $row = $result->fetchArray()) {
    $id=$row["id"];
    $quote=$row["quote"];
    $vote=$row["score"];
    $comment = $row["comment"];
    $place++;
    output($id, $quote, $vote, $comment, $place);
  }
  outputClose();  
}
}

?>
