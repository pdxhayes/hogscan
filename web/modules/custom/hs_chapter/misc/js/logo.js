(function($){

  $(document).ready(function(){
    // console.log('logo.js 0.0001');
    // Create chapter logo.

    function getCircularText(text, diameter, startAngle, align, textInside, inwardFacing, fName, fSize, kerning, topOffset, patchStyle) {
      // text:         The text to be displayed in circular fashion
      // diameter:     The diameter of the circle around which the text will
      //               be displayed (inside or outside)
      // startAngle:   In degrees, Where the text will be shown. 0 degrees
      //               if the top of the circle
      // align:        Positions text to left right or center of startAngle
      // textInside:   true to show inside the diameter. False to show outside
      // inwardFacing: true for base of text facing inward. false for outward
      // fName:        name of font family. Make sure it is loaded
      // fSize:        size of font family. Don't forget to include units
      // kearning:     0 for normal gap between letters. positive or
      //               negative number to expand/compact gap in pixels
      // console.log("text: " + text);
      // console.log("diameter: " + diameter);
      // console.log("startAngle: " + startAngle);
      // console.log("align: " + align);
      // console.log("textInside: " + textInside);
      // console.log("inwardFacing: " + inwardFacing);
      // console.log("fName: " + fName);
      // console.log("fSize: " + fSize);
      // console.log("kerning: " + kerning);
      // console.log("topOffset: " + topOffset);
      // console.log("patchStyle: " + patchStyle);
      //------------------------------------------------------------------------


      context.save();

      // declare and intialize canvas, reference, and useful variables
      align = align.toLowerCase();
      fSize = fSize.toString() + 'px';
      topOffset = parseInt(topOffset);

      var clockwise = align == "right" ? 1 : -1; // draw clockwise for aligned right. Else Anticlockwise
      startAngle = startAngle * (Math.PI / 180); // convert to radians

	    text = text.toUpperCase();
      // calculate height of the font
      var div = document.createElement("div");
      div.innerHTML = text;
      div.style.position = 'absolute';
      div.style.top = '-10000px';
      div.style.left = '-10000px';
      div.style.fontFamily = fName;
      div.style.fontSize = fSize;
      document.body.appendChild(div);
      var textHeight = div.offsetHeight;
      document.body.removeChild(div);

      // in cases where we are drawing outside diameter,
      // expand diameter to handle it
      if (!textInside) diameter += textHeight * 2;


       // set the text color
	  if("EAGLE" == patchStyle) {
	  	context.fillStyle = '#c7932c';
	  } else {
		  context.fillStyle = '#000000';
	  }

      context.font = fSize + ' ' + fName;

      // Reverse letters for align Left inward, align right outward
      // and align center inward.
      if (((["left", "center"].indexOf(align) > -1) && inwardFacing) || (align == "right" && !inwardFacing)) text = text.split("").reverse().join("");

      // Setup letters and positioning
      context.translate(mainCanvas.width / 2, (mainCanvas.width / 2) + topOffset); // Move to center
      startAngle += (Math.PI * !inwardFacing); // Rotate 180 if outward
      context.textBaseline = 'middle'; // Ensure we draw in exact center
      context.textAlign = 'center'; // Ensure we draw in exact center

      // rotate 50% of total angle for center alignment
      if (align == "center") {
        for (var j = 0; j < text.length; j++) {
          var charWid = context.measureText(text[j]).width;
          startAngle += ((charWid + (j == text.length-1 ? 0 : kerning)) / (diameter / 2 - textHeight)) / 2 * -clockwise;
        }
      }

      // Phew... now rotate into final start position
      context.rotate(startAngle);

      // Now for the fun bit: draw, rotate, and repeat
      for (var j = 0; j < text.length; j++) {
          var charWid = context.measureText(text[j]).width; // half letter
          // rotate half letter
          context.rotate((charWid/2) / (diameter / 2 - textHeight) * clockwise);
          // draw the character at "top" or "bottom"
          // depending on inward or outward facing
          context.fillText(text[j], 0, (inwardFacing ? 1 : -1) * (0 - diameter / 2 + textHeight / 2));

          context.rotate((charWid/2 + kerning) / (diameter / 2 - textHeight) * clockwise); // rotate half letter
      }
      context.restore();
    }

    function init_logo_drawing() {
      context.clearRect(0, 0, mainCanvas.width, mainCanvas.height);
      context.drawImage(img.get(0), 0, 0);
      getCircularText($('#text1').val(), 475, 0, "center", false, true, "Logo", $('#font1').val(), -2, $('#offset1').val(), $('#patchselect').val());
      getCircularText($('#text2').val(), 475, 0, "center", true, true, "Logo", $('#font2').val(), -2, $('#offset2').val(), $('#patchselect').val());
    }




    if("EAGLE" == $('#patchselect').val()) {
      var imgsrc = $('#eagle_logo_url').val();
	  } else if("SKULL" == $('#patchselect').val()) {
      var imgsrc = $('#skull_logo_url').val();
    }
    $('#generated-img').attr("src", imgsrc);



    var img = $('#generated-img');
    var mainCanvas = document.getElementById('logo-canvas');
    var context = mainCanvas.getContext('2d');

    img.on("load", function(){
      mainCanvas.width = img.width();
      mainCanvas.height = img.height();
      init_logo_drawing();
    });


    $('.form-text').on('input', init_logo_drawing);


    $('#patchselect').on('change', function() {

	    if("EAGLE" == $('#patchselect').val()) {
		    var imgsrc = $('#eagle_logo_url').val();
	    } else if("SKULL" == $('#patchselect').val()) {
		    var imgsrc = $('#skull_logo_url').val();
      }

	    // console.log("Set Image Source: " + imgsrc);
	    $('#generated-img').attr("src", imgsrc);

	    init_logo_drawing();

	  });

    init_logo_drawing();

    $('#create-logo').on('click', function(e) {
      e.preventDefault();
      var dataURL = mainCanvas.toDataURL('image/png');
      $('#logo-img').val(dataURL);
      $(this).closest('form').submit();
    });



  });

})(jQuery);
