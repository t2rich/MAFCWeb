<?php
 ob_start();
 session_start();
 require_once 'dbconnect.php';

 // if session is not set this will redirect to login page
 if( !isset($_SESSION['user']) ) {
  header("Location: index.php");
  exit;
 }
 // select loggedin users detail
 $res=mysql_query("SELECT * FROM users WHERE userId=".$_SESSION['user']);
 $userRow=mysql_fetch_array($res);
?>

<!DOCTYPE HTML>
<html>

<head>

  <title>
    RAILabs MAFC
  </title>

  <style>

    .overlays {
      color: gold;
    }

    .selected {
      background-color:skyblue;
    }

    body {
      margin:auto;
      background-color:#0C090A;
      color:#737CA1;
    }

    .btn {
      width:9%;
    }

    .btn1 {
      width: 9%;
    }

    .btn2 {
      width:49%;
      line-height: 200%;
    }

    .btn3 {
      margin-right: .5%;
    }

    #myProgress {
      position: relative;
      height: 2%;
      background-color: grey;
    }

    #myBar {
      position: absolute;
      width: 10%;
      height: 100%;
      background-color: #737CA1;
    }

    #label {
      text-align: center;
      line-height: 100%;
      color: white;
    }

    #title_stuff {
      color:#737CA1;
      font-size: 200%;
    }

    #title_stuff a {
      color:#737CA1;
      font-size: 100%;
      margin-left: 60.5%;
    }
  </style>

</head>

<body>

<!-- setting up the html division to be container for cornstone enabled elements  -->
<div class="container">

  <br>

  <div id="title_stuff"> MAFC Duke RAILabs <a href="logout.php?logout">Sign Out</a> </div>
  <!-- This is an example webpage to be utilized for 2AFC observer studies. -->

  <br>

  <!-- Set up javascript buttons for user selection of "correct" image -->
  <button id="choose1" class="btn2" style="margin-right: 1%;" onclick="write_to_db()">CHOOSE IMAGE 1</button>
  <button id="choose2" class="btn2" onclick="write_to_db2()">CHOOSE IMAGE 2</button>

  <br>
  <br>

  <!-- We disable mouse selection on the top most div -->
  <div style="position:relative; display:inline-block; margin-right:1%;"
    class="cornerstone-enabled-image"
    oncontextmenu="return false"
    unselectable='on'
    onselectstart='return false;'
    onmousedown='return false;'>

    <!-- dicom image (left)-->
    <div id="image1"
      style="top:0px;left:0px; position:absolute">
    </div>

    <!-- image overlay -->
    <div id="topleft" class="overlays" style="position: absolute;top:0px; left:0px">
      Patient Name
    </div>

    <!-- image overlay -->
    <div id="topright" class="overlays" style="position: absolute;top:0px; right:0px">
      Image
    </div>

    <!-- image overlay -->
    <div id="bottomright" class="overlays" style="position: absolute;bottom:0px; right:0px">
      Zoom:
    </div>

    <!-- image overlay -->
    <div id="bottomleft" class="overlays" style="position: absolute;bottom:0px; left:0px">
      WW/WC:
    </div>

  </div>

  <!-- We disable mouse selection on the top most div -->
  <div style="position:relative; display:inline-block;"
    class="cornerstone-enabled-image"
    oncontextmenu="return false"
    unselectable='on'
    onselectstart='return false;'
    onmousedown='return false;'>

    <!-- dicom image (right)-->
    <div id="image2"
      style="top:0px;left:0px; position:absolute">
    </div>

    <!-- image overlay -->
    <div id="topleft2" class="overlays" style="position: absolute;top:0px; left:0px">
      Patient Name
    </div>

    <!-- image overlay -->
    <div id="topright2" class="overlays" style="position: absolute;top:0px; right:0px">
      Image
    </div>

    <!-- image overlay -->
    <div id="bottomright2" class="overlays" style="position: absolute;bottom:0px; right:0px">
      Zoom:
    </div>

    <!-- image overlay -->
    <div id="bottomleft2" class="overlays" style="position: absolute;bottom:0px; left:0px">
      WW/WC:
    </div>

  </div>

  <br>

  <div class="cont" style="width:inherit;">
    <!-- Set up javascript buttons for window level presets -->
    <button id="softTissue" class="btn" >Soft Tissue</button>
    <button id="lung" class="btn" >Lung</button>
    <button id="bone" class="btn" >Bone</button>
    <!-- <button id="invert" class="btn_invert" style="width:9%;">Invert</button> -->
    <!-- <button id="interpolation" class="btn_interp selected" style="width:9%;">Interpolation</button> -->
    <button id="softTissue2" class="btn1" style="margin-left: 22.5%;">Soft Tissue</button>
    <button id="lung2" class="btn1" >Lung</button>
    <button id="bone2" class="btn1" >Bone</button>
    <!-- <button id="invert2" class="btn_invert"  style="width:9%;">Invert</button> -->
    <!-- <button id="interpolation2" class="btn_interp selected" style="width:9%;">Interpolation</button> -->
  </div>

  <br>

  <input id="ww" class="btn3" type="checkbox" checked="" > Synchronize WW/WC
  <input id="zp" class="btn3" type="checkbox" checked="" style="margin-left: 1%;"> Synchronize Zoom/Pan
  <input id="pos" class="btn3" type="checkbox" checked="" style="margin-left: 1%;"> Synchronize Slice

  <br>
  <br>

  <div id="myProgress">
    <div id="myBar">
      <div id="label">10%</div>
    </div>
  </div>


