<?php
/* Stop include file being called directly */
$tmp=$_SERVER['SCRIPT_FILENAME'];
if (!preg_match("/\bindex.php\b/i", "$tmp") && !preg_match("/\bupgrade.php\b/i", "$tmp")) {
die("Hacking Attempt");
}

$lang['template_name'] = "bash_org"; // The name of the template
$lang['head_stylesheet'] = "bash.css"; // The stylesheet to call for the template
$lang['page_title'] = "OSQDB: Open Source Quote Database Home"; // The <title> tags
$lang['date_layout'] = "d/m/y (H:i)"; 
// d = day, m = month, y = year, H = hour, i=min (futher reference at http://uk.php.net/date)

$lang['page_bgcolor'] = "bgcolor=#ffffff";
$lang['page_text'] = "text=#000000";
$lang['page_link'] = "link=#c08000";
$lang['page_alink'] = "alink=#c08000";
$lang['page_vlink'] = "vlink=#c08000";
$lang['page_bottommargin'] = "bottommargin=0";
$lang['page_topmargin'] = "topmargin=0";
$lang['page_leftmargin'] = "leftmargin=0";
$lang['page_rightmargin'] = "rightmargin=0";
$lang['page_marginheight'] = "marginheight=0";
$lang['page_marginwidth'] = "marginwidth=0";
$lang['page_logo'] = "images/logo.png";

$lang['add_button_1'] = "Add Quote to Database"; // ./?add
$lang['add_button_2'] = "Clear Form"; // ./?add
$lang['add_comment_1'] = "Please <u>remove timestamps</u> unless they are part of the quote's humor value."; // ./?add
$lang['add_comment_2'] = "Comment:"; // ./?add
$lang['add_comment_3'] = "<b>Privacy Note:</b> Your IP address is never displayed publicly."; // ./?add
$lang['add_comment_4'] = "Your quote has been added and is awaiting approval. <br> The permanent id is"; // ./?add (after quote is submitted)

$lang['admin_accept_1'] = "The quote has been accepted as"; // ./?admin (after accepting awaiting quote)
$lang['admin_addnews_comment_1'] = "Author"; // ./?admin (add news)
$lang['admin_addnews_comment_2'] = "Post"; // ./?admin (add news)
$lang['admin_author_1'] = "Author"; // ./?admin (edit news)
$lang['admin_ban_comment_1'] = "Reason:";  // ./?admin (Un/Ban system)
$lang['admin_ban_comment_2'] = "Unban"; // ./?admin (Un/Ban system)
$lang['admin_ban_comment_3'] = "No IP matching in ban list"; // ./?admin (Un/Ban system)
$lang['admin_del_1'] = "Delete"; //./?admin (for user delete)
$lang['admin_close_1'] = "Close Board."; //./?admin (close boards)
$lang['admin_close_2'] = "Close Submissions."; //./?admin (close submissions)
$lang['admin_close_3'] = "Reason."; //./?admin (close boards)
$lang['admin_edit_comment_1'] = "edit"; // ./?admin (general edit button)
$lang['admin_editnews_comment_1'] = "Posted by";  // ./?admin (edit news)
$lang['admin_edituser_comment_1'] = "New Password"; // ./?admin (edit user)
$lang['admin_edituser_comment_2'] = "<i>Tick to change password, otherwise please leave unticked</i>"; // ./?admin (edit user)
$lang['admin_hide_comment_1'] = "hide"; // ./?admin (edit news)
$lang['admin_hide_comment_2'] = "unhide"; // ./?admin (edit news)
$lang['admin_links_1'] = "Accept/Reject Quotes"; // ./?admin (accept/reject)
$lang['admin_links_2'] = "Add news";  // ./?admin (admin links)
$lang['admin_links_3'] = "Edit News"; // ./?admin (admin links)
$lang['admin_links_4'] = "Edit Main"; // ./?admin (admin links)
//5&6 edit mod/man removed
$lang['admin_links_7'] = "Add User"; // ./?admin (admin links)
$lang['admin_links_8'] = "Edit/Delete User"; // ./?admin (admin links)
$lang['admin_links_9'] = "Logout"; // ./?admin (admin links)
$lang['admin_links_10'] = "Ban IP"; // ./?admin (admin links)
$lang['admin_links_11'] = "UnBan IP"; // ./?admin (admin links)
$lang['admin_links_12'] = "List Banned IP's"; // ./?admin (admin links)
$lang['admin_links_13'] = "Review Sux"; // ./?admin (admin links)
$lang['admin_links_14'] = "Add Template"; // ./?admin (admin links)
$lang['admin_links_15'] = "Use/Delete Template"; // ./?admin (admin links)
$lang['admin_links_16'] = "Close Board / Submissions"; // ./?admin (admin links)
$lang['admin_links_17'] = "Change User Password"; // ./?admin (admin links)
$lang['admin_login_1'] = "Login"; // ./?admin (main admin login)
$lang['admin_logout_comment_1'] = "You are now logged out."; // ./?admin (logout screen)
$lang['admin_logout_error_1'] = "You are not logged in."; // ./?admin (logout screen, if you aren't logged in while attempting to log out)
$lang['admin_main_comment_1'] = "Accept"; // ./?admin (accept/reject)
$lang['admin_main_comment_2'] = "Reject"; // ./?admin (accept/reject)
$lang['admin_main_comment_3'] = "It's Ok"; // ./?admin (accept/reject)
$lang['admin_main_comment_4'] = "Trash"; // ./?admin (accept/reject)
$lang['admin_news_1'] = "News"; // ./?admin (various news add/edit/etc)
$lang['admin_password_comment_1'] = "Password"; // ./?admin (add user)
$lang['admin_reject_1'] = "The quote has been rejected as"; // ./?admin (accept/reject)
$lang['admin_update_comment_1'] = "Updated"; // ./?admin (general update managers/mods/news/users etc.)
$lang['admin_username_comment_1'] = "Username"; // ./?admin (add/edit user)

