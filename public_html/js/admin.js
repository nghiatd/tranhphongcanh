MINLENGTH_AUTOCOMPLETE = 3;
$(document).ready(function() {	
	tinyMCE.init( {
		// General options
		mode : "textareas",
		theme : "advanced",
		skin : "o2k7",
		plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : false,
		// Example content CSS (should be your site CSS)
		content_css :"http://www.vietinfo.eu/css/stylesheet.css",
		file_browser_callback : "tinyBrowser",
		convert_urls : false,
		editor_selector : "mceEditor",
		editor_deselector : "mceNoEditor"
	});
	$.ajaxSetup( { type: "POST" } );
	$('.float-right').ajaxStart(function(){
		$(this).show();
	}).ajaxStop(function(){
		$(this).hide();
	});
	$.each($('div').find('button'), function() {
		$('button').button();
	});
	$.each($('div').find('input[type=button]'), function() {
		$('input[type=button]').button();
	});
	
});
var dialogSelectCategory = (function(obj,selector,inner){

	$(obj).dialog( {
		autoOpen : false,
		resizable : false,
		height : 150,
		width : 350,
		modal : true,
		buttons : {
			"Thêm mục" : function() {
				$('#category_counter').val(parseInt($('#category_counter').val())+1);
				ajaxGetCategory($(selector).val(),inner,$('#category_counter').val());
			},
			"Hủy bỏ" : function() {
				$(this).dialog('close');
			}
		}
	});
});

var initSelectPositionDialog = (function(obj){
	$(obj).dialog( {
		autoOpen : false,
		resizable : false,
		height : 600,
		width : 600,
		modal : true,
		buttons : {
			"Hoàn tất" : function() {
				$('#img_selected').hide();
				$('#set_position').submit();
			},
			"Hủy bỏ" : function() {
				$('#img_selected').hide();
				$(this).dialog('close');
			}
		}
	});
});
var dialogSelectPosition = (function(dialog,location,id,cor) {
	$('#'+dialog).dialog('open');
	$('#img_selected').show();
	$('#id').val(id);
	var width = 575;	
	var offset = $('#img_src').offset();
	$('#img_src').append('<img src="'+IMAGE_LOCATION+'location/'+location+'.jpg" width="'+width+'"/>');
	$("#img_src").click(function(e){
		var height = $("#img_src > img").attr('height');	    
	     var x = e.clientX - offset.left;
	     var y = e.clientY - offset.top;
	    $('#x_pos').text(x);
	    $('#y_pos').text(y); 
	    $('#location_images_position').val(Math.round(100*x/width)+','+Math.round(100*y/height));
	    $('#img_selected').css('top',offset.top+y-6).css('left',offset.left+x-6);
	});
	
	if(cor!=''){
		cor = cor.split(',');
		$('#img_src > img').load(function(){
			height = $("#img_src > img").height();
			$('#img_selected').css('top',offset.top+(cor[1]*height/100)-6).css('left',offset.left+(cor[0]*width/100)-6);
			
		});
	}else{
		$('#img_selected').css('top',0).css('left',0);
	}
});
var initAdvDialogs = (function(objId) {
	$("#" + objId).dialog( {
		autoOpen : false,
		resizable : false,
		height : 580,
		width : 600,
		modal : true,
		open: function() {
			$("button." + objId + "-add").click(function(){$("div." + objId+'-dialog').dialog('open');return false;});
		},
		buttons : {
			"Hoàn tất" : function() {
				$("#" + objId + '-form').submit();
			},
			"Hủy bỏ" : function() {
				$(this).dialog('close');
			}
		}
	});
	dialogSelectCategory("div." + objId+'-dialog',"#" + objId + "-list",'#'+objId+'-selected');
	$('button.' + objId).click(function() {
		$('#' + objId).dialog('open');
	});	
});

var dialogEditAdv = (function(obj,dialog) {
	$.get(obj.attr('href'), function(data) {
		$("#"+dialog+"-form").attr('action',obj.attr('href'));
		
		$.each(data, function(val, text) {
	  		$("#"+dialog+"-form input[name="+val+"]").val(text);
		});
		$("#"+dialog+"-form select[name=location]").val(data.location);
		$("#"+dialog+"-form select[name=active]").val(data.active);
		$('#'+dialog+'-selected').html('');
		$('#'+dialog).dialog('open');
		$categories = data.adv2page.split(',');
		$.each($categories, function(i,category) {
			ajaxGetCategory(category,'#'+dialog+'-selected',i);
		});
		$.each(data.country_code, function(code, item) {
			$("#"+dialog+"-country_code_"+item).attr('checked','checked');
		});
	}, "json");
});
var initDeleteAdvDialog = (function(){
	$('#dialog-form-delete').dialog( {
		autoOpen : false,
		resizable : false,
		height : 150,
		width : 350,
		modal : true,
		buttons : {
			"Tôi chắc chắn" : function() {
				$('#dialog-form-delete-form').submit();
			},
			"Hủy bỏ" : function() {
				$(this).dialog('close');
			}
		}
	});
});
var dialogDeleteAdv = (function(obj) {
	//$.get(obj.attr('href'), function(data) {
		$("#dialog-form-delete-form").attr('action',obj.attr('href'));		
		$('div.alert_delete>b').text($('a.adv_'+obj.attr('rel')).text());
		$('#dialog-form-delete').dialog('open');
	//}, "json");
});