Image Load Progress

</div>

</body>

<!-- cornerstone depends on jQuery so it must be loaded first-->
<script src="./js/jquery.min.js"></script>

<!-- include the cornerstone library -->
<script src="./js/cornerstone.js"></script>
<script src="./js/cornerstoneMath.js"></script>

<!-- include the dicomParser library as the WADO image loader depends on it -->
<script src="./js/dicomParser.min.js"></script>

<!-- Add support for JPEG2000 Compressed dicoms and JPEG-LS -->
<script src="./js/libopenjpeg.js"></script>
<script src="./js/libCharLS.js"></script>

<!-- include the cornerstoneWADOImageLoader library -->
<script src="./js/cornerstoneWADOImageLoader.js"></script>

<!-- include DICOM UIDS -->
<script src="./js/uids.js"></script>

<!-- include javascript tools for wwwc, pan/zoom, stacks -->
<script src="./js/cornerstoneTools.js"></script>

<script>

var study_index = 1; // study progress index
//check dynamic database to see where they left off, or are just beginning

// number of stack slices (must be constant across all image datasets)
var slices = 5;
//read from pre-filled database

// scale the zoom property to account for reconstructed field of view differences
var size_ratio = 1;
//Not implemented in this iteration

// set-up some dummy variables to be used to load images later on
var imageIds1 = [
    'example://1',
];

var imageIds2 = [
    'example://1',
];

var stack = {
    currentImageIdIndex : 0,
    imageIds: imageIds1
};

var stack2 = {
    currentImageIdIndex : 0,
    imageIds: imageIds2
};

// enablee the html divs to hold cornerstone dicom images
var element = cornerstone.enable(document.getElementById('image1'));
var element2 = cornerstone.enable(document.getElementById('image2'));

// create listeners for updated images
$(element).on("CornerstoneImageRendered", onViewportUpdated);
$(element2).on("CornerstoneImageRendered", onViewportUpdated2);

// create listeners for new images (i.e. images within stack)
$(element).on("CornerstoneNewImage", onNewImage);
$(element2).on("CornerstoneNewImage", onNewImage2);

// create synchronizers for wwwc and pan/zoom across the two displayed images
var synchronizer = new cornerstoneTools.Synchronizer("CornerstoneImageRendered", cornerstoneTools.panZoomSynchronizer);
var synchronizer2 = new cornerstoneTools.Synchronizer("CornerstoneImageRendered", cornerstoneTools.wwwcSynchronizer);
var synchronizer3 = new cornerstoneTools.Synchronizer("CornerstoneNewImage", cornerstoneTools.stackImageIndexSynchronizer);

//initial run once webpage is ready
$(document).ready(loadAndDisplayImages())

// ajust all displayed elements to fit current screen size
resizeMain();

