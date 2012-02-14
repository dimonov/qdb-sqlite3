<?php
/***************************************************************************
 *   OSQDB, includes/search.inc.php
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
 * HTML Form for the search query 
 */
function searchForm(){
global $lang;
  echo("
  <br />
  <div width=80% align=center>
    <form action='./?searchq'>
      <table cellpadding='2' cellspacing='0'>
        <tr>
          <td><input type='text' name='searchq' size='30' class='text' /></td>
          <td><input type='submit' value='$lang[submit_button_2]' class='button' /></td>
        </tr>
        <tr>
          <td>$lang[search_comment_1]</td>
          <td>$lang[search_comment_2]<br />
            <select name='sort' class='select'>
              <option value='0' selected>$lang[search_comment_4]</option>
              <option value='1'>$lang[search_comment_5]</option>
            </select>
          </td>
          <td>Show:<br />
            <select name='show'>
              <option value='10'>10</option>
              <option value='25' selected>25</option>
              <option value='50'>50</option>
              <option value='75'>75</option>
              <option value='100'>100</option>
            </select>
          </td>
        </tr>
      </table>
    </form>
  </div>");
}

/**
 * SQL Output for the search query passed through ./?searchq 
 * 
 * Parameters: $sort - How the results are sorted.
 *             $show - How many results to show.
 *             $searchq - The search query.
 */
function searchResults($sort, $show, $searchq){
global $dbc;
global $lang;
$approved = 0;
$place=0;
$searchq = escape_str($searchq);
  if($sort=='0'){ $sort="score"; }
  else if($sort=='1'){ $sort="id"; } 
  else { $sort="score"; }
    
  if($show != '10' && $show != '25' && $show !='50' && $show !='75' && $show !='100'){ $show=25; }

  $get = $dbc->query("SELECT * FROM quotes WHERE quote like
  '%$searchq%' and approved='1'");
  /* Find out how many approved quotes there are */
  while ($count = $get->fetchArray()) { 
    $approved++;
  }
  if($approved==0){
    echo("<div width=80% align=center>");
    echo("$lang[search_comment_6]");
  } else {

  $result = $dbc->query("SELECT * FROM quotes WHERE quote like
  '%$searchq%' and approved='1' ORDER BY $sort DESC LIMIT $show");

  outputOpen();

  while ( $row = $result->fetchArray()) {
    $id=$row["id"];
    $quote=$row["quote"];
    $vote=$row["score"];
    $comment=$row["comment"];
    $place++;
    output($id, $quote, $vote, $comment, $place);
  }
  
  outputClose();
}
}


?>