var initGroupDialogs = (function(objId) {
	$("#" + objId).dialog( {
		autoOpen : false,
		resizable : false,
		height : 580,
		width : 600,
		modal : true,
		open: function() {
			$("button." + objId + "-add").click(function(){$("div." + objId+'-dialog').dialog('open');return false;});
		},
		buttons : {
			"Hoàn tất" : function() {
				$("#" + objId + '-form').submit();
			},
			"Hủy bỏ" : function() {
				$(this).dialog('close');
			}
		}
	});
	
	$('button.' + objId).click(function() {
		$('#' + objId).dialog('open');
	});	
});
var dialogEditGroup = (function(obj,dialog) {
	$.get(obj.attr('href'), function(data) {
		$("#"+dialog+"-form").attr('action',obj.attr('href'));
		
		$.each(data, function(val, text) {
	  		$("#"+dialog+"-form input[name="+val+"]").val(text);
		});
		$('#'+dialog).dialog('open');
		$.each(data.role, function(a, module) {
			$.each(module, function(key, value) {
				$.each(value, function(code, item) {
					$("#"+dialog+"_"+a+"_Role_"+key+"_"+code).attr('checked','checked');
				});
			});
		});
	}, "json");
});

var ajaxGetCategory = (function(category,objId,index) {
	if(index==undefined)index='';
	if (category == '0') {
		$(objId).append('<div id="category-0"><input type="button" onclick="$(\'#category-0\').remove();return false;" value="X"/> Trang chủ<input type="hidden" value="0" name="news_categories['+index+']"/></div>');
	} else {
		$.get("admin/ajax/getpathofcategory", {category : category,index:index}, function(data) {
			$(objId).append(data);
		});
	}
});

var initMemberDialogs = (function(objId) {
	$("#" + objId).dialog( {
		autoOpen : false,
		resizable : false,
		height : 460,
		width : 400,
		modal : true,
		open: function() {
			$("button." + objId + "-add").click(function(){$("div." + objId+'-dialog').dialog('open');return false;});
		},
		buttons : {
			"Hoàn tất" : function() {
				$("#" + objId + '-form').submit();
			},
			"Hủy bỏ" : function() {
				$(this).dialog('close');
			}
		}
	});
	
	$('button.' + objId).click(function() {
		$('#' + objId).dialog('open');
	});	
});

var dialogEditMember = (function(obj,dialog) {
	$.get(obj.attr('href'), function(data) {
		$("#"+dialog+"-form").attr('action',obj.attr('href'));
		
		$.each(data, function(val, text) {
	  		$("#"+dialog+"-form input[name="+val+"]").val(text);
		});
		$("#"+dialog+"-form input[name=username]").attr("disabled", true);
		$.each(data.groups_id,function(k,v){
			$("#"+dialog+"-form #groups_id-"+v).attr('checked', true);
		});
		$("#"+dialog+"-form select[name=active]").val(data.active);
		$('#'+dialog).dialog('open');
		
	}, "json");
});
var initDeleteMemberDialog = (function(){
	$('#dialog-form-delete').dialog( {
		autoOpen : false,
		resizable : false,
		height : 150,
		width : 350,
		modal : true,
		buttons : {
			"Tôi chắc chắn" : function() {
				$('#dialog-form-delete-form').submit();
			},
			"Hủy bỏ" : function() {
				$(this).dialog('close');
			}
		}
	});
});

var dialogDeleteMember = (function(obj) {
	//$.get(obj.attr('href'), function(data) {
		$("#dialog-form-delete-form").attr('action',obj.attr('href'));
		$('div.alert_delete>b').text($('td.member_'+obj.attr('rel')).text());
		$('#dialog-form-delete').dialog('open');
	//}, "json");
});


