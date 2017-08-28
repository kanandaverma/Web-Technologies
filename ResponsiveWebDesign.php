<?php
  session_start();
    require_once __DIR__ . '/php-graph-sdk-5.0.0/src/Facebook/autoload.php';
    header('Access-Control-Allow-Origin:*');
    $fb = new Facebook\Facebook([
      'app_id' => '1000772346719718',
      'app_secret' => 'f1c8dd2237c6d3232c76ec066719bd4a',
      'default_graph_version' => 'v2.8'
     ]);

    $fb->setDefaultAccessToken ('EAAOOMng6LeYBAHcoq9SkZAB5MnWH7MHfx8Y5NVgE8swdXQd5YPlyFZCxkXFAa5eQqhfsCnHlOZBihhwV3SlNvUzsOvJbO5dtSpojc8P85TEWVHJo4AAIgxqQzHFZANsNfJsK0snBw91w9IjKgrqk7ZCvM2YUirFoZD');
            try {
                    $response = $fb->get('/me');
                    $usernode=$response->getGraphUser();
                } 
            catch(Facebook\Exceptions\FacebookResponseException $e) {
          
                  echo 'Graph returned an error: ' . $e->getMessage();
                  exit;
            } 
            catch(Facebook\Exceptions\FacebookSDKException $e) {
          
                  echo 'Facebook SDK returned an error: ' . $e->getMessage();
                  exit;
            }
    error_reporting(E_ALL & ~E_NOTICE & ~E_ERROR);
    if($_GET['basic']){
            
            $s=$fb->get('/search?q='.$_GET['query'].'&type='.$_GET['type'].'&fields=id,name,picture.width(2000).height(2000)');
            $s=$s->getDecodedBody();
            echo json_encode($s);
    }
    if($_GET['album']){
            
            $s=$fb->get('/'.$_GET['id'].'?fields=id,name,picture.width(2000).height(2000),albums.limit(5){name,photos.limit(2){name,images}},posts.limit(5)');
            $s=$s->getDecodedBody();
            echo json_encode($s);
    }
    if($_GET['fbpost']){
        
         $s=$fb->get('/'.$_GET['id'].'?fields=id,name,picture.width(2000).height(2000)');
         $s=$s->getDecodedBody();
         echo json_encode($s);
    }
    if($_GET['forpaging']){
        $s=file_get_contents($_GET['query']);
        echo $s;
    }

?>