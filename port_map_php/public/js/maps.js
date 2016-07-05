$( document ).ready( function() {

	// Render images on canvas
	var canvas = document.getElementById("canvas3");
    var ctx = canvas.getContext("2d");
    var img = document.getElementById("image3");
   	ctx.drawImage(img, 10, 10, 637, 1000);

   	var canvas = document.getElementById("canvas4");
    var ctx = canvas.getContext("2d");
    var img = document.getElementById("image4");
   	ctx.drawImage(img, 10, 10, 637, 1000);

   	var canvas = document.getElementById("canvas5");
    var ctx = canvas.getContext("2d");
    var img = document.getElementById("image5");
   	ctx.drawImage(img, 10, 10, 637, 1000);

	// Gets the floor from the current data set
	function getCurrentLevel() {
		return $('.context_list').find("li#floor").text();
	}
	
	// show selected floor map and draw Marker
	// Check to see value of floor for current search
	// Add active class to current view
	// Call draw Marker
	$('.tab-pane').each(function() {
		//show floor map, draw marker and shortest path
		if($(this).attr('id') == ('Level'+getCurrentLevel())) {
			$(this).addClass("in active");	
			drawMarker(getCurrentLevel());
			//console.log(getCurrentLevel());
			drawpath(getCurrentLevel());
		}
		//draw port list on map
		port_num = parseInt($("#port_num").text());
		if ( port_num > 0 ){
			room_level = parseInt($("#room_floor").text());
			drawportlist(port_num, room_level);	
			if($(this).attr('id') == ('Level'+room_level)){
				$(this).addClass("in active");	
			} 	
		}
	})

	// Add active class to link after search--active left floor selecting bar
	//var x = $('.nav-sidebar').find("#"+getCurrentLevel());
	//x.addClass('active')

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
  		return {x:parseInt($('#x_coord').text()), y:parseInt($('#y_coord').text())};
  	}

  	// Validate coordinates from curent data set
  	function validCoordinates() {
  		var x = parseInt($("#x_coord").text());
  		var y = parseInt($("#y_coord").text());
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
		var canvas_tar = "#canvas"+target;
		var marker_tar = "#marker"+target;
		var position = $(canvas_tar).position();
		var left = x_coordinate + position.left - 21;
		var top = y_coordinate + position.top - 21;
		$(marker_tar).css('top', top + 'px').css('left', left+'px').css('display','block');
	}

	// When the map is clicked find the offset and page event to track 
	// requested coordinates, drawmarker at location
	$( '.map' ).on( "click", function( event ) {
		if($(this).hasClass('updating')) {
	  		var offset = $(this).offset();
	  		var x_coordinate = Math.round(event.pageX-offset.left);
	  		var y_coordinate = Math.round(event.pageY-offset.top);
			updateCoordinates(x_coordinate, y_coordinate);
			updateMarker(getCurrentLevel(), x_coordinate, y_coordinate);
			drawpath(getCurrentLevel());
			$('#update_form').submit();
		}
  	})

	// Update form fields with new x,y
	function updateCoordinates(x_coordinate, y_coordinate) {
		document.getElementById("x_update").value = x_coordinate;
	  	document.getElementById("y_update").value = y_coordinate;
	}


	// Adjust marker to stay at proper location during resize event
	$(window).resize(function(){
    	drawMarker(getCurrentLevel());
	});

	// Don't display dataset if it does not match current selected floor
	// Add current links' floor value to search bar
	$('.router').click(function() {

		// Render images on canvas
		var canvas = document.getElementById("canvas3");
	    var ctx = canvas.getContext("2d");
	    var img = document.getElementById("image3");
	   	ctx.drawImage(img, 10, 10, 637, 1000);

	   	var canvas = document.getElementById("canvas4");
	    var ctx = canvas.getContext("2d");
	    var img = document.getElementById("image4");
	   	ctx.drawImage(img, 10, 10, 637, 1000);

	   	var canvas = document.getElementById("canvas5");
	    var ctx = canvas.getContext("2d");
	    var img = document.getElementById("image5");
	   	ctx.drawImage(img, 10, 10, 637, 1000);


		var x = $(this).attr('id');
	  	console.log(x);
		$('.search #floor').val(x);

		// set element room_port_list display to none
		$('#room_port_list').css("display", "none");

		var level = getCurrentLevel();
		if($(this).attr('id') != level) {
			$('#data_set').css("display", "none");
		}
		else {
			$('#data_set').css("display", "block");
			drawpath(getCurrentLevel());
		}
	});


    // Toggle update form, draw marker when toggled to prevent resizing issues
	$('#update').click(function(){
		$(this).text('Updating...');
		var canvas = "#canvas"+ getCurrentLevel();
		$(canvas).addClass('updating');
    })

	//draw shortest path from main entry to dataport
    function drawpath(target){
    	var headlen = 10;   // length of head in pixels
    	
    	var nodenum = parseInt($("#nodenum").text());
    	var canvasTarget = "canvas"+target; 
    	var canvas = document.getElementById(canvasTarget);
    	var ctx = canvas.getContext("2d");
    	ctx.lineWidth="6";
    	ctx.lineCap="round";
    	ctx.strokeStyle="red";
    	ctx.lineJoin = "round";
    	//ctx.setLineDash([5, 15]);
    	for (i = 1 ; i < nodenum; i++){
    		//star point
    		next = i + 1;
    		x_coord_1 = parseInt($('#' + i + 'x' ).text());
    		y_coord_1 = parseInt($('#' + i + 'y' ).text());
    		//end point
    		x_coord_2 = parseInt($('#' + next + 'x' ).text());
    		y_coord_2 = parseInt($('#' + next + 'y' ).text());

    		ctx.moveTo(x_coord_1, y_coord_1);
    		ctx.lineTo(x_coord_2, y_coord_2);
    		
    		//draw arrow
    		var angle = Math.atan2(y_coord_2-y_coord_1,x_coord_2-x_coord_1);
    		ctx.lineTo(x_coord_2-headlen*Math.cos(angle-Math.PI/6),y_coord_2-headlen*Math.sin(angle-Math.PI/6));
    		ctx.moveTo(x_coord_2, y_coord_2);
    		ctx.lineTo(x_coord_2, y_coord_2);
    		ctx.lineTo(x_coord_2-headlen*Math.cos(angle+Math.PI/6),y_coord_2-headlen*Math.sin(angle+Math.PI/6));

    		ctx.stroke();

    	}

    }
    //draw room port list in relative floor map
    function drawportlist(port_num, room_level){
    	console.log(port_num);
    	console.log(room_level);

    	if ( port_num >0 ){
    		var canvasTarget = "canvas"+room_level; 
    		var canvas = document.getElementById(canvasTarget);
    		var ctx = canvas.getContext("2d"); 
    		ctx.fillStyle = "red";
    		for (i = 1 ; i <= port_num; i++){
    			x_coord = parseInt($('#' + i + 'x' ).text());
    			y_coord = parseInt($('#' + i + 'y' ).text());
    			ctx.fillRect(x_coord, y_coord, 10, 10);
    		}
    	}  		
    }


});

    
