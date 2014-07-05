<script>
	$(document).ready(function(){
		alert('ja');
		$('.add_cart').click(function(){
			$(this).hide(800).show(800);
			var id=$(this).attr('id');
			pos(id,'add');	
		});
		
		$('.custom_images').delegate('.del','click',function(){
			var id=$(this).attr('id');
			pos(id,'xoa');
		});
		
		function pos(id,act){
			$.ajax({
				type:'POST',
				url:'/add-cart.html',
				data:{id:id,act:act},
				success:function(data){
					if(data){
						var total=0;
						$('.custom_images').html("");				
						$.each(data,function(i,value){
							var product=value.split(",");
							var str='<div id='+product[2]+'><label>San pham : '+product[0]+'</label><br> <label>Gia : '+product[1]+'</label><br><label>So luong : '+product[3]+'</label><br><div  id='+product[2]+'> <button class="del" id='+product[2]+' >Del</button> </div><hr></div><hr>';
							$('.custom_images').append(str);
								
								total=total+parseInt(product[1]*product[3]);	
						});
							$('.custom_images').append('<label><b>Tong tien : '+total+'  VND</b></label>');
					}		
				},
			});
		}	
	});
 </script>