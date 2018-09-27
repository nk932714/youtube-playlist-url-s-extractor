<?php
    /***************   some Universal Constants here        **********************/
   /*************/     $script_name = "Youtube Playlist Extractor";         /********************/
  /*************/      $site_name = "";         /*******************/
 /*************/       $site_link = 'https://'.$_SERVER['SERVER_NAME'];  /******************/
/**************        END OF UNIVERSAL CONSTANTS           ******************/
$whomtosent     = $_POST["whomtosent"];
        
if (!isset($_POST['submit'])) { // if page is not submitted to itself echo the form
?>


<html>
<head>
      <title><?php echo $script_name ?> Online Script</title>
      <link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>
                <center><font color="red" size="5"><strong><b> <?php echo $script_name ?> online Script</b></strong></font><br>
                <center><font color="green" size="2.5"><strong>ex:-https://www.youtube.com/watch?v=dFKhWe2bBkM&list=<b><font color=magenta size=2.5>PLjwBf9QEIO95j5kXIm9XAS_NAiu9_NDmv</b></strong></font><br></center>

                <form method="post" action="<?php  echo $PHP_SELF; ?>">
                         <input type="text" class="text" maxlength="99" name="whomtosent" placeholder="Playlist code"><br><br>
						 
                         <input type="submit" name="submit" class="button" value="Submit">			
                </form></center>


                       <center><font class="heading"><strong><font color="red" size="5">!!!<a href="<?php echo $site_link?>" style="text-decoration:none">  <?php echo $site_name ?>  </a>!!!</font><br>
                       <font color="#FF1493" size="5">-- <a href="<?php echo $site_link?>/contact" style="text-decoration:none">Contact us</a>--<br><br>
                       </font></strong></center>
               

<?php

     } //!isset($_POST['submit'])  closing of this



else {



// orignal code

         
$replaces = [['|GopherCon 2015: |',''],['|GopherCon 2014 |',''],['|GopherCon 2016: |','']];
$format = 'markdown'; // markdown, json, html
// $list = 'PL2ntRZ1ySWBcD_BiJiDJUcyrb2w3bTulF'; // 2014
// $list = 'PL2ntRZ1ySWBf-_z-gHCOR2N156Nw930Hm'; // 2015
// $list = 'PL2ntRZ1ySWBdliXelGAItjzTMxy2WQh0P'; // 2016
// $list = 'PLiJoT0I9mYpFe5f6ht5BifkNix0coGF1t'; //2018
$list = $whomtosent;
$base = 'https://www.youtube.com';

// retrieve

$url = $base.'/playlist?list='.$list;
$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
);  
$content = file_get_contents($url, false, stream_context_create($arrContextOptions));
$count = preg_match_all('|<tr class="pl-video([^>]*)>(.*)</tr>|msiU',$content,$matches);
$videos = [];
for($i=0;$i<$count;$i++) {
  if (!preg_match('|<a([^>]*)>([^<]*)</a>|msi',$matches[0][$i],$link)) continue;
  if (!preg_match('|href="(/watch[^&]*)&amp|i',$link[1],$href)) continue;
  if (!preg_match('|<td class="pl-video-time"([^>]*)>(.*)</td>|msiU',$matches[0][$i],$time)) continue;
  $href = $base.trim($href[1]);


  $title = trim($link[2]);
  $time = trim(strip_tags($time[2]));
  foreach ($replaces as $replace) {
    $title = preg_replace($replace[0],$replace[1],$title);
  }
  $videos[] = (object) compact('href','title','time');
}

// print

if ($format=='json') {
  echo json_encode($videos);
} else if ($format=='html') {
  foreach ($videos as $video) {
    extract((array)$video);
    echo "<a href=\"$href\">$title</a> [$time]\n";
  }
} else if ($format=='markdown') {
  foreach ($videos as $video) {
    extract((array)$video);
 //   echo html_entity_decode("- [$title]($href) [$time]\n");
 //echo html_entity_decode("$href\n<br>");
   //     $sata = "Please copy the List Below manually.";
 //      echo $href."<br>";
    $sata .= $href.PHP_EOL;    // PHP_EOL tag is used to create new line where backslash n and br tag doesn't work
}
}
       //echo $sata;

         echo "<center><font class=heading><strong><font color=red size=5>".$script_name." Online Script</font><br><br><br>";          //heading
      //  echo "<span class='firstx'>".$sata."</span><br><br><br><br>";





