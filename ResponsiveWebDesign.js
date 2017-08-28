window.fbAsyncInit = function() {
        FB.init({ 
          appId: '1000772346719718',
          status: true, 
          cookie: true, 
          xfbml: true,
          version: 'v2.8'
        });
    };
(function(d){
        var js, id = 'facebook-jssdk', reference = d.getElementsByTagName('script')[0];
        if (d.getElementById(id)) {
          return;
        }
        js = d.createElement('script');
        js.id = id;
        js.async = true;
        js.src = "//connect.facebook.net/en_US/all.js";
        reference.parentNode.insertBefore(js, reference);
    }(document));

var app = angular.module('myApp', ['ngAnimate']);
app.controller('myCtrl', function($scope,$http,$window) {
        $scope.tab='user';
     //FOR ACTIVE TAB
    $scope.activetab= function(t,q){
        $scope.tab=t;
        if($scope.tab=='favs'){
            $scope.showfav();
        }
        else{
            if(!q==''){
                $scope.search(q);
            } 
        }
    };
    
    //FB POST
    $scope.postonfb=function(id){
        $scope.id=id;
        $scope.fb=true;
        $http.get("http://homeworkeight-env.us-west-1.elasticbeanstalk.com/ResponsiveWebDesign.php",{params:{"id":$scope.id, "fbpost": $scope.fb}}).success(function(r){
            $scope.fbdata=r;
        });
        FB.ui({ method: 'feed',name: $scope.fbdata.name,
                link: $window.location.href,
                caption: 'FB SEARCH FROM USC CSCI571',
                picture: $scope.fbdata.picture.data.url,
              }, function(response) {
                    if (response && !response.error_message) {
                        alert('Posted Successfully!');
                    } 
                    else{
                        alert('Not Posted!');
                    }
                });
        }; 
    // ADD TO FAVOURITES
    var record;
    $scope.addfav=function(id,photo,name,type){
        if(!$window.localStorage.getItem(id.toString())){
            var t=type.toString();
            var one=t.charAt(0).toUpperCase();
            t=t.substr(1);
            t=one+t;
            record="["+photo.toString()+","+name.toString()+","+t+","+id.toString()+"]";
            $window.localStorage.setItem(id.toString(),record);
        }
    };
    // DISPLAY FAVOURITES
    $scope.showfav=function(){
        $scope.progresspanel=true;
        var k,val,v, entry,j,flag=0;
        $scope.fav=[];
        for (i=0;i<$window.localStorage.length; i++) {
            k = $window.localStorage.key(i);
            val=$window.localStorage.getItem(k);
            val=val.replace("[", "");
            val=val.replace("]","");
            v=val.split(",");
            entry={
                picture:v[0],
                name:v[1],
                type:v[2],
                id:v[3]
            };
            $scope.fav.push(entry);
        }
        $scope.progresspanel=false;
        $scope.favshow=true;
        $scope.tablehide=false;
        $scope.panelhide=false;
    };
    //REMOVE FAVOURITES
    $scope.removefav=function(id){
        $window.localStorage.removeItem(id.toString());
        if($scope.tab.toString()=='favs'){
            $scope.favshow=false;
            $scope.showfav();
        }
    };
    //REMOVE FAVOURITES FROM PANEL
    $scope.removef=function(id){
        $window.localStorage.removeItem(id.toString());
    };
    
    //STAR COLOUR
    $scope.starcolour=function(id){
        if(!$window.localStorage.getItem(id.toString())){
            return true;
        }
        else{
            return false;
        }
    };
    //CLEAR FUNCTION
    $scope.clear=function(){
        $scope.tb=null;
        $scope.favshow=false;
        $scope.tablehide=false;
        $scope.panelhide=false;
    };
    
    //FOR LOCATION
    $scope.position= function(pos){
        $scope.lat=pos.coords.latitude;
        $scope.long=pos.coords.longitude;
    };
    navigator.geolocation.getCurrentPosition($scope.position);
    $scope.tablehide=false;
    $scope.panelhide=false;
    $scope.favshow=false;
    
    //FIXDATE
    $scope.fixdate=function(datetime){
        datetime=datetime.replace("T", " ");
        return datetime.replace("+0000", "");
    };
    $scope.progresspanel=false;
    //FOR DETAILS
    $scope.gotodetails=function(id){
        $scope.id=id;
        $scope.a=true;
        $scope.tablehide=false;
        $scope.favshow=false;
        $scope.progresspanel=true;
        $http.get("http://homeworkeight-env.us-west-1.elasticbeanstalk.com/index.php",{params:{"id":$scope.id, "album": $scope.a}}).success(function(r){
            $scope.al=r;
            $scope.progresspanel=false;
            $scope.panelhide=true;
        });
    };
    
    //FOR BACK BUTTON
    $scope.go_back=function(que){
        $scope.q=que;
        $scope.b=true;
        $scope.tablehide=false;
        $scope.panelhide=false;
        $scope.favshow=false;
        if($scope.tab=='favs'){
            $scope.tab='user';
        }
        $http.get("http://homeworkeight-env.us-west-1.elasticbeanstalk.com/index.php", {params:{"query": $scope.q, "type": $scope.tab, "basic": $scope.b}}).success(function(response){
           $scope.data=response.data;
           $scope.tablehide=true;
           $scope.panelhide=false;
           $scope.favshow=false;
        });
    };
    
    //FOR GOTONEXT PAGINATION
    $scope.gotonext=function(next){
        $scope.tablehide=false;
        $scope.panelhide=false;
        $scope.progresstable=true;
        $http.get("http://homeworkeight-env.us-west-1.elasticbeanstalk.com/index.php", {params:{"query": next, "forpaging": true}}).success(function(response){
           $scope.data=response.data;
           $scope.next=response.paging.next;
           $scope.prev=response.paging.previous;
           $scope.tablehide=true;
           $scope.panelhide=false;
           $scope.favshow=false; 
           $scope.progresstable=false;
        });
    };
    
    //FOR GOTOPREV PAGINATION
     $scope.gotoprev=function(prev){
        $scope.tablehide=false;
        $scope.panelhide=false;
        $scope.progresstable=true;
        $http.get("http://homeworkeight-env.us-west-1.elasticbeanstalk.com/index.php", {params:{"query": prev, "forpaging": true}}).success(function(response){
           $scope.data=response.data;
           $scope.next=response.paging.next;
           $scope.prev=response.paging.previous;
           $scope.tablehide=true;
           $scope.panelhide=false;
           $scope.favshow=false; 
           $scope.progresstable=false;
        });
    };
    
    $scope.progresstable=false;
    //FOR SEARCH BUTTON
    $scope.search=function(i){
        $scope.q=i;
        $scope.tablehide=false;
        $scope.panelhide=false;
        $scope.favshow=false;
        $scope.b=true;
        if(!i==''){
            $scope.progresstable=true;
        }
        $http.get("http://homeworkeight-env.us-west-1.elasticbeanstalk.com/index.php", {params:{"query": $scope.q, "type": $scope.tab, "basic": $scope.b}}).success(function(response){
           $scope.data=response.data;
           $scope.next=response.paging.next;
           $scope.prev=response.paging.previous;
           $scope.tablehide=true;
           $scope.panelhide=false;
           $scope.favshow=false; 
           $scope.progresstable=false;
        });
    }
});
