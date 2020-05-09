<?php header("content-type:text/javascript");?>
document.writeln("<form method=\"get\" action=\"/searchresults.php\" onSubmit=\"if(this.searchtext.value.length<2){alert(\'搜索內容過短\');return false;}\">");
<?php if (isset($_GET['ownerid'])) { ?>
document.writeln("<input name=\"ownerid\" type=\"hidden\" value=\"<?php echo $_GET['ownerid']; ?>\" \/>");
<?php } ?>
document.writeln("<input name=\"searchtext\" type=\"text\" size=\"20\" maxlength=\"100\" id=\"sb_text\" \/><input type=\"submit\" name=\"Submit\" id=\"sb_submit\" value=\"搜尋\" \/>");
document.writeln("<\/form>");