// to get more then 100 videos


 $re2 = '/\/browse(.*?)"/';  //it will display the further link
            $count2 = preg_match($re2, $content, $matches2);
           //echo $count2;
      $url2 = "https://www.youtube.com/browse".$matches2[1];
if ($count2 == "1") {
    
                     $content2 = file_get_contents($url2, false, stream_context_create($arrContextOptions));
                      $re3 = '/\/watch\?v=(.*?)\\\\/';
                       $result3 = preg_match_all($re3, $content2, $matches3);
                     // print_r($matches3);
                      $result4 = array_unique($matches3[0]);
                       // var_dump($result4);
                      // print_r($result4);
                      //echo $content2;
                        $result5 = implode(PHP_EOL,$result4);      // PHP_EOL tag is used to create new line where backslash n and br tag doesn't work
                        $result6 = str_replace('/watch','https://www.youtube.com/watch',$result5);
                        $result6 = str_replace('\\','',$result6);
                     // echo $sata."<br>";
                     // echo $result6;
                     $full_list = $sata.$result6;
                     // echo $full_list;
} else {
    $full_list=$sata;
}




     
?>
<html>
<head>
<style>#snackbar { visibility: hidden; min-width: 250px; margin-left: -125px; background-color: #333; color: #fff; text-align: center; border-radius: 2px; padding: 16px; position: fixed; z-index: 1; left: 50%; bottom: 30px; font-size: 17px;}#snackbar.show { visibility: visible; -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s; animation: fadein 0.5s, fadeout 0.5s 2.5s;}@-webkit-keyframes fadein { from {bottom: 0; opacity: 0;} to {bottom: 30px; opacity: 1;}}@keyframes fadein { from {bottom: 0; opacity: 0;} to {bottom: 30px; opacity: 1;}}@-webkit-keyframes fadeout { from {bottom: 30px; opacity: 1;} to {bottom: 0; opacity: 0;}}@keyframes fadeout { from {bottom: 30px; opacity: 1;} to {bottom: 0; opacity: 0;}}</style>
</head>
<!-- <input type="text" value="<?php echo $sata; ?>" id="myInput"> -->
<textarea rows="4" cols="50" name="comment" id="myInput" form="myInput"><?php echo $full_list; ?></textarea>
<button onclick="myFunction()">Copy text</button>
<div id="snackbar">Text has been copied</div>
<script>
function myFunction() {
  var copyText = document.getElementById("myInput");
   var x = document.getElementById("snackbar")
    x.className = "show";
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
  copyText.select();
  document.execCommand("Copy");
// alert("Copied the text: \n" + copyText.value);
}
</script>

<?php                                                                 //Echo MAIN data
          
             $final_count = $count+($result3/2);
  	// echo "<span class='firstx'>".$sataa."\n</span><br><br>"; 
           echo "<br><br><span class='firstx'><font color=#205813 size=4>Total ".$final_count." Videos Found.</font></span><br><br>";
         echo "<center><font color=magenta size=3><strong>------------------<a href=./> Go Back </a>------------------</strong></font></center>   
        <font color=#FF1493 size=5>-- <a href=".$site_link."/contact style=text-decoration:none>Contact us</a>--<br><br>
         </font></strong></center>";

           // Get the client ip address
             $ipaddress = $_SERVER['REMOTE_ADDR'];

                  //for log the data
                   $posts = file_get_contents("posts.txt");
                   $posts = "$whomtosent\n\n\n" . $posts;
                   $posts = "$ipaddress\n" . $posts;
                   file_put_contents("posts.txt", $posts);
    }
?>

<head><title> <?php echo $script_name ?> Online Script</title><link rel="stylesheet" type="text/css" href="style.css">
</head>	
</body>
</html>
