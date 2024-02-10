<!DOCTYPE html>
<html>
<head>
	<title>index</title>
  <link rel="stylesheet" type="text/css" href="assets/desplayer.css">

</head>
<body>
      <?php 
      session_start();
      $_SESSION['season']=1;
      $_SESSION['episode']=1;
        $conn = new mysqli('localhost','root','','only_series');
        mysqli_set_charset($conn,"utf8");
        $id=$_GET['id'];
        $q="SELECT * FROM series WHERE id=$id";
        $row=mysqli_fetch_array(mysqli_query($conn, $q));
      ?>
<div id="video_container">
  <div id="info">
    <div id="image_holder">
      <img src="images/<?php echo $row['title']?>/<?php echo $row['image']?>" id="image">
    </div>
    <div id="basic_info">
    


      <h3 id="title">title: <?php echo $row['title']?></h3>
      <h3>seasons number: <?php echo $row['seasons']?></h3>
      <h3>episodes number: <?php echo $row['episodes']?></h3>
    </div>
  </div>
  <div>
    <?php
$directory = "images/".$row['title']."/seasons/s".$_SESSION['season'];

// Get the list of files and directories in the specified directory
$files = scandir($directory);

// Remove . and .. from the list, if present
$files = array_diff($files, array('', ''));
$index = 2+$_SESSION['episode'];
// Loop through each file in the directory

  ?>
 <video id="video" src="<?php echo $directory."/".$files[$index]?>" width="800px" height="500px" controls onchange="pause()">



  
  </div>
  </video>
</div>
	<br>
<div style="display:flex;justify-content: center;width: 500px;">


<div id="seasons_episodes">
  <select>
    <?php
$directory = "images/".$row['title']."/seasons";

// Initialize counter
$file_count = 0;

// Open a directory handle
$dir_handle = opendir($directory);

// Check if directory handle is valid
if ($dir_handle) {
    // Loop through each file in the directory
    while (($file = readdir($dir_handle)) !== false) {
        // Exclude . and .. directories
        if ($file != "." && $file != "..") {
            // Increment file count
            $file_count++;

            ?> 
               <option>season <?php echo $file_count?></option>
        <?php 
      }

    }
    // Close the directory handle
    closedir($dir_handle);

}
?>



  </select>
  <select>
       <?php
$directory = "images/".$row['title']."/seasons/s".$_SESSION['season'];

// Initialize counter
$episode_number = 0;

// Open a directory handle
$dir_handle = opendir($directory);

// Check if directory handle is valid
if ($dir_handle) {
    // Loop through each file in the directory
    while (($file = readdir($dir_handle)) !== false) {
        // Exclude . and .. directories
        if ($file != "." && $file != "..") {
            // Increment file count
            $episode_number++;

            ?> 
    <option>episode <?php echo $episode_number?></option>
<?php 
      }

    }
    // Close the directory handle
    closedir($dir_handle);

}
?>
  </select>
</div>
</div>

<div id="buttons">
  <button onclick="alert('this option is disabled for the moemnt')">download</button>
  <button onclick="document.getElementById('dp_section').style.display='block'">download a part</button>
</div>


<div id="dp_section">
  <div>
  <h3>starting time:
  <input type="number" style="width:80px" id="starting_houres" placeholder="houres">
  <input type="number" style="width:80px" id="starting_minutes" placeholder="minutes">
  <input type="number" style="width:80px" id="starting_seconds" placeholder="seconds">
  </h3>
  <h3>ending time:
  <input type="number" style="width:80px" id="ending_houres" placeholder="houres">
  <input type="number" style="width:80px" id="ending_minutes" placeholder="minutes">
  <input type="number" style="width:80px" id="ending_seconds" placeholder="seconds">
  </h3>
  </div>
  <div style="display:flex;justify-content: center;">
  <button id="segmenting"onclick="test()"  disabled>Segment</button>
  </div>
</div>


<br> 


</body>
</html>
<script>
var valide=0;

document.getElementById('starting_houres').addEventListener('input', function(event) {
  var value=document.getElementById('starting_houres').value
  if(value!='' && valide==0){
  document.getElementById('segmenting').disabled=!document.getElementById('segmenting').disabled;
  valide++;
}else if((value=='' || value==0) && valide!=0         ){
  document.getElementById('segmenting').disabled=!document.getElementById('segmenting').disabled;
  valide--;
}
});
document.getElementById('starting_minutes').addEventListener('input', function(event) {
  var value=document.getElementById('starting_minutes').value
  if(value!='' && valide==0){
  document.getElementById('segmenting').disabled=!document.getElementById('segmenting').disabled;
  valide++;
}else if((value=='' || value==0) && valide!=0         ){
  document.getElementById('segmenting').disabled=!document.getElementById('segmenting').disabled;
  valide--;
}
});
document.getElementById('starting_seconds').addEventListener('input', function(event) {
  var value=document.getElementById('starting_seconds').value
  if(value!='' && valide==0){
  document.getElementById('segmenting').disabled=!document.getElementById('segmenting').disabled;
  valide++;
}else if((value=='' || value==0) && valideS!=0){
  document.getElementById('segmenting').disabled=!document.getElementById('segmenting').disabled;
  valide--;
}
});




	function test() {
    if(!valid_starting_time() || !valid_ending_time()){
      alert('the timing you gave is way of the duration of the video');
      return;
    }


    var cuting_time=calculate_starting_time();

    if(calculate_ending_time()<calculate_starting_time()){
      alert('starting time is greater than ending time');
      return;
    }
    var duration=calculate_ending_time()-calculate_starting_time();
    
    var src=document.getElementById('video').getAttribute("src");

  
  var url ='http://127.0.0.1:3000/process-variables';
  url+='?variable1=' + encodeURIComponent(src);
  url+='&cuting_time=' + encodeURIComponent(cuting_time);
  url+='&duration=' + encodeURIComponent(duration);
  url+='&src=' + encodeURIComponent(src);
  
    var xhr = new XMLHttpRequest();
            xhr.open('GET', url, true);
            xhr.setRequestHeader('Content-Type', 'application/json');

            xhr.onload = function () {
                if (xhr.status === 200) {
                    console.log('Variables sent successfully');
                } else {
                    console.error('Error sending variables');
                }
            };

            xhr.send();
  }





  /*--------------------------------------------------------------------------------------*/

  function valid_starting_time()
  {
    var duration=document.getElementById('video').duration;

    if(duration<calculate_starting_time()){
      return false;
    }else{
      return true;
    }

  }

  function calculate_starting_time(){
    var houre=document.getElementById('starting_houres').value;
    var minute=document.getElementById('starting_minutes').value;
    var second=document.getElementById('starting_seconds').value;


    if (houre=='') {
      houre=0;
    }
    if (minute=='') {
      minute=0;
    }

if (second=='') {
      second=0;
    }


    var totat_seconds=parseFloat(second)+(parseFloat(minute)*60)+(parseFloat(houre)*3600);
    return totat_seconds;
  }

  function valid_ending_time()
  {
    var duration=document.getElementById('video').duration;

    if(duration<calculate_ending_time()){
      return false;
    }else{
      return true;
    }

  }

  function calculate_ending_time(){
    var houre=document.getElementById('ending_houres').value;
    var minute=document.getElementById('ending_minutes').value;
    var second=document.getElementById('ending_seconds').value;


    if (houre=='') {
      houre=0;
    }
    if (minute=='') {
      minute=0;
    }

if (second=='') {
      second=0;
    }


    var totat_seconds=parseFloat(second)+(parseFloat(minute)*60)+(parseFloat(houre)*3600);
    return totat_seconds;
  }




</script>