$lang['back_link_1'] = "<a href='javascript:history.back()'>Back</a>"; // general back statement
$lang['back_link_2'] = "<a href='./?admin'>Back to Admin Panel</a>"; // ./?admin updated comment

$lang['browse_comment_1'] = "Page:"; // ./?browse

$lang['error_comment_1'] = "There has been an error connecting to the database, please try again later."; // general error
$lang['error_comment_2'] = "Sorry, there was a problem with this request."; // general error
$lang['error_comment_3'] = "Quote could not be located in our quotes database."; // general error
$lang['error_comment_4'] = "This quote has not yet been approved."; // general error
$lang['error_comment_5'] = "Quote already exists in the database."; // general error
$lang['error_comment_6'] = "You cannot delete the Admin account."; // general error
$lang['error_comment_7'] = "<center><i>Access Denied</i></center>."; // if a MOD tries to access MAN pages
$lang['error_comment_8'] = "You cannot edit the admin username or status, they have been reset to default.<br />";
$lang['error_comment_9'] = "No quotes currently in database.";
$lang['error_comment_10'] = "No quotes currently in database for this category.";

$lang['manager_comment_1'] = "Manager"; // ./?admin (add/edit user)
$lang['moderator_comment_1'] = "Moderator"; // ./?admin (add/edit user)

$lang['output_comment_1'] = "Comment:"; // general output 
$lang['output_comment_2'] = "Permanent link to this quote."; // general output (within link title)

$lang['reset_button_1'] = "Reset Form"; // general form button

$lang['search_comment_1'] = "Type in the search word,<br />apply pressure to button,<br />then read the results."; // ./?search
$lang['search_comment_2'] = "Sort by:"; // ./?search
$lang['search_comment_3'] = "Show:"; // ./?search - within the dropdown
$lang['search_comment_4'] = "Score"; // ./?search - within the dropdown
$lang['search_comment_5'] = "Number"; // ./?search - within the dropdown
$lang['search_comment_6'] = "No results found with search query."; // ./?search

$lang['stats_comment_1'] = "quotes approved";
$lang['stats_comment_2'] = "quotes pending";

$lang['submit_button_1'] = "Submit"; // general form button
$lang['submit_button_2'] = "Search"; // ./?search general form button

$lang['template_comment_1'] = "Template Name";
$lang['template_comment_2'] = "<i>e.g osqdb_orginal, this must be stored in ./templates/<b>TEMPLATE NAME</b></i>";
$lang['template_comment_3'] = "Use";
$lang['template_comment_4'] = "Delete";
$lang['template_comment_5'] = "In Use";
$lang['template_error_1'] = "You cannot delete the template in use.";
$lang['template_error_2'] = "The following template doesn't seem to be working correctly, please re-upload.";
$lang['template_error_3'] = "You cannot delete the orginal template as this may be needed for backup purposes";

$lang['update_vote_1'] = "Done! Quote"; // after vote "Done! Quote #123 has been up/down(graded)"
$lang['update_vote_2'] = "has been downgraded."; // after vote "Done! Quote #123 has been up/down(graded)"
$lang['update_vote_3'] = "has been upgraded."; // after vote "Done! Quote #123 has been up/down(graded)"
$lang['update_vote_4'] = "You have already voted on"; // error report if quote has already been voted on
$lang['update_vote_5'] = "Quote has been marked as sux and will be reviewed."; // mark quote as sux

$lang['user_links_1'] = "Home"; //main links
$lang['user_links_2'] = "Latest"; //main links
$lang['user_links_3'] = "Browse"; //main links
$lang['user_links_4'] = "Random"; //main links
$lang['user_links_5'] = "Queue "; //main links
$lang['user_links_6'] = "Worst"; //main links
$lang['user_links_7'] = "Top 25"; //main links
$lang['user_links_8'] = "-50"; //main links
$lang['user_links_9'] = "<b>Add Quote</b>"; //main links
$lang['user_links_10'] = "Search"; //main links
$lang['user_links_11'] = "&npsb;"; //main links

$lang['vote_comment_1'] = "This quote has not yet been approved (However you may still vote on it)"; // for voting on items in queue

?>