var initCategoriesDialogs = (function(objId) {
	$("#" + objId).dialog( {
		autoOpen : false,
		resizable : false,
		height : 450,
		width : 600,
		modal : true,
		open: function() {
			$("button." + objId + "-add").click(function(){$("div." + objId+'-dialog').dialog('open');return false;});
		},
		buttons : {
			"Hoàn tất" : function() {
				$("#" + objId + '-form').submit();
			},
			"Hủy bỏ" : function() {
				$(this).dialog('close');
			}
		}
	});
	
	$('button.' + objId).click(function() {
		$('#' + objId).dialog('open');
	});	
});
var initDeleteMemberDialog = (function(){
	$('#dialog-form-delete').dialog( {
		autoOpen : false,
		resizable : false,
		height : 150,
		width : 350,
		modal : true,
		buttons : {
			"Tôi chắc chắn" : function() {
				$('#dialog-form-delete-form').submit();
			},
			"Hủy bỏ" : function() {
				$(this).dialog('close');
			}
		}
	});
});
var dialogDeleteCategories = (function(obj) {
	//$.get(obj.attr('href'), function(data) {
		$("#dialog-form-delete-form").attr('action',obj.attr('href'));		
		$('div.alert_delete>b').text($('a.category_'+obj.attr('rel')).text());
		$('#dialog-form-delete').dialog('open');
	//}, "json");
});
var dialogEditCategories = (function(obj,dialog) {
	$.get(obj.attr('href'), function(data) {
		$("#"+dialog+"-form").attr('action',obj.attr('href'));
		
		$.each(data, function(val, text) {
	  		$("#"+dialog+"-form input[name="+val+"]").val(text);
		});
		if(data.conditions_group_id)
		$.each(data.conditions_group_id.split(','), function(val, text) {
			$('#'+dialog+'-conditions_group_id-'+text).attr('checked', true);
		});
		$("#"+dialog+"-form select[name=product_type_id]").val(data.product_type_id);
		$("#"+dialog+"-form select[name=product_categories_id]").val(data.product_categories_id);
		$("#"+dialog+"-form select[name=status]").val(data.status);	
		$("#dialog-form-edit img[name=anh]").attr('src','uploads/productcate/'+data.id+'/thumb.jpg');	
		$("#dialog-form-edit textarea[name=detail]").val(data.detail);
		$('#'+dialog).dialog('open');
	}, "json");
});

var doCheckTitle = (function(val,id){
	$.post(DOMAIN_NAME+'ajax/checktitle',{title:val,id:id},function(data){
		$('input[name=title_plain]').val(data.title_plain);
		if(data.code=='Error'){
			$('#title_report').html('<a href="'+data.link+'" target="_blank">'+data.title+'</a>');
			alert('Có thể bài viết này đã tồn tại hoặc bị trùng liên kết, Hãy kiểm tra ở mục "Link"\nBạn có thể sửa lại liên kết nếu muốn');
		}else{$('#title_report').html('');}
	},'json');
});
var doCheckTitlePlain = (function(val){
	if(val==''){
		alert('Liên kết không thể để trống, Hệ thống sẽ tự động tạo liên kết theo tiêu đề bài viết');
		doCheckTitle($('input[name=title]').val());
	}else{
		$.post(DOMAIN_NAME+'ajax/checktitleplain',{title:val},function(data){
			if(data.code=='Error'){
				$('#title_report').html('<a href="'+data.link+'" target="_blank">'+data.title+'</a>');
				alert('Liên kết bạn muốn sử dụng đã được sử dụng trước đo, hãy chọn một liên kết mới');
			}else{$('#title_report').html('');}
		},'json');
	}
});
var initAutoComplete = (function(obj,link,form){
	
	$(obj).autocomplete({
		source : DOMAIN_NAME + "admin/ajax/" + link,
		minLength: 3,
		select: function(event, ui) {
			$('#customers_id').val(ui.item.id);	
		}
	});
});

var initDateSelect = (function(obj){
	$(obj).datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: "yy-mm-dd",
		showTime: true,
		timePos: 'top'
	});
});
var initDeleteNewsDialog = (function(){
	$('#dialog-form-delete').dialog( {
		autoOpen : false,
		resizable : false,
		height : 150,
		width : 350,
		modal : true,
		buttons : {
			"Tôi chắc chắn" : function() {
				$('#dialog-form-delete-form').submit();
			},
			"Hủy bỏ" : function() {
				$(this).dialog('close');
			}
		}
	});
});
var dialogDeleteNews = (function(obj) {
	//$.get(obj.attr('href'), function(data) {
		$("#dialog-form-delete-form").attr('action',obj.attr('href'));		
		$('div.alert_delete>b').text($('a.news_'+obj.attr('rel')).text());
		$('#dialog-form-delete').dialog('open');
	//}, "json");
});

