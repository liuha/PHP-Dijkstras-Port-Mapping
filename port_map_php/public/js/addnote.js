$( document ).ready( function() {

	// Render images on canvas
	// 6 is total floor
	for (i = 1 ; i <= 6 ; i++)
	{
		var canvas_id = "canvas" + i;
		var img_id = "image" + i;
		var canvas = document.getElementById(canvas_id);
		var ctx = canvas.getContext("2d");
		var img = document.getElementById(img_id);
		switch (i){
			case 1:
				ctx.drawImage(img, 10, 10, 971, 1000);
				break;
			default:
				ctx.drawImage(img, 10, 10, 637, 1000);
		}
	}



	$('.router').click(function() {

		// Render images on canvas
		// 6 is total floor
		for (i = 1 ; i <= 6 ; i++)
		{
			var canvas_id = "canvas" + i;
			var img_id = "image" + i;
			var canvas = document.getElementById(canvas_id);
			var ctx = canvas.getContext("2d");
			var img = document.getElementById(img_id);
			switch (i){
				case 1:
					ctx.drawImage(img, 10, 10, 971, 1000);
					break;
				default:
					ctx.drawImage(img, 10, 10, 637, 1000);
			}
		}


		var x = $(this).attr('id');
	  //console.log(x);
		$('.search #floor').val(x);
	}); 

	
	// Gets the floor from the current data set
	function getCurrentLevel() {
		return parseInt($('#floor').val());
	}
	
	// show selected floor map and draw Marker
	// Check to see value of floor for current search
	// Add active class to current view
	// Call draw Marker
	$('.tab-pane').each(function() {
		if($(this).attr('id') == ('Level'+getCurrentLevel())) {
			$(this).addClass("in active");	
			drawMarker(getCurrentLevel());
		}
	})

	// Add active class to link after search--active left floor selecting bar
	var x = $('.nav-sidebar').find("#"+getCurrentLevel());
	x.addClass('active');

	// Gets current data coordinates and displays marker on target( target = floor number)
	function drawMarker(target) {	
		if (validCoordinates()) {
			var canvas_tar = "#canvas"+target;
			var marker_tar = "#marker"+target;
			var position = $(canvas_tar).position();
			var coordinates = getCoordinates();
			var left = coordinates.x + position.left - 21;
			var top = coordinates.y + position.top - 21;
			$(marker_tar).css('top', top+'px').css('left', left+'px').css('display','block');
			blink(marker_tar);
		}	
	}

	// Get the coordinates from current data set
  	function getCoordinates() {
  		return {x:parseInt($('#x_add').text()), y:parseInt($('#y_add').text())};
  	}

  	// Validate coordinates from curent data set
  	function validCoordinates() {
  		var x = parseInt($("#x_add").text());
  		var y = parseInt($("#y_add").text());
  		if (isNaN(x) || isNaN(y)) {
  			return false;
  		}
  		return true;
  	}

  	// Adds blinking to marker
  	function blink(selector){
	    $(selector).animate({opacity:0.25}, 50, "linear", function(){
	        $(this).delay(800);
	        $(this).animate({opacity:1}, 50, function(){
	        blink(this);
	        });
	        $(this).delay(800);
	    });
	}

	// Update marker position and draw it on map
	function updateMarker(target, x_coordinate, y_coordinate) {
		//console.log(target);
		var canvas_tar = "#canvas"+target;
		var marker_tar = "#marker"+target;
		var position = $(canvas_tar).position();
		//console.log(position);
		var left = x_coordinate + position.left - 21;
		var top = y_coordinate + position.top - 21;
		$(marker_tar).css('top', top + 'px').css('left', left+'px').css('display','block');
	}

	// When the map is clicked find the offset and page event to track 
	// requested coordinates, drawmarker at location
	$( '.map' ).on( "click", function( event ) {
	  		var offset = $(this).offset();
	  		var x_coordinate = Math.round(event.pageX-offset.left);
	  		var y_coordinate = Math.round(event.pageY-offset.top);
			updateCoordinates(x_coordinate, y_coordinate);
			updateMarker(getCurrentLevel(), x_coordinate, y_coordinate);
  	})
  	// Update form fields with new x,y
	function updateCoordinates(x_coordinate, y_coordinate) {
		document.getElementById("x_add").value = x_coordinate;
	  	document.getElementById("y_add").value = y_coordinate;
	}

	// Adjust marker to stay at proper location during resize event
	$(window).resize(function(){
    	drawMarker(getCurrentLevel());
	});


	//get floor node list
	$("#EdgeFloor").on('change',function(e){
		    var x = document.getElementById("EdgeFloor").value;
    		//document.getElementById("demo").innerHTML = "You selected: " + x;
    		if (x != 0)
    		{
    			//console.log(x);
    			$('#nodefloor').val(x);
    			$('#node_form').submit();
    		}
	});

});
