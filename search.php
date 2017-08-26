<!DOCTYPE HTML>  
<html>
<head>
<style>
    #fom{
          height: 240px;
        background-color: lightgrey;
        width: 600px;
    }
    .left{
        text-align: left;
    }
    #hide{
        display: none;
    }
    #detail{
        display:none;
    }
    .format{
        background-color: lightgrey;
        align-content: center;
    }
    .center{
        text-align: center;
    }
    .border{
        border-collapse: collapse;
        border: 1px solid black;
        border: '1';
    }
    .bold{
        font-weight: bold;
    }
    .hide{
        display:none;
    }
    span{
        color: blue;
        text-decoration: underline; 
    }
    span:hover{
        cursor: pointer;
    }
</style>
<script>
    function func() {
    var a = document.getElementById("plce");
    var b = document.getElementById("hide");
    if(a.selected)
        b.style.display="block";
    else
        b.style.display="none";
    }
    function clearr() {   
            document.getElementById("tab").style.display="none";
            document.getElementById("fom").reset();
        }
    function detail(id){
        var p=document.getElementById("tab1");
        p.style.display="none";
        var a=id+'albhead';
        var b=id+'phead';
        document.getElementById(a).style.display="block";
        document.getElementById(b).style.display="block";
    }
    function albumlink(id){
        var a=id+'albhead';
        var b=id+'alb';
        var c=id+'pos';
        document.getElementById(a).style.display="block";
        document.getElementById(c).style.display="none";
        if(document.getElementById(b).style.display=="block")
            document.getElementById(b).style.display="none";
        else
            document.getElementById(b).style.display="block";
    }
    
    function pslink(id){
        var a=id+'phead';
        var b=id+'pos';
        var c=id+'alb';
        document.getElementById(c).style.display="none";
        document.getElementById(a).style.display="block";
        if(document.getElementById(b).style.display=="block")
            document.getElementById(b).style.display="none";
        else
            document.getElementById(b).style.display="block";
    }
</script>
</head>
<body>
   
<center><form id="fom" method="post">

    <h2><i><center>Facebook Search</center></i></h2>
    <hr>
 <div class="left"> &nbsp;Keyword <input type="text" name="keyword" required>
    <br>
  &nbsp;Type:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="type" onclick="func()">
  <option value="user">Users</option>
  <option value="page">Pages</option>
  <option value="event">Events</option>
  <option value="place" id="plce">Places</option>
  <option value="group">Groups</option>    
    </select><br>
    <div id="hide">
    Location&nbsp;<input type="text" name="location"> Distance (meters) &nbsp;<input type="text" name="distance"><br/>
        <br>
    </div>