var initVideoDialogs = (function(objId) {
	$("#" + objId).dialog( {
		autoOpen : false,
		resizable : false,
		height : 400,
		width : 650,
		modal : true,
		buttons : {
			"Hoàn tất" : function() {
				$("#" + objId + '-form').submit();
			},
			"Hủy bỏ" : function() {
				$(this).dialog('close');
			}
		}
	});
	$('button.' + objId).click(function() {
		$('#' + objId).dialog('open');
	});	
});

var dialogEditVideo = (function(obj,dialog) {
	$.get(obj.attr('href'), function(data) {
		$("#"+dialog+"-form").attr('action',obj.attr('href'));
		$.each(data, function(val, text) {
	  		$("#"+dialog+"-form input[name="+val+"]").val(text);
		});
		$("#"+dialog+"-form select[name=active]").val(data.active);
		$('#'+dialog).dialog('open');
	}, "json");
});
var initDeleteVideoDialog = (function(){
	$('#dialog-form-delete').dialog( {
		autoOpen : false,
		resizable : false,
		height : 150,
		width : 350,
		modal : true,
		buttons : {
			"Tôi chắc chắn" : function() {
				$('#dialog-form-delete-form').submit();
			},
			"Hủy bỏ" : function() {
				$(this).dialog('close');
			}
		}
	});
});
var dialogDeleteVideo = (function(obj) {
	//$.get(obj.attr('href'), function(data) {
		$("#dialog-form-delete-form").attr('action',obj.attr('href'));		
		$('div.alert_delete>b').text($('a.video_'+obj.attr('rel')).text());
		$('#dialog-form-delete').dialog('open');
	//}, "json");
});
var initCommentDialog = (function(){
	$('#comment_review').dialog( {
		autoOpen : false,
		resizable : false,
		height : 410,
		width : 600,
		modal : true,
		buttons : {
			"Xóa đi" : function() {
				$('#comment_review_form input[name=active]').val('-1');
				$('#comment_review_form').submit();
			},
			"Đã duyệt" : function() {
				$('#comment_review_form input[name=active]').val(1);
				$('#comment_review_form').submit();
			},
			"Chưa duyệt" : function() {
				$('#comment_review_form input[name=active]').val(0);
				$('#comment_review_form').submit();
			},			
			"Bỏ qua" : function() {
				$(this).dialog('close');
			}
		}
	});
});
var dialogComment = (function(obj) {
	$.get(obj.attr('href'), function(data) {
		$.each(data, function(val, text) {
	  		$("#comment_review_form input[name="+val+"]").val(text);
		});
		$.each(data, function(val, text) {
	  		$("#comment_review_form ."+val+">b").text(text);
		});
		$("#comment_review_form textarea[name=body]").val(data.body);
		$('#comment_review').dialog('open');
	}, "json");
});
var initContactDialog = (function(){
	$('#comment_review').dialog( {
		autoOpen : false,
		resizable : false,
		height : 410,
		width : 750,
		modal : true,
		buttons : {
			"Xóa đi" : function() {
				$('#comment_review_form input[name=active]').val('-1');
				$('#comment_review_form').submit();
			},
			"Đã duyệt" : function() {
				$('#comment_review_form input[name=active]').val(1);
				$('#comment_review_form').submit();
			},
			"Chưa duyệt" : function() {
				$('#comment_review_form input[name=active]').val(0);
				$('#comment_review_form').submit();
			},			
			"Bỏ qua" : function() {
				$(this).dialog('close');
			}
		}
	});
});
var dialogContact = (function(obj) {
	$.get(obj.attr('href'), function(data) {		
		$.each(data, function(val, text) {
	  		$("."+val).html(text);
		});
		$('#comment_review').dialog('open');
	}, "json");
});