// define all inline functions
function loadAndDisplayImages() {

  // check current study index
  var image_number = (2*study_index) - 1;

  //load dicom images (Instance_*) within image_number* folder
  for (i = 0; i < slices; i++) {
    imageIds1[i] = 'wadouri:http://colab-sbx-245.oit.duke.edu/all_images/image' + image_number + '/' + 'Instance_' + (i+1) + '.dcm';
  };

  // update stack info
  stack = {
      currentImageIdIndex : Math.floor(slices/2), // Middle slice as default
      imageIds: imageIds1
  };

  // function used to display images
  loadAll(imageIds1).then(function(image) {

    // log full image data to browser log
    console.log(image);

    //display image
    cornerstone.displayImage(element, image);

    // Set the stack as tool state
    cornerstoneTools.addStackStateManager(element, ['stack']);
    cornerstoneTools.addToolState(element, 'stack', stack);

    // set image overlay properites and values
    var viewport = cornerstone.getViewport(element);
    $('#bottomright').text("Zoom: " + viewport.scale.toFixed(2) + "x");
    $('#bottomleft').text("WW/WC:" + Math.round(viewport.voi.windowWidth) + "/" + Math.round(viewport.voi.windowCenter));

    // enable and bind user input to mouse buttons and movement
    cornerstoneTools.mouseInput.enable(element);
    cornerstoneTools.mouseWheelInput.enable(element);
    cornerstoneTools.wwwc.activate(element, 1);
    cornerstoneTools.pan.activate(element, 2);
    cornerstoneTools.zoom.activate(element, 4);
    cornerstoneTools.stackScrollWheel.activate(element);
    //cornerstoneTools.stackPrefetch.enable(element);

    // add displayed image to synchronizers (wwwc and pan/zoom)
    synchronizer.add(element);
    synchronizer2.add(element);
    synchronizer3.add(element);

    // Add event handlers for the ww/wc presets
    $('#softTissue').click(function(e) {
      var viewport = cornerstone.getViewport(element);
      viewport.voi.windowWidth = 400;
      viewport.voi.windowCenter = 20;
      cornerstone.setViewport(element, viewport);
    });

    $('#lung').click(function(e) {
      var viewport = cornerstone.getViewport(element);
      viewport.voi.windowWidth = 1600;
      viewport.voi.windowCenter = -600;
      cornerstone.setViewport(element, viewport);
    });

    $('#bone').click(function(e) {
      var viewport = cornerstone.getViewport(element);
      viewport.voi.windowWidth = 2000;
      viewport.voi.windowCenter = 300;
      cornerstone.setViewport(element, viewport);
    });

  });

  // check current study index
  var image_number2 = (2*study_index);

  //load dicom images (Instance_*) within image_number* folder
  for (i = 0; i < slices; i++) {
    imageIds2[i] = 'wadouri:http://colab-sbx-245.oit.duke.edu/all_images/image' + image_number2 + '/' + 'Instance_' + (i+1) + '.dcm';
  };

  // update stack info
  stack2 = {
      currentImageIdIndex : Math.floor(slices/2), //Default to middle slice
      imageIds: imageIds2
  };

  // function used to display images
  loadAll2(imageIds2).then(function(image2) {

    // log full image data to browser log
    console.log(image2);

    //display image
    cornerstone.displayImage(element2, image2);

    // Set the stack as tool state
    cornerstoneTools.addStackStateManager(element2, ['stack']);
    cornerstoneTools.addToolState(element2, 'stack', stack2);

    // set image overlay properites and values
    var viewport2 = cornerstone.getViewport(element2);
    $('#bottomright2').text("Zoom: " + viewport2.scale.toFixed(2) + "x");
    $('#bottomleft2').text("WW/WC:" + Math.round(viewport2.voi.windowWidth) + "/" + Math.round(viewport2.voi.windowCenter));

    // enable and bind user input to mouse buttons and movement
    cornerstoneTools.mouseInput.enable(element2);
    cornerstoneTools.mouseWheelInput.enable(element2);
    cornerstoneTools.wwwc.activate(element2, 1);
    cornerstoneTools.pan.activate(element2, 2);
    cornerstoneTools.zoom.activate(element2, 4);
    cornerstoneTools.stackScrollWheel.activate(element2);
    //cornerstoneTools.stackPrefetch.enable(element2);

    // add displayed image to synchronizers (wwwc and pan/zoom)
    synchronizer.add(element2);
    synchronizer2.add(element2);
    synchronizer3.add(element2);

    // Add event handlers for the ww/wc presets
    $('#softTissue2').click(function(e) {
      var viewport = cornerstone.getViewport(element2);
      viewport.voi.windowWidth = 400;
      viewport.voi.windowCenter = 20;
      cornerstone.setViewport(element2, viewport);
    });

    $('#lung2').click(function(e) {
      var viewport = cornerstone.getViewport(element2);
      viewport.voi.windowWidth = 1600;
      viewport.voi.windowCenter = -600;
      cornerstone.setViewport(element2, viewport);
    });

    $('#bone2').click(function(e) {
      var viewport = cornerstone.getViewport(element2);
      viewport.voi.windowWidth = 2000;
      viewport.voi.windowCenter = 300;
      cornerstone.setViewport(element2, viewport);
    });

  });

};

