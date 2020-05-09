// This is where the compressor will load all components, include all components used on the page here
tinyMCE_GZ.init({
	plugins : 'advhr,advimage,advlink,iespell,media,print,contextmenu,paste,nonbreaking',
	themes : 'advanced',
	languages : 'en',
	disk_cache : true,
	debug : false
});

// Normal initialization of TinyMCE
tinyMCE.init({
	// General options
	mode : "textareas",
	theme : "advanced",
	plugins : "advhr,advimage,advlink,emotions,iespell,media,contextmenu,nonbreaking",

	// Theme options
	theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,cut,copy,paste,|,fontsizeselect,fontselect",
	theme_advanced_buttons2 : "bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,image,cleanup,|,forecolor,backcolor,|,hr,removeformat,|,iespell,media,advhr",
	theme_advanced_buttons3 : "",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "none",
	theme_advanced_resizing : false,

	// Example content CSS (should be your site CSS)
	content_css : "css/example.css",

	// Drop lists for link/image/media/template dialogs
	template_external_list_url : "lists/template_list.js",
	external_link_list_url : "lists/link_list.js",
	external_image_list_url : "lists/image_list.js",
	media_external_list_url : "lists/media_list.js",

	// Replace values for the template plugin
	template_replace_values : {
		username : "Some User",
		staffid : "991234"
	}
});