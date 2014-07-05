/*	$(document).ready(function() {		
		$("img.tooltip").hover(function(e){
			$("#large").css("top",(e.pageY+5)+"px")
							 .css("left",(e.pageX+5)+"px")					
							 .html("<img src="+ $(this).attr("alt") +" width='400px' /><br/>"+$(this).attr("rel"))
							 .fadeIn("slow");
		}, function(){
			$("#large").fadeOut("fast");
		});				
	});*/
	
	$(document).ready(function() {		
		  /* CONFIG */
				
                var xOffset = 10;
                var yOffset = 30;

                // these 2 variable determine popup's distance from the cursor
                // you might want to adjust to get the right result

        /* END CONFIG */
        $("img.tooltip").hover(function(e)
		{
                $("body").append("<p id='preview'><img src='"+  $(this).attr("alt") +"' alt='Image preview' width='400px' /></BR>"+ $(this).attr("rel") +"</p>");
                $("#preview")
                        .css("top",(e.pageY - xOffset) + "px")
                        .css("left",(e.pageX + yOffset) + "px")
                        .fadeIn("fast");
    	},
        function()
		{
                this.rel = this.t;
                $("#preview").remove();
    	});
        $("img.tooltip").mousemove(function(e)
		{
                $("#preview")
                        .css("top",(e.pageY - xOffset) + "px")
                        .css("left",(e.pageX + yOffset) + "px");
        });

});