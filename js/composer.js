function reloadEntryList(){
$.post("composer.php", { searchText: $("#searchText").val(), action : "loadEntryList" },
 function(data){
 
	var strTmp = '';
	$.each(data,function(i,item){
		strTmp += "<a onclick='loadIntoEditor("+item.id+")'>["+item.type+"]"+item.title+"</a><br />";
		strTmp += "<span style='color:#CCC'>"+item.datetime+"</span>";
		strTmp += "<hr />";
	});
   $("#entryResults").html(strTmp);
 }, "json");
}
function loadIntoEditor(tid){
$.post("composer.php", { id: tid, action : "loadText" },
 function(data){
 
	var strTmp = '';
	$.each(data,function(i,item){
		strTmp += "<a onclick='loadIntoEditor("+item.id+")'>["+item.type+"]"+item.title+"</a><br />";
		strTmp += "<span style='color:#CCC'>"+item.datetime+"</span>";
		strTmp += "<hr />";
	});
   $("#entryResults").html(strTmp);
 }, "json");
}
$(document).ready(function(){
	reloadEntryList();
});