// load all stack images before displaying any single one
function loadAll(imageID) {

  for (i = 0; i < slices; i++) {

    cornerstone.loadAndCacheImage(imageID[i]);
    move(i,slices*2 -1);

  }

  return cornerstone.loadImage(imageID[Math.floor(slices/2)]);
}

function loadAll2(imageID) {

  for (i = 0; i < slices; i++) {

    cornerstone.loadAndCacheImage(imageID[i]);
    move(slices + i,slices*2 -1);

  }

  return cornerstone.loadImage(imageID[Math.floor(slices/2)]);
}

// define image update callback functions
function onViewportUpdated(e, data) {
    var viewport = data.viewport;
    $('#bottomleft').text("WW/WC: " + Math.round(viewport.voi.windowWidth) + "/" + Math.round(viewport.voi.windowCenter));
    $('#bottomright').text("Zoom: " + viewport.scale.toFixed(2) + "x");
};

function onViewportUpdated2(e, data) {
    var viewport = data.viewport;
    $('#bottomleft2').text("WW/WC: " + Math.round(viewport.voi.windowWidth) + "/" + Math.round(viewport.voi.windowCenter));
    $('#bottomright2').text("Zoom: " + viewport.scale.toFixed(2) + "x");
};

// define new image callback functions (i.e. slice scrolling)
function onNewImage(e, data) {
  var newImageIdIndex = stack.currentImageIdIndex;
  // Populate the current slice span
  var currentValueSpan = document.getElementById("topright");
  currentValueSpan.textContent = "Image " + (newImageIdIndex + 1) + "/" + imageIds1.length;
}

function onNewImage2(e, data) {
  var newImageIdIndex = stack2.currentImageIdIndex;
  // Populate the current slice span
  var currentValueSpan = document.getElementById("topright2");
  currentValueSpan.textContent = "Image " + (newImageIdIndex + 1) + "/" + imageIds2.length;
}

// define user image mafc selection callback functions
function write_to_db(){
  // step to the next image datasets
  study_index = study_index + 1;
  // post selection to server side database
  $.ajax({
    type: 'POST',
    url: './db/script.php',
  });

  //TODO add code to clear cache
  cornerstone.imageCache.purgeCache();

  //start the next mafc user selection
  loadAndDisplayImages();
}

function write_to_db2(){
  // step to the next image datasets
  study_index = study_index + 1;
  // post selection to server side database
  $.ajax({
    type: 'POST',
    url: './db/script2.php',
  });

  //TODO add code to clear cache
  cornerstone.imageCache.purgeCache();

  //start the next mafc user selection
  loadAndDisplayImages();
}

// Resize elements according to current browser display window size
function resizeMain() {
  var height = $(window).height();
  var width = $(window).width();
  var new_size = 0;

  //find limiting dimension
  if (height <= (width*.5)) {
    new_size = height*2;
  } else {
    new_size = width;
  }

  $('body').height(new_size * .45);
  $('body').width(new_size * .7);

  $('.container').height($('body').height());
  $('.container').width($('body').width());

  $('.cornerstone-enabled-image').height(Math.floor($('.container').width() * .49));
  $('.cornerstone-enabled-image').width(Math.floor($('.container').width() * .49));

  $('#image1').height($('.cornerstone-enabled-image').width());
  $('#image1').width($('.cornerstone-enabled-image').width());

  $('#image2').height($('.cornerstone-enabled-image').width());
  $('#image2').width($('.cornerstone-enabled-image').width());

  $('#myProgress').width($('.cornerstone-enabled-image').width());
  $('#title_stuff').width($('body').width());

  cornerstone.resize(element, true);
  cornerstone.resize(element2, true);

  // update size of buttons to match current browser display size
  var fontSize = $('.container').width() * 0.009; // 9% of container width
  $('.btn').css('font-size', fontSize);
  $('.btn1').css('font-size', fontSize);
  $('.btn_invert').css('font-size', fontSize);
  $('.btn_interp').css('font-size', fontSize);
  $('.btn2').css('font-size', fontSize);
  $('body').css('font-size', fontSize);
  $('#label').css('font-size', fontSize);

}

