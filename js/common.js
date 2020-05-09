function dialog(url, title, iframe,width){
	var tempContent;
	if(iframe==true){
		tempContent = '<iframe style="border:none;height:600px;width:100%" src="'+url+'"></iframe>';
		$('#modelDialog').find(".modal-body").html(tempContent);
		}else{
		$('#modelDialog').find(".modal-body").load(url);
	}
	$('#modelDialog').modal('show');
	$('#modelDialog').find(".modal-title").html(title);
	if(width>1){
		//$('#modelDialog').css("width", width );
	}
}

function checkNoti(){
	$.get('/ajaxdata.php',{type: 'notify'},
	function(data){
		$('#notify').html(data);
		if(data != '0'){
			document.title = docTitle + '(' + data + ')';
			}else{
			document.title = docTitle;
		}
	});
}
function findBootstrapEnvironment() {
    var envs = ['xs', 'sm', 'md', 'lg'];

    var $el = $('<div>');
    $el.appendTo($('body'));

    for (var i = envs.length - 1; i >= 0; i--) {
        var env = envs[i];

        $el.addClass('hidden-'+env);
        if ($el.is(':hidden')) {
            $el.remove();
            return env;
        }
    }
}