var doChangeSpecial = (function(url,id){
	$.get(url, function(data) {	
		if(data.code=='Image'){
			if(confirm("Ảnh này chưa được CROP theo kích thước tiêu chuẩn\nChọn OK để sửa ảnh ngay")){
				location.href=data.html;
			}
		}else if(data.code=='Success')
			$('#special_'+id).html(data.html);
		else alert(data.html);
	},'json');
});
var addPollItem = (function(obj,name,value){
	var index = $('#cacher').val();
	var html='<tr id="'+obj+'_item_'+index+'"><td><input type="text" size="60" name="item['+index+'][title]" value="'+name+'" /></td><td><input size="5" type="text" name="item['+index+'][voted]" value="'+value+'" /></td><td><input type="button" onclick="$(\'#'+obj+'_item_'+index+'\').remove();" value=" - " /></td></tr>';
	$('#'+obj+'_item_list > tbody:last').append(html);
	$('#cacher').val(parseInt(index) + 1);
});
var initPollDialogs = (function(objId) {
	$("#" + objId).dialog( {
		autoOpen : false,
		resizable : false,
		height : 400,
		width : 600,
		modal : true,
		buttons : {
			"Hoàn tất" : function() {
				$("#" + objId + '-form').submit();
			},
			"Hủy bỏ" : function() {
				$(this).dialog('close');
			}
		}
	});
	$('button.' + objId).click(function() {
		$('#' + objId).dialog('open');
	});	
});
var initDeletePollDialog = (function(){
	$('#dialog-form-delete').dialog( {
		autoOpen : false,
		resizable : false,
		height : 150,
		width : 350,
		modal : true,
		buttons : {
			"Tôi chắc chắn" : function() {
				$('#dialog-form-delete-form').submit();
			},
			"Hủy bỏ" : function() {
				$(this).dialog('close');
			}
		}
	});
});
var dialogEditPoll = (function(obj,dialog) {
	$.get(obj.attr('href'), function(data) {
		$("#"+dialog+"-form").attr('action',obj.attr('href'));
		
		$.each(data, function(val, text) {
	  		$("#"+dialog+"-form input[name="+val+"]").val(text);
		});
		$("#"+dialog+"-form select[name=home_page]").val(data.home_page);
		$("#"+dialog+"-form select[name=status]").val(data.status);
		$('#'+dialog).dialog('open');
		$('#'+dialog+'_item_list > tbody > tr').remove();
		$.each(data.item, function(val, text) {
			addPollItem(dialog,text.title,text.voted);
		});
		
	}, "json");
});
var dialogDeletePoll = (function(obj) {
	//$.get(obj.attr('href'), function(data) {
		$("#dialog-form-delete-form").attr('action',obj.attr('href'));		
		$('div.alert_delete>b').text($('a.category_'+obj.attr('rel')).text());
		$('#dialog-form-delete').dialog('open');
	//}, "json");
});
var doSubmitChat = (function(){
	var message = $('#message').val();
	$('#message').val('');
	if(message.length>0){
		$.post('chat/dopost',{message: message},function(data){
		
		});
	}
	return false;
});
var initAppendFile = (function() {
	var obj_id = $('#cacher_file').val();
	var txtPromo = '<tr id="tr-file-' + obj_id + '">';
	txtPromo += '	<td><input type="file" name="images[' + obj_id + ']"></td>';
	txtPromo += '	<td><input type="text" value="" name="caption[' + obj_id + ']"></td>';
	txtPromo += '	<td><button onclick="$(\'#tr-file-' + obj_id + '\').remove();return false">X</button></td>';
	txtPromo += '</tr>';
	$('#table_file > tbody:last').append(txtPromo);
	$('#cacher_file').val(parseInt(obj_id) + 1);
});
var initConditionGroupDialog = (function() {
	$("#dialog-form-create").dialog( {
		autoOpen : false,
		resizable : false,
		height : 300,
		width : 400,
		modal : true,
		buttons : {
			Create : function() {
				$("#form_create_country").submit();
			},
			Cancel : function() {
				$(this).dialog('close');
			}
		}
	});
	$("#dialog-form-edit").dialog( {
		autoOpen : false,
		resizable : false,
		height : 300,
		width : 400,
		modal : true,
		open : function() {
			$('div.gmnoprint').css('z-index', 99999);
		},
		buttons : {
			Save : function() {
				$("#form_edit_country").submit();
			},
			Cancel : function() {
				$(this).dialog('close');
			}
		}
	});

	$('#create-user').click(function() {
		//initializeGmap($("#form_create_country input[name=map]"), 540, 400);
		$('#dialog-form-create').dialog('open');
		//tinyMCE.execCommand('mceAddControl', false, 'form_create_country-visa_requirements');
	});
});
var initCountriesDialog = (function() {
	$("#dialog-form-create").dialog( {
		autoOpen : false,
		resizable : false,
		height : 550,
		width : 720,
		modal : true,
		buttons : {
			Create : function() {
				$("#form_create_country").submit();
			},
			Cancel : function() {
				$(this).dialog('close');
			}
		}
	});
	$("#dialog-form-edit").dialog( {
		autoOpen : false,
		resizable : false,
		height : 550,
		width : 720,
		modal : true,
		open : function() {
			$('div.gmnoprint').css('z-index', 99999);
		},
		buttons : {
			Save : function() {
				$("#form_edit_country").submit();
			},
			Cancel : function() {
				$(this).dialog('close');
			}
		}
	});

	$('#create-user').click(function() {
		//initializeGmap($("#form_create_country input[name=map]"), 540, 400);
		$('#dialog-form-create').dialog('open');
		//tinyMCE.execCommand('mceAddControl', false, 'form_create_country-visa_requirements');
	});
});
/**
 * Init value to Edit country dialog
 * 
 * @param obj
 *            a link obj
 * @return
 */