<br><br>
     
  &nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Search"><input type="button" name="Clear" value="Clear" onclick="clearr()"> </div>
    </form></center>
    <br><br><br>
    
    
    <?php
    
    session_start();
    require_once __DIR__ . '/php-graph-sdk-5.0.0/src/Facebook/autoload.php';
    $fb = new Facebook\Facebook([
      'app_id' => '1000772346719718',
      'app_secret' => 'f1c8dd2237c6d3232c76ec066719bd4a',
      'default_graph_version' => 'v2.5'
     ]);

    $fb->setDefaultAccessToken ('EAAOOMng6LeYBAHcoq9SkZAB5MnWH7MHfx8Y5NVgE8swdXQd5YPlyFZCxkXFAa5eQqhfsCnHlOZBihhwV3SlNvUzsOvJbO5dtSpojc8P85TEWVHJo4AAIgxqQzHFZANsNfJsK0snBw91w9IjKgrqk7ZCvM2YUirFoZD');
    
            try {
                    $response = $fb->get('/me');
                    $usernode=$response->getGraphUser();
                } 
            catch(Facebook\Exceptions\FacebookResponseException $e) {
          // When Graph returns an error
                  echo 'Graph returned an error: ' . $e->getMessage();
                  exit;
            } 
            catch(Facebook\Exceptions\FacebookSDKException $e) {
          // When validation fails or other local issues
                  echo 'Facebook SDK returned an error: ' . $e->getMessage();
                  exit;
            }
    
    if(isset ($_POST["submit"])){
        error_reporting(E_ALL ^ E_NOTICE);
        $keyword=trim($_POST["keyword"]);
        $type=$_POST["type"];
        $location=$_POST["location"];
        $distance=$_POST["distance"];
        $s=$fb->get('/search?q='.$keyword.'&type='.$type.'&fields=id,name,picture.width(2000).height(2000),albums.limit(5){name,photos.limit(2){name,picture}},posts.limit(5)');
        $s=$s->getDecodedBody();
        if($s["data"]==""){
            echo "<center><table class='table1' width='600px' border='1'><tr><td>No records have been found</td></tr></table></center>";
        }
        else{
        if($type=="event"){
            $sevent=$fb->get('/search?q='.$keyword.'&type='.$type.'&fields=id,name,picture.width(2000).height(2000),place');
            $sevent=$sevent->getDecodedBody();
            $sevent=$sevent["data"];
            echo "<div id='tab'>";
            if($sevent[0]==''){
                echo "<center><table class='format' width='600px' height='15px' border='1'><tr><td class='center'>No Records Present</td></tr></table></center>";
            }
            else{
                echo "<center><table class='format border' border='1' width='600px' height='15px'><th class='bold center'>ProfilePhoto<td class='center bold'>Name</td><td class='bold'>Place</td></th>";
                foreach($sevent as $k){
                    echo "<tr><td><a href='".$k['picture']['data']['url']."' target='_blank'><img src='".$k['picture']['data']['url']."' width='25px' height='25px'></img></a></td><td>".$k['name']."</td><td>".$k['place']['name']."</td></tr>";
                }
                echo "</table></center>";
            }
            echo "</div>";
        }
          
        $s=$s["data"];
        
        if($type=="page"||$type=="group"||$type=="user"){
            
            echo "<div id='tab'>";
            if($s[0]==''){
                echo "<center><table class='table1' width='600px' border='1'><tr><td'><center>No records have been found</center></td></tr></table></center>";
            }
            else{
                echo "<center><table class='border format' border='1' id='tab1' width='600px' height='15px'><tr><th>Profile Photo</th><th>Name</th><th>Details</th></tr>";
                foreach($s as $k){
                    echo "<tr><td><a href='".$k['picture']['data']['url']."' target='_blank'><img src='".$k['picture']['data']['url']."' height='25px' width='25px'></img></a></td><td>".$k['name']."</td><td><span id='details' onclick='detail(".$k['id'].");'>Details</span></td></tr>";
                }
                echo "</table></center>";
                
            foreach($s as $k){
                $a='alb';
                $b=$k['id'].$a;
                
                $aa='albhead';
                $bb=$k['id'].$aa;
                
                if($k['albums']['data']==''){
                    echo "<div id='".$bb."' style='display:none'>";
                    echo "<center><table class='border format' width='600px' border='1'><tr><td><center>No Albums have been found</center></td></tr></table></center><br></div>";
                }
                else if($k['albums']['data']!=''){
                    foreach($s as $k){
                        $aa='albhead';
                        $bb=$k['id'].$aa;
                        echo "<div id='".$bb."' style='display:none'>";
                        echo "<center><table class='border format' width='600px' border='1'><tr><td><span onclick='albumlink(".$k['id'].");'><center>Albums</center></span></td></tr></table></center></div>";
                        
                    }
                    foreach($s as $k){
                        $a='alb';
                        $b=$k['id'].$a;
                        $p='photos';
                        $photos=$k['id'].$p;
                        
                        echo "<div id='".$b."' style='display:none'>";
                        for($i=0;$i<5;$i++){
                            echo "<center><table class='border format' width='600px' border='1'>";
                            if($k['albums']['data'][$i]['name']!=''){
                                echo "<tr><td>";
                                if($k['albums']['data'][$i]['photos']['data']==''){
                                    echo $k['albums']['data'][$i]['name'];
                                }
                                else{
                                    echo "<span onclick='ab(".$i.$photos.")'>".$k['albums']['data'][$i]['name']."</span>";
                                }
                                echo "</td></tr>";
                            }
                            echo "</table></center>";
                            echo "<div id='".$i.$photos."' style='display:block'>";
                            if($k['albums']['data'][$i]['photos']['data']==''){
                                echo "<center><table class='border format' width='600px' border='1'>";
                                if($k['albums']['data'][$i]['name']!=''){
                                    echo "<span onclick='ab(".$i.$photos.")'>".$k['albums']['data'][$i]['name']."</span></td></tr>";
                                   
                                }
                                echo "</table></center>";
                            }
                            else{
                                
                                echo "<center><table class='border format' width='600px' border='1'>";
                                echo "<tr><td>";
                                for($j=0;$j<2;$j++){
                                    if($k['albums']['data'][$i]['photos']['data'][$j]['picture']!=''){
                                        echo "<a href='".$k['albums']['data'][$i]['photos']['data'][$j]['picture']."' target='_blank'><img src='".$k['albums']['data'][$i]['photos']['data'][$j]['picture']."'></img></a>&nbsp&nbsp";
                                    }
                                }
                                echo "</td></tr></table></center>";
                            }
                            echo "</div>";
                        }
                        echo "</div>";
                    }
                }
            }
            foreach($s as $k){
                $po='pos';
                $posts=$k['id'].$po;
                
                $ppo='phead';
                $post=$k['id'].$ppo;
                
                if($k['posts']['data'][0]['message']!=''){
                    foreach($s as $k){
                        $ppo='phead';
                        $post=$k['id'].$ppo;
                        echo "<div id='".$post."' style='display:none'>";
                        echo "<br><center><table class='border format' width='600px' border='1'><tr><td><span onclick='pslink(".$k['id'].")'><center>Posts</center></span></td></tr></table></center></div>";
                    }
                    foreach($s as $k){
                        $po='pos';
                        $posts=$k['id'].$po;
                        
                        echo "<div id='".$posts."' style='display:none'>";
                        if($k['posts']['data'][0]['message']!=''){
                            echo "<center><table class='border format' width='600px' border='1'><tr><td><b>Message</b></td></tr>";
                            for($j=0;$j<5;$j++){
                                echo "<tr><td>".$k['posts']['data'][$j]['message']."</td></tr>";
                            }
                            echo "</table></center>";
                        }
                        else{
                            echo "<center><table class='border format' width='600px' border='1'><tr><td><center>No messages have been found</center></td><tr></table></center>";
                        }
                        echo '</div>';
                    }
                }
                else{
                    echo "<div id='".$post."' style='display:none'>";
                    echo "<center><table class='format border' width='600px' border='1'><tr><td><center>No posts have been found</center></td></tr></table></center>";
                    echo "</div>";
                }
            }
        }
        echo "</div>";
            
        }
        if($type=="place")
            {
               if(($location=='')&&($distance))
                {
                    echo "<div id=\"tab\">";
                    echo "<center><table class='border format'><tr><td>Distance specified without location or address</td></tr></table></center>";
                    echo "</div>";
                }
                else if(($location)&&($distance)){
                {
                    $l=str_replace(' ','+',$location);
                    $geo=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$l.'&sensor=false');
                    $fin= json_decode($geo);
                    $latitude = $fin->results[0]->geometry->location->lat;
                    $longitude = $fin->results[0]->geometry->location->lng;	
                }
                $s=$fb->get('/search?q='.$keyword.'&type=place&center='.$latitude.','.$longitude.'&distance='.$distance.'&fields=id,name,picture.width(2000).height(2000),albums.limit(5){name,photos.limit(2){name,picture}},posts.limit(5)');
                
                $s=$s->getDecodedBody();
                $s=$s["data"];
                echo "<div id='tab'>";
            if($s[0]==''){
                echo "<center><table class='table1' width='600px' border='1'><tr><td'><center>No records have been found</center></td></tr></table></center>";
            }
            else{
                echo "<center><table class='border format' border='1' id='tab1' width='600px' height='15px'><tr><th>Profile Photo</th><th>Name</th><th>Details</th></tr>";
                foreach($s as $k){
                    echo "<tr><td><a href='".$k['picture']['data']['url']."' target='_blank'><img src='".$k['picture']['data']['url']."' height='25px' width='25px'></img></a></td><td>".$k['name']."</td><td><span id='details' onclick='detail(".$k['id'].");'>Details</span></td></tr>";
                }
                echo "</table></center>";
                
            foreach($s as $k){
                $a='alb';
                $b=$k['id'].$a;
                
                $aa='albhead';
                $bb=$k['id'].$aa;
                
                if($k['albums']['data']==''){
                    echo "<div id='".$bb."' style='display:none'>";
                    echo "<center><table class='border format' width='600px' border='1'><tr><td><center>No Albums have been found</center></td></tr></table></center><br></div>";
                }
                else if($k['albums']['data']!=''){
                    foreach($s as $k){
                        $aa='albhead';
                        $bb=$k['id'].$aa;
                        echo "<div id='".$bb."' style='display:none'>";
                        echo "<center><table class='border format' width='600px' border='1'><tr><td><span onclick='albumlink(".$k['id'].");'><center>Albums</center></span></td></tr></table></center></div>";
                        
                    }
                    foreach($s as $k){
                        $a='alb';
                        $b=$k['id'].$a;
                        $p='photos';
                        $photos=$k['id'].$p;
                        
                        echo "<div id='".$b."' style='display:none'>";
                        for($i=0;$i<5;$i++){
                            echo "<center><table class='border format' width='600px' border='1'>";
                            if($k['albums']['data'][$i]['name']!=''){
                                echo "<tr><td>";
                                
                                if($k['albums']['data'][$i]['photos']['data']==''){
                                    echo $k['albums']['data'][$i]['name'];
                                }
                                else{
                                    echo "<span onclick='ab(".$i.$photos.");'>".$k['albums']['data'][$i]['name']."</span>";
                                }
                                echo "</td></tr>";
                            }
                            echo "</table></center>";
                            echo "<div id='".$i.$photos."' style='display:block'>";
                            if($k['albums']['data'][$i]['photos']['data']==''){
                                echo "Here1";
                                echo "<center><table class='border format' width='600px' border='1'>";
                                if($k['albums']['data'][$i]['name']!=''){
                                    echo "here";
                                    echo "<tr><td><span onclick='ab(".$i.$photos.");'>".$k['albums']['data'][$i]['name']."</span></td></tr>";
                                   
                                }
                                echo "</table></center>";
                            }
                            else{
                                
                                echo "<center><table class='border format' width='600px' border='1'>";
                                echo "<tr><td>";
                                for($j=0;$j<2;$j++){
                                    if($k['albums']['data'][$i]['photos']['data'][$j]['picture']!=''){
                                        echo "<a href='".$k['albums']['data'][$i]['photos']['data'][$j]['picture']."' target='_blank'><img src='".$k['albums']['data'][$i]['photos']['data'][$j]['picture']."'></img></a>&nbsp&nbsp";
                                    }
                                }
                                echo "</td></tr></table></center>";
                            }
                            echo "</div>";
                        }
                        echo "</div>";
                    }
                }
            }
            foreach($s as $k){
                $po='pos';
                $posts=$k['id'].$po;
                
                $ppo='phead';
                $post=$k['id'].$ppo;
                
                if($k['posts']['data'][0]['message']!=''){
                    foreach($s as $k){
                        $ppo='phead';
                        $post=$k['id'].$ppo;
                        echo "<div id='".$post."' style='display:none'>";
                        echo "<br><center><table class='border format' width='600px' border='1'><tr><td><span onclick='pslink(".$k['id'].")'><center>Posts</center></span></td></tr></table></center></div>";
                    }
                    foreach($s as $k){
                        $po='pos';
                        $posts=$k['id'].$po;
                        
                        echo "<div id='".$posts."' style='display:none'>";
                        if($k['posts']['data'][0]['message']!=''){
                            echo "<center><table class='border format' width='600px' border='1'><tr><td><b>Message</b></td></tr>";
                            for($j=0;$j<5;$j++){
                                echo "<tr><td>".$k['posts']['data'][$j]['message']."</td></tr>";
                            }
                            echo "</table></center>";
                        }
                        else{
                            echo "<center><table class='border format' width='600px' border='1'><tr><td><center>No messages have been found</center></td><tr></table></center>";
                        }
                        echo '</div>';
                    }
                }
                else{
                    echo "<div id='".$post."' style='display:none'>";
                    echo "<center><table class='format border' width='600px' border='1'><tr><td><center>No posts have been found</center></td></tr></table></center>";
                    echo "</div>";
                }
            }
        }
        echo "</div>";
    
            }
     else if(($location=='')&&($distance=='')){
         echo "<div id='tab'>";
            if($s[0]==''){
                echo "<center><table class='table1' width='600px' border='1'><tr><td><center>No records have been found</center></td></tr></table></center>";
            }
            else{
                echo "<center><table class='border format' border='1' id='tab1' width='600px' height='15px'><tr><th>Profile Photo</th><th>Name</th><th>Details</th></tr>";
                foreach($s as $k){
                    echo "<tr><td><a href='".$k['picture']['data']['url']."' target='_blank'><img src='".$k['picture']['data']['url']."' height='25px' width='25px'></img></a></td><td>".$k['name']."</td><td><span id='details' onclick='detail(".$k['id'].");'>Details</span></td></tr>";
                }
                echo "</table></center>";
                
            foreach($s as $k){
                $a='alb';
                $b=$k['id'].$a;
                
                $aa='albhead';
                $bb=$k['id'].$aa;
                
                if($k['albums']['data']==''){
                    echo "<div id='".$bb."' style='display:none'>";
                    echo "<center><table class='border format' width='600px' border='1'><tr><td><center>No Albums have been found</center></td></tr></table></center><br></div>";
                }
                else if($k['albums']['data']!=''){
                    foreach($s as $k){
                        $aa='albhead';
                        $bb=$k['id'].$aa;
                        
                        echo "<div id='".$bb."' style='display:none'>";
                        echo "<center><table class='border format' width='600px' border='1'><tr><td><span onclick='albumlink(".$k['id'].");'><center>Albums</center></span></td></tr></table></center></div>";
                    }
                    
                    foreach($s as $k){
                        $a='alb';
                        $b=$k['id'].$a;
                        $p='photos';
                        $photos=$k['id'].$p;
                        
                        echo "<div id='".$b."' style='display:none'>";
                        for($i=0;$i<5;$i++){
                            echo "<center><table class='border format' width='600px' border='1'>";
                            if($k['albums']['data'][$i]['name']!=''){
                                echo "<tr><td>";
                                if($k['albums']['data'][$i]['photos']['data']==''){
                                    echo $k['albums']['data'][$i]['name'];
                                }
                                else{
                                    
                                    echo "<span onclick='ab(".$i.$photos.")'>".$k['albums']['data'][$i]['name']."somethinggg</span>";
                                    
                                }
                                echo "</td></tr>";
                            }
                            echo "</table></center>";
                            
                            echo "<div id='".$i.$photos."' style='display:block'>";
                            if($k['albums']['data'][$i]['photos']['data']==''){
                                echo "<center><table class='border format' width='600px' border='1'>";
                                if($k['albums']['data'][$i]['name']!=''){
                                    echo "<tr><td><span onclick='ab(".$i.$photos.")'>".$i.$photos.$k['albums']['data'][$i]['name']."</span></td></tr>";
                                }
                                echo "</table></center>";
                            }
                            else{
                                echo "<center><table class='border format' width='600px' border='1'>";
                                echo "<tr><td>";
                                for($j=0;$j<2;$j++){
                                    if($k['albums']['data'][$i]['photos']['data'][$j]['picture']!=''){
                                        echo "<a href='".$k['albums']['data'][$i]['photos']['data'][$j]['picture']."' target='_blank'><img src='".$k['albums']['data'][$i]['photos']['data'][$j]['picture']."'/></a>&nbsp&nbsp";
                                    }
                                }
                                echo "</td></tr></table></center>";
                            }
                            echo "</div>";
                        }
                        echo "</div>";
                    }
                }
            }
            foreach($s as $k){
                $po='pos';
                $posts=$k['id'].$po;
                
                $ppo='phead';
                $post=$k['id'].$ppo;
                
                if($k['posts']['data'][0]['message']!=''){
                    foreach($s as $k){
                        $ppo='phead';
                        $post=$k['id'].$ppo;
                        echo "<div id='".$post."' style='display:none'>";
                       
                        echo "<br><center><table class='border format' width='600px' border='1'><tr><td><span onclick='pslink(".$k['id'].")'><center>Posts</center></span></td></tr></table></center></div>";
                    }
                    foreach($s as $k){
                        $po='pos';
                        $posts=$k['id'].$po;
                        
                        echo "<div id='".$posts."' style='display:none'>";
                        if($k['posts']['data'][0]['message']!=''){
                            echo "<center><table class='border format' width='600px' border='1'><tr><td><b>Message</b></td></tr>";
                            for($j=0;$j<5;$j++){
                                echo "<tr><td>".$k['posts']['data'][$j]['message']."</td></tr>";
                            }
                            echo "</table></center>";
                        }
                        else{
                            echo "<center><table class='border format' width='600px' border='1'><tr><td><center>No messages have been found</center></td><tr></table></center>";
                        }
                        echo '</div>';
                    }
                }
                else{
                    echo "<div id='".$post."' style='display:none'>";
                    echo "<center><table class='format border' width='600px' border='1'><tr><td><center>No posts have been found</center></td></tr></table></center>";
                    echo "</div>";
                }
            }
        }
        echo "</div>";
     }
    else if(($location)&&($distance=='')){
        {
                    $l=str_replace(' ','+',$location);
                    //$l=urlencode($location);
                    $geo=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$l.'&sensor=false');
                    $fin= json_decode($geo);
                   
                    $latitude = $fin->results[0]->geometry->location->lat;
                    $longitude = $fin->results[0]->geometry->location->lng;	
                }
                $s=$fb->get('/search?q='.$keyword.'&type=place&center='.$latitude.','.$longitude.'&fields=id,name,picture.width(2000).height(2000),albums.limit(5){name,photos.limit(2){name,picture}},posts.limit(5)');
                
                $s=$s->getDecodedBody();
                $s=$s["data"];
                echo "<div id='tab'>";
            if($s[0]==''){
                echo "<center><table class='table1' width='600px' border='1'><tr><td><center>No records have been found</center></td></tr></table></center>";
            }
            else{
                echo "<center><table class='border format' border='1' id='tab1' width='600px' height='15px'><tr><th>Profile Photo</th><th>Name</th><th>Details</th></tr>";
                foreach($s as $k){
                    echo "<tr><td><a href='".$k['picture']['data']['url']."' target='_blank'><img src='".$k['picture']['data']['url']."' height='25px' width='25px'></img></a></td><td>".$k['name']."</td><td><span id='details' onclick='detail(".$k['id'].");'>Details</span></td></tr>";
                }
                echo "</table></center>";
                
            foreach($s as $k){
                $a='alb';
                $b=$k['id'].$a;
                
                $aa='albhead';
                $bb=$k['id'].$aa;
                
                if($k['albums']['data']==''){
                    echo "<div id='".$bb."' style='display:none'>";
                    echo "<center><table class='border format' width='600px' border='1'><tr><td><center>No Albums have been found</center></td></tr></table></center><br></div>";
                }
                else if($k['albums']['data']!=''){
                    foreach($s as $k){
                        $aa='albhead';
                        $bb=$k['id'].$aa;
                        
                        echo "<div id='".$bb."' style='display:none'>";
                        echo "<center><table class='border format' width='600px' border='1'><tr><td><span onclick='albumlink(".$k['id'].");'><center>Albums</center></span></td></tr></table></center></div>";
                    }
                    
                    foreach($s as $k){
                        $a='alb';
                        $b=$k['id'].$a;
                        $p='photos';
                        $photos=$k['id'].$p;
                        
                        echo "<div id='".$b."' style='display:none'>";
                        for($i=0;$i<5;$i++){
                            echo "<center><table class='border format' width='600px' border='1'>";
                            if($k['albums']['data'][$i]['name']!=''){
                                echo "<tr><td>";
                                if($k['albums']['data'][$i]['photos']['data']==''){
                                    echo $k['albums']['data'][$i]['name'];
                                }
                                else{
                                    echo "<span onclick='ab(".$i.$photos.")'>".$k['albums']['data'][$i]['name']."</span>";
                                }
                                echo "</td></tr>";
                            }
                            echo "</table></center>";
                            
                            echo "<div id='".$i.$photos."' style='display:block'>";
                            if($k['albums']['data'][$i]['photos']['data']==''){
                                echo "<center><table class='border format' width='600px' border='1'>";
                                if($k['albums']['data'][$i]['name']!=''){
                                    echo "<tr><td><span onclick='ab(".$i.$photos.")'>".$k['albums']['data'][$i]['name']."</span></td></tr>";
                                }
                                echo "</table></center>";
                            }
                            else{
                                echo "<center><table class='border format' width='600px' border='1'>";
                                echo "<tr><td>";
                                for($j=0;$j<2;$j++){
                                    if($k['albums']['data'][$i]['photos']['data'][$j]['picture']!=''){
                                        echo "<a href='".$k['albums']['data'][$i]['photos']['data'][$j]['picture']."' target='_blank'><img src='".$k['albums']['data'][$i]['photos']['data'][$j]['picture']."'/></a>&nbsp&nbsp";
                                    }
                                }
                                echo "</td></tr></table></center>";
                            }
                            echo "</div>";
                        }
                        echo "</div>";
                    }
                }
            }
            foreach($s as $k){
                $po='pos';
                $posts=$k['id'].$po;
                
                $ppo='phead';
                $post=$k['id'].$ppo;
                
                if($k['posts']['data'][0]['message']!=''){
                    foreach($s as $k){
                        $ppo='phead';
                        $post=$k['id'].$ppo;
                        echo "<div id='".$post."' style='display:none'>";
                       
                        echo "<br><center><table class='border format' width='600px' border='1'><tr><td><span onclick='pslink(".$k['id'].")'><center>Posts</center></span></td></tr></table></center></div>";
                    }
                    foreach($s as $k){
                        $po='pos';
                        $posts=$k['id'].$po;
                        
                        echo "<div id='".$posts."' style='display:none'>";
                        if($k['posts']['data'][0]['message']!=''){
                            echo "<center><table class='border format' width='600px' border='1'><tr><td><b>Message</b></td></tr>";
                            for($j=0;$j<5;$j++){
                                echo "<tr><td>".$k['posts']['data'][$j]['message']."</td></tr>";
                            }
                            echo "</table></center>";
                        }
                        else{
                            echo "<center><table class='border format' width='600px' border='1'><tr><td><center>No messages have been found</center></td><tr></table></center>";
                        }
                        echo '</div>';
                    }
                }
                else{
                    echo "<div id='".$post."' style='display:none'>";
                    echo "<center><table class='format border' width='600px' border='1'><tr><td><center>No posts have been found</center></td></tr></table></center>";
                    echo "</div>";
                }
            }
        }
        echo "</div>";
    
    }
                             
        }
    }
}
    ?>

</body>
<script> 
    function ab(id){
        document.write(id);
        if(document.getElementById(id).style.display=="none")
            document.getElementById(id).style.display="block";
        
        else
            document.getElementById(id).style.display="none"; 
   }
    </script> 
</html>