// Call resize main on window resize
$(window).resize(function() {
    resizeMain();
});

// define other useful image display parameter functions (button activated)
$('#invert').click(function (e) {
    var viewport = cornerstone.getViewport(element);
    if (viewport.invert === true) {
        viewport.invert = false;
    } else {
        viewport.invert = true;
    }
    cornerstone.setViewport(element, viewport);
});

$('#interpolation').click(function (e) {
    var viewport = cornerstone.getViewport(element);
    if (viewport.pixelReplication === true) {
        viewport.pixelReplication = false;
    } else {
        viewport.pixelReplication = true;
    }
    cornerstone.setViewport(element, viewport);
});

$('#invert2').click(function (e) {
    var viewport = cornerstone.getViewport(element2);
    if (viewport.invert === true) {
        viewport.invert = false;
    } else {
        viewport.invert = true;
    }
    cornerstone.setViewport(element2, viewport);
});

$('#interpolation2').click(function (e) {
    var viewport = cornerstone.getViewport(element2);
    if (viewport.pixelReplication === true) {
        viewport.pixelReplication = false;
    } else {
        viewport.pixelReplication = true;
    }
    cornerstone.setViewport(element2, viewport);
});

$('#ww').click(function() {
    if (ww.checked){
      synchronizer2.add(element2);
      ww.checked = true;
      cornerstone.updateImage(element2);
      $('.btn').removeClass('selected');
      id_name = $('.btn1.selected').attr('id');
      split = id_name.match(/[a-zA-Z]+|[0-9]+/g);
      only_letters = "#" + split[0];
      $(only_letters).addClass('selected');
      if ($('#invert2').hasClass('selected')){
        $('#invert').addClass('selected');
      }
    } else {
      synchronizer2.remove(element2);
      ww.checked = false;
    }
});

$('#zp').click(function() {
    if (zp.checked){
      synchronizer.add(element2);
      zp.checked = true;
      cornerstone.updateImage(element2);
    } else {
      synchronizer.remove(element2);
      zp.checked = false;
    }
});

$('#pos').click(function() {
    if (pos.checked){
      synchronizer3.add(element2);
      pos.checked = true;
      diff = stack2.currentImageIdIndex - stack.currentImageIdIndex;
      cornerstoneTools.scroll(element, diff);
    } else {
      synchronizer3.remove(element2);
      pos.checked = false;
    }
});

$('.btn').on('click', function(){
    if (ww.checked) {
      $('.btn').removeClass('selected');
      $('.btn1').removeClass('selected');
      this_id = this.id;
      split = this.id.match(/[a-zA-Z]+|[0-9]+/g);
      only_letters = "#" + split[0];
      sync_id = only_letters + 2;
      $(only_letters).addClass('selected');
      $(sync_id).addClass('selected');
      if ($('#invert').hasClass('selected')){
        $('#invert2').addClass('selected');
      }
    } else {
      $('.btn').removeClass('selected');
      $(this).addClass('selected');
    }
});

$('.btn1').on('click', function(){
    if (ww.checked) {
      $('.btn').removeClass('selected');
      $('.btn1').removeClass('selected');
      this_id = this.id;
      split = this.id.match(/[a-zA-Z]+|[0-9]+/g);
      only_letters = "#" + split[0];
      sync_id = only_letters + 2;
      $(only_letters).addClass('selected');
      $(sync_id).addClass('selected');
      if ($('#invert2').hasClass('selected')){
        $('#invert').addClass('selected');
      }
    } else {
      $('.btn1').removeClass('selected');
      $(this).addClass('selected');
    }
});

$('.btn_invert').on('click', function(){
    if (ww.checked) {
      $('#invert').toggleClass('selected');
      $('#invert2').toggleClass('selected');
    } else {
      $(this).toggleClass('selected');
    }
});

$('.btn_interp').on('click', function(){
  $(this).toggleClass('selected');
});

function move(current_index,total) {
    var elem = document.getElementById("myBar");
    var width = Math.floor((current_index/total)*100);
    elem.style.width = width + '%';
    document.getElementById("label").innerHTML = width + '%';
}

</script>
</html>
<?php ob_end_flush(); ?>