var dialogEditCountry = (function(obj) {
	$.get($(obj).attr('href'),
			function(data) {
				$("#dialog-form-edit input[name=id]").val(data.id);
				$("#dialog-form-edit input[name=name]").val(data.name);
				$("#dialog-form-edit input[name=sort_orders]").val(data.sort_orders);
				//$("#dialog-form-edit textarea[name=description]").val(data.description);
				$("#dialog-form-edit select[name=status]").val(data.status);
				//$("#dialog-form-edit input[name=map]").val(data.map);
				//initializeGmap($("#dialog-form-edit input[name=map]"), 540,	400, 'map_edit');
				//$("#dialog-form-edit input[name=seo_title]").val(data.seo_title);
				//them vao conditions group 
				$("#dialog-form-edit input[name=display_name]").val(data.display_name);
				//them vao conditions name
				$("#dialog-form-edit select[name=conditions_group_id]").val(data.conditions_group_id);
				$('#dialog-form-edit').dialog('open');
				tinyMCE.execCommand('mceAddControl', false, 'form_edit_country-visa_requirements');
			}, "json");
});
var dialogView = (function(obj) {
	$.get($(obj).attr('href'),function(data) {
		if(data.length > 0){
			$('#dialog-form-view-customer').dialog({ 
				height : 525,
				width : 720,
				buttons : {
					"Chỉnh sửa" : function() {
						location.href='/admin/customer/edit/id/'+id;
					},
					"Đóng" : function() {
						$(this).dialog('close');
					}
				}
			});
		}
	}, "json");
});

/**
 * Start Javascript for Cities page
 * 
 * Init dialogs for cities
 */
var initCityDialog = (function() {
	$("#dialog-form-create").dialog( {
		autoOpen : false,
		resizable : false,
		height : 518,
		width : 720,
		modal : true,
		buttons : {
			Create : function() {
				$("#form_create_city").submit();
			},
			Cancel : function() {
				$(this).dialog('close');
			}
		}
	});
	$('#create-city').click(function() {
		//initializeGmap($("#form_create_city input[name=map]"), 633, 400);
		$('#dialog-form-create').dialog('open');
	});

	$("#dialog-form-edit").dialog( {
		autoOpen : false,
		resizable : false,
		height : 518,
		width : 720,
		modal : true,
		buttons : {
			Save : function() {
				$("#form_edit_city").submit();
			},
			Cancel : function() {
				$(this).dialog('close');
			}
		}
	});
});
/**
 * Dialog for edit city
 * 
 * @param obj
 * @return
 */
var dialogEditCity = (function(obj) {
	$.get($(obj).attr('href'),
					function(data) {
						$("#dialog-form-edit input[name=id]").val(data.id);
						$("#dialog-form-edit input[name=url]").val($(obj).attr('href'));
						$("#dialog-form-edit select[name=countries_id]").val(data.countries_id);
						$("#dialog-form-edit input[name=name]").val(data.name);
						//$("#dialog-form-edit input[name=tags]").val(data.tags);
						//$("#dialog-form-edit input[name=trasport_code]").val(data.trasport_code);
						//if (data.settop == 1)
						//	$("#dialog-form-edit input[name=settop]").attr('checked', true);
						$("#dialog-form-edit input[name=sort_orders]").val(data.sort_orders);
						//$("#dialog-form-edit textarea[name=description]").val(data.description);
						$("#dialog-form-edit select[name=status]").val(data.status);
						//$("#dialog-form-edit select[name=countries_id]").val(data.countries_id);
						//$("#dialog-form-edit select[name=transport]").val(data.transport);
						$("#dialog-form-edit input[name=map]").val(data.map);
						//initializeGmap($("#dialog-form-edit input[name=map]"),633, 400, 'map_edit');
						//$("#dialog-form-edit input[name=seo_title]").val(data.seo_title);
						//$("#dialog-form-edit input[name=seo_keyword]").val(data.seo_keyword);
						//$("#dialog-form-edit textarea[name=seo_description]").val(data.seo_description);
						$('#dialog-form-edit').dialog('open');
					}, "json");
});
/**
 * Save city via Ajax
 * 
 * @param obj
 * @return
 */
