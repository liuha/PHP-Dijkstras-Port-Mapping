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
}); 