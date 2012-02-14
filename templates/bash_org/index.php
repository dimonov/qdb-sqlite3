<?php
/* Stop include file being called directly */
$tmp=$_SERVER['SCRIPT_FILENAME'];
if (!preg_match("/\bindex.php\b/i", "$tmp") && !preg_match("/\bupgrade.php\b/i", "$tmp")) {
die("Hacking Attempt");
}

include("lang_eng.php");

/**
 * Output site copyright information (Creative Commons GNU GPL tag)
 */
function copyright(){
  
  echo("
    <center>
      <!-- Creative Commons License -->
      <a href='http://www.sourceforge.net/projects/osqdb' target=_blank class=cr>OSQDB</a> is licensed under the <a href='http://creativecommons.org/licenses/GPL/2.0/' class=cr>CC-GNU GPL</a>.
      <!-- /Creative Commons License -->
      
      <!--
        <rdf:RDF xmlns=\"http://web.resource.org/cc/\"
          xmlns:dc=\"http://purl.org/dc/elements/1.1/\"
          xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\">
          <Work rdf:about=\"\">
            <license rdf:resource=\"http://creativecommons.org/licenses/GPL/2.0/\" />
            <dc:type rdf:resource=\"http://purl.org/dc/dcmitype/Software\" />
          </Work>
          
          <License rdf:about=\"http://creativecommons.org/licenses/GPL/2.0/\">
            <permits rdf:resource=\"http://web.resource.org/cc/Reproduction\" />
            <permits rdf:resource=\"http://web.resource.org/cc/Distribution\" />
            <requires rdf:resource=\"http://web.resource.org/cc/Notice\" />
            <permits rdf:resource=\"http://web.resource.org/cc/DerivativeWorks\" />
            <requires rdf:resource=\"http://web.resource.org/cc/ShareAlike\" />
            <requires rdf:resource=\"http://web.resource.org/cc/SourceCode\" />
          </License>
        </rdf:RDF>
      -->
    </center>");
}

function htmltop(){
global $lang;
echo("
<html>
<head>
	<title>QDB+sqlite3: Quote Database Home</title>
	<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
	<style type='text/css'>
	<!--
		@import url('./templates/$lang[template_name]/$lang[head_stylesheet]');
	-->
	</style>
</head>

<body $lang[page_bgcolor] $lang[page_text] $lang[page_link] $lang[page_alink] $lang[page_vlink]>

<center>

<table cellpadding='2' cellspacing='0' width='80%' border='0'>
	<tr>
		<td bgcolor='#c08000' align='left'>
			<font size='+1'><b><i>QDB</i></b></font>
		<a href='./?admin'><font size='-1' color='#c08000'>Admin</font></a></td>
		
		<td bgcolor='#c08000' align='right'>
			<font face='arial' size='+1'><b>Quote Database Home</b></font><br></td>
	</tr></table>

	<table cellpadding='2' cellspacing='0' width='80%' border='0'>
	<tr>
		<td align='right' bgcolor=#f0f0f0 height=15 colspan=2>
 			<a href='./'>Home</a>
			/ <a href='./?latest'>Latest</a>
			/ <a href='./?browse'>Browse</a>
 			/ <a href='./?random'>Random</a>
 			/ <a href='./?queue'>Queue</a> 
			/ <a href='./?worst'>Worst</a>
 			/ <a href='./?top'>Top 25</a><a href='./?top2'>-50</a> 
			/ <a href='./?add'><b>Add Quote</b></a>
 			/ <a href='./?search'>Search</a> &nbsp;
		</td>

	</tr>
</table>
</center><p>
<table width=80% align=center><tr><td>");
}

function htmlbottom(){
echo("</td></tr></table>
   <center>
   <table cellpadding='0' cellspacing='0' width='80%' border='0'>
	<tr><td align='left' bgcolor=#f0f0f0 height=15 colspan=2>
 			<a href='./'>Home</a>
			/ <a href='./?latest'>Latest</a>
			/ <a href='./?browse'>Browse</a>
 			/ <a href='./?random'>Random</a>
 			/ <a href='./?queue'>Queue</a> 
			/ <a href='./?worst'>Worst</a>
 			/ <a href='./?top'>Top 25</a><a href='./?top2'>-50</a> 
			/ <a href='./?add'><b>Add Quote</b></a>
 			/ <a href='./?search'>Search</a> &nbsp;
		</td>
	</tr><tr><td bgcolor='#c08000' align='left' width=50%>"); copyright(); echo("</td>
	<td bgcolor='#c08000' align='right'>"); stats(); echo("</td></tr>
	</table></center><br />");
}