var ajaxSaveCity = (function(obj) {
	$.post($("#dialog-form-edit input[name=url]").val(),
			$('#form_edit_country').serialize(), function(data) {
				location.reload();
			});
});
// End javascript for Cities

/**
 * Start Javascript for Location page
 * 
 * Init dialogs for Location
 */
var initLocationDialog = (function() {
	$("#dialog-form-create").dialog( {
		autoOpen : false,
		height : 500,
		width : 550,
		modal : true,
		resizable : false,
		buttons : {
			'Create' : function() {
				$("#form_create_location").submit();
			},
			Cancel : function() {
				$(this).dialog('close');
			}
		}
	});
	$('#create-location').button().click(function() {
		initializeGmap($("#form_create_location input[name=map]"), 633, 400);
		$('#dialog-form-create').dialog('open');
	});
	$("#dialog-form-edit").dialog( {
		autoOpen : false,
		height : 500,
		width : 550,
		modal : true,
		resizable : false,
		buttons : {
			'Save' : function() {
				$("#form_edit_location").submit();
			},
			Cancel : function() {
				$(this).dialog('close');
			}
		}
	});
});
/**
 * Dialog for edit location
 * 
 * @param obj
 * @return
 */
var dialogEditLocation = (function(obj) {
	$.get($(obj).attr('href'), function(data) {
		$("#dialog-form-edit input[name=id]").val(data.id);
		$("#dialog-form-edit input[name=url]").val($(obj).attr('href'));
		$("#dialog-form-edit img[name=anh]").attr('src','uploads/yellow/location/'+data.id+'.jpg');
		$("#dialog-form-edit input[name=name]").val(data.name);
		$("#dialog-form-edit input[name=sort_orders]").val(data.sort_orders);
		$("#dialog-form-edit select[name=status]").val(data.status);
		$("#dialog-form-edit input[name=map]").val(data.map);
		initializeGmap($("#dialog-form-edit input[name=map]"),633, 400, 'map_edit');
		$('#dialog-form-edit').dialog('open');
	}, "json");
});
/**
 * Save city via Ajax
 * 
 * @param obj
 * @return
 */
var ajaxSaveLocation = (function(obj) {
	var id = $("#dialog-form-edit input[name=locaion]").val();
	var name = $("#dialog-form-edit input[name=name]").val();
	var sort = $("#dialog-form-edit input[name=sort_order]").val();
	var status = $("#dialog-form-edit select[name=status]").val();
	$.post($("#dialog-form-edit input[name=url]").val(), {
		location : id,
		name : name,
		sort_order : sort,
		status : status
	}, function(data) {
		location.reload();
	});
});
// End javascript for Cities

var ajaxLoadForOption = (function(url, obj, selected) {
	obj.children().remove();
	$.get(url, function(data) {
		if (data) {
			$.each(data, function(val, text) {
				checked = (val == selected) ? true : false;
				obj.append(new Option(text, val, checked, checked));
			});
		}
	}, "json");
});
var initializeGmap = (function(Coordinate, width, height, inner) {
	if (width == undefined)
		width = 950;
	if (height == undefined)
		height = 350;
	if (inner == undefined)
		inner = 'map_canvas';
	if (GBrowserIsCompatible()) {
		var map = new GMap2(document.getElementById(inner), {
			size : new GSize(width, height)
		});

		map.setCenter(new GLatLng(21.03443357007912, 105.84455107804388), 13);
		map.setUIToDefault();
		map.enableGoogleBar();
		// Kiem tra xem da co toa do chua, neu co roi thi set marker
		if (Coordinate.val() != '') {
			var latlng = Coordinate.val();
			latlng = latlng.split(",");
			var pointLoad = new GLatLng(latlng[0], latlng[1]);
			var marker = createMarker(pointLoad);
			// map.panTo(pointLoad);		
			map.addOverlay(marker);
			if (latlng.length == 4) {
				map.setCenter(pointLoad, parseInt(latlng[3]));
			}else{
				map.setCenter(pointLoad, parseInt(latlng[2]));
			}
		}
		GEvent.addListener(map, "tilesloaded", function() {
			$('div.gmnoprint').css('z-index', 99999);
		});
		GEvent.addListener(map, "click", function(overlay, latlng) {
			if (overlay)
				return;
			// Clear toan bo map
				map.clearOverlays();
				var point = new GLatLng(latlng.lat(), latlng.lng());
				// Luu lai coordinate
				Coordinate.val(latlng.lat() + "," + latlng.lng() + ","+ map.getZoom());
				var marker = createMarker(point);
				map.setCenter(point, map.getZoom());
				map.addOverlay(marker);
			});
	}
});

