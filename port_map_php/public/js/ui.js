// Add current links' floor value to search bar
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
  console.log(x);
	$('.search #floor').val(x);
}); 