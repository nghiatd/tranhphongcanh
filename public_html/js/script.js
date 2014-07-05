$(document).ready(function(){
	//Register
	RegisterAccount();
	Login();
	Popup_Register();
});
var initializeGmapRegister = (function(Coordinate, width, height, inner) {
//	alert(Coordinate.val());
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

		//map.setCenter(new GLatLng(21.03443357007912, 105.84455107804388), 13);
		map.setCenter(new GLatLng(41.947234, -103.299866));
		map.setUIToDefault();
		//map.enableGoogleBar();
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
			Coordinate.val(latlng.lat() + "," + latlng.lng()+',' + map.getZoom());
			var marker = createMarker(point);
			map.setCenter(point, map.getZoom());
			map.addOverlay(marker);
		});
	}
});

var createMarker = (function(point, title, link) {
	var baseIcon = new GIcon();
	baseIcon.shadow = "http://www.google.com/mapfiles/shadow50.png";
	// baseIcon.iconSize = new GSize(28, 26);
	// baseIcon.shadowSize = new GSize(37, 26);
	baseIcon.iconAnchor = new GPoint(9, 26);
	baseIcon.infoWindowAnchor = new GPoint(9, 2);
	baseIcon.infoShadowAnchor = new GPoint(18, 25);
	var iconStar = new GIcon(baseIcon);
	iconStar.image = DOMAIN_NAME + '/images/markerA.png';
	markerOptions = {
		icon : iconStar,
		title : title
	};
	var markerClick = new GMarker(point, markerOptions);
	if (link)
		GEvent.addListener(markerClick, "click", function() {
			location.href = link;
		});

	return markerClick;
});
var RegisterAccount = (function(){
	$('#register').click(function(){
		var flag = true;
		var fullname = $('#frmRegister input[name=fullname]').val();
		if(fullname==''){
			flag=false;
			$('#frmRegister input[name=fullname]').css({border:'1px solid red'});
			$('#frmRegister input[name=fullname]').parent('td').find('span.error').html('Họ tên không được bỏ trống.').show();
		}
		var username = $('#frmRegister input[name=username]').val();
		if(username==''){
			flag=false;
			$('#frmRegister input[name=username]').css({border:'1px solid red'});
			$('#frmRegister input[name=username]').parent('td').find('span.error').html('Tên đăng nhập không được bỏ trống.').show();
		}
		var password = $('#frmRegister input[name=password]').val();
		if(password==''){
			flag=false;
			$('#frmRegister input[name=password]').css({border:'1px solid red'});
			$('#frmRegister input[name=password]').parent('td').find('span.error').html('Mật khẩu không được để trống').show();
		}
		var repassword = $('#frmRegister input[name=repassword]').val();
		if(repassword==''){
			flag=false;
			$('#frmRegister input[name=repassword]').css({border:'1px solid red'});
			$('#frmRegister input[name=repassword]').parent('td').find('span.error').html('Mật khẩu không được bỏ trống').show();
		}else{
			if(password!=repassword){
				flag=false;
				$('#frmRegister input[name=repassword]').css({border:'1px solid red'});
				$('#frmRegister input[name=repassword]').parent('td').find('span.error').html('Gõ lại mật khẩu không trùng với mật khẩu').show();
			}
		}
		var email = $('#frmRegister input[name=email]').val();
		if(email==''){
			flag=false;
			$('#frmRegister input[name=email]').css({border:'1px solid red'});
			$('#frmRegister input[name=email]').parent('td').find('span.error').html('Email không được bỏ trống').show();
		}
		var address = $('#frmRegister input[name=address]').val();
		if(address==''){
			flag=false;
			$('#frmRegister input[name=address]').css({border:'1px solid red'});
			$('#frmRegister input[name=address]').parent('td').find('span.error').html('Số nhà không được bỏ trống').show();
		}
		var phone = $('#frmRegister input[name=phone]').val();
		if(phone==''){
			flag=false;
			$('#frmRegister input[name=phone]').css({border:'1px solid red'});
			$('#frmRegister input[name=phone]').parent('td').find('span.error').html('Số điện thoại không được bỏ trống').show();
		}
		if(!$('#check').is(':checked')){
			flag=false;
			$('#check').parent('td').css({color:'red'});
		}
		if(flag){
			$('form#frmRegister').submit();
		}
	});
	$('form#frmRegister').submit(function(){
		$.post('user/register',$(this).serializeArray(),function(data){
			if(data.STATUS=='TRUE'){
				alert('Xin chúc mừng bạn đã đăng ký thành công.');
				$.fancybox.close();
			}
		});
		return false;
	});
});
var Login = (function(){
	$('#login').click(function(){
		var username = $('#frmLogin input[name=username]').val();
		var flag=true;
		if(username==''){
			flag=false;
			$('#frmLogin input[name=username]').css({border:'1px solid red'});
			$('#frmLogin input[name=username]').attr('title','Tên đăng nhập không được bỏ trống.');
		}
		var password = $('#frmLogin input[name=password]').val();
		if(password==''){
			flag=false;
			$('#frmLogin input[name=password]').css({border:'1px solid red'});
			$('#frmLogin input[name=password]').attr('title','Mật khẩu không được bỏ trống.');
		}
		if(flag){
			$('form#frmLogin').submit();
		}
	});	
	$('form#frmLogin').submit(function(){
		$.post('user/login',$(this).serializeArray(),function(data){
			if(data.STATUS=='TRUE'){
				location.href=data.URL;
			}else{
				alert('Đăng nhập không thành công, vui lòng xem lại tên đăng nhập hoặc mật khẩu.');
			}
		});
		return false;
	});
});
var Popup_Register = (function(){
	$('#popup_register').click(function(){
		$.fancybox.close();
		$('.reg').trigger('click');
	});
});