var createMarker = (function(point) {
	var baseIcon = new GIcon();
	baseIcon.shadow = "http://www.google.com/mapfiles/shadow50.png";
	// baseIcon.iconSize = new GSize(28, 26);
	// baseIcon.shadowSize = new GSize(37, 26);
	baseIcon.iconAnchor = new GPoint(9, 26);
	baseIcon.infoWindowAnchor = new GPoint(9, 2);
	baseIcon.infoShadowAnchor = new GPoint(18, 25);
	var iconStar = new GIcon(baseIcon);
	iconStar.image = 'http://tuntravel.com/images/markerA.png';
	markerOptions = {
		icon : iconStar
	};
	var markerClick = new GMarker(point, markerOptions);
	return markerClick;
});
var ParsedAdvHtml = (function(a, b, c, d) {
	var height = 'height="' + c + '"';
	var html = '';
	var width = 'width="' + d + '"';
	if (b != 'image')
		html += '<object '
				+ width
				+ height
				+ ' align="middle" id="ob11" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"><param value="transparent" name="wmode" /><param value="sameDomain" name="allowScriptAccess" /><param value="'
				+ DOMAIN_NAME
				+ a
				+ '" name="movie" /><param value="high" name="quality" /><embed '
				+ width
				+ height
				+ ' align="middle" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" allowscriptaccess="sameDomain" wmode="transparent" quality="high" src="'
				+ DOMAIN_NAME + a + '" /></object>';
	else
		html += '<img src="' + a + '"' + width + ' ' + height + '">';
	return html;
});

var initReviewsDialog = (function() {
	$("#dialog-Review-view").dialog( {
		autoOpen : false,
		resizable : false,
		height : 500,
		width : 650,
		modal : true,
		buttons : {
			'Save' : function() {
				ajaxSaveReviewDetail();
				//$("#form_edit_review").submit();
			},
			Cancel : function() {
				$(this).dialog('close');
			}
		}
	});
});
var ajaxSaveReviewDetail = (function(obj) {
    var created_date = $("#form_edit_review input[name=created_date]").val();
	var comment = $("#form_edit_review textarea[name=content]").val();
	var status = $("#form_edit_review select[name=status]").val();
	$.post($("#form_edit_review input[name=url]").val(), {
			created_date : created_date,
			content : content,
			status : status
		}, function(data) {
			location.reload();
		});
});
var dialogReviewDetail = (function(obj) {
	$.get($(obj).attr('href'), function(data) {
		$("#dialog-Review-view_inner").html(data);
		$('#dialog-Review-view').dialog('open');
	});
});

var initProducttypeDialogs = (function(objId) {
	$("#" + objId).dialog( {
		autoOpen : false,
		resizable : false,
		height : 200,
		width : 600,
		modal : true,
		open: function() {
			$("button." + objId + "-add").click(function(){$("div." + objId+'-dialog').dialog('open');return false;});
		},
		buttons : {
			"Hoàn tất" : function() {
				$("#" + objId + '-form').submit();
			},
			"Hủy bỏ" : function() {
				$(this).dialog('close');
			}
		}
	});
	
	$('button.' + objId).click(function() {
		$('#' + objId).dialog('open');
	});	
});
var dialogEditProducttype = (function(obj,dialog) {
	$.get(obj.attr('href'), function(data) {
		$("#"+dialog+"-form").attr('action',obj.attr('href'));
		
		$.each(data, function(val, text) {
	  		$("#"+dialog+"-form input[name="+val+"]").val(text);
		});
		if(data.conditions_group_id)
		$.each(data.conditions_group_id.split(','), function(val, text) {
			$('#'+dialog+'-conditions_group_id-'+text).attr('checked', true);
		});
		$("#"+dialog+"-form input[name=name]").val(data.name);
		$("#"+dialog+"-form input[name=orders]").val(data.orders);	
		$('#'+dialog).dialog('open');
	}, "json");
});
var dialogDeleteProducttype = (function(obj) {
	//$.get(obj.attr('href'), function(data) {
		$("#dialog-form-delete-form").attr('action',obj.attr('href'));		
		$('div.alert_delete>b').text($('a.category_'+obj.attr('rel')).text());
		$('#dialog-form-delete').dialog('open');
	//}, "json");
});
var selectCity = (function(){
	var arrCity = new Array();
	arrCity['HCM']='TP. Hồ Chí Minh';
	arrCity['HP']='TP. Hải Phòng';
	arrCity['DN']='TP. Đà Nẵng';
	arrCity['HG']='Tỉnh Hà Giang';
});