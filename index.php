<style>
    .thestartitle a {
        color:#fff;
    }
    .menu {
        padding:20px 0 10px 0;
    }
    .menu a{
        color:#fff;
    }

</style>
<div class="thestartitle" style="text-align:center;width:80%;padding-top:20px;background:black;margin-left:auto;margin-right:auto;"> 
    <img src="img/swBanner.png" style="padding:20px; max-width:100%;"/><br/>
    <span style="font-family:Tahoma;font-size:10pt;">
        <a href="https://swapi.dev/">Data Provided by: The Star Wars API</a>
    </span>
</div>

<!-- This here’s the navigation bar at the top -->
<div class="menu" style="text-align:center;width:80%;background:black;margin-left:auto;margin-right:auto;">
    <a href="?cat=people">People&nbsp;</a>
    <a href="?cat=species">Species&nbsp;</a>
    <a href="?cat=planets">Planets&nbsp;</a>
    <a href="?cat=starships">Starships&nbsp;</a>
    <a href="?cat=vehicles">Vehicles&nbsp;</a>
    <a href="?cat=films">Films&nbsp;</a>
</div>

<div class="wrapper">
    <?php
        // $first_day = (date('Y-01-01'));  
        // $last_day = date('Y-m-t');
        // echo $first_day."<br>";
        // echo $last_day
        ?>
</div>

<?php
    if (isset($_GET["cat"])){
        $category= $_GET["cat"]; 
    }else {
        $category = "people";
    }
    if (isset($_GET["specific"])){
        $specific= $_GET["specific"];
     }else {
         $specific = "";
     }
     if (isset($_GET["page"])){
        $pageNo= $_GET["page"]; 
    }else {
        $pageNo = "";
    }

    $urlLink = 'https://swapi.dev/api/'.$category.'/'.$specific;
    if ($pageNo<>"") $urlLink.='?page='.$pageNo;
    // Here's where we call the API and get the data
    $jsonSW = file_get_contents($urlLink);
    // Then put it into JSON format so we can use it
    $jsonSW = json_decode($jsonSW);

    function drawLeftBox($key,$value){

        if ($key != "created" && $key!="edited" && $key!="url") echo '<span style="color:LightBlue;display:inline-block;margin-top:4px;"><u>'.ucfirst($key).'</u>&nbsp;:&nbsp;';
        
        if (is_array($value)){
        echo '<br/>';
        for($i=0;$i<count($value);$i++) {
        
        $urlArray = explode('/',$value[$i]);
        $dirname = 'swimg/'.$urlArray[4].'/'.$urlArray[5].'/';
        
        $images = glob($dirname."*.*");
        foreach($images as $image) {
        echo '<a href="?cat='.$urlArray[4].'&specific='.$urlArray[5].'"><img src="'.$image.'" class="swSmallPix" style="max-width:60px;padding-top:2px;padding-bottom:2px;"/></a>';
        }
        
        } echo '<br/>';
        } else if ($key != "created" && $key!="edited" && $key!="url"){
        
        if ($key=='homeworld') {
        
        $homeworldURLArray= explode("/",$value);
        $hwLink = '/swimg/'.$homeworldURLArray[4].'/'.$homeworldURLArray[5].'/1.jpg';
        
        echo '<br/><a href="?cat='.$homeworldURLArray[4].'&specific='.$homeworldURLArray[5].'"><img src="'.$hwLink.'" class="swSmallPix" style="max-width:60px;padding-top:2px;"/></a><br/>';
        
        } else echo '<span style="color:yellow;display:inline-block;margin-top:4px;">'.$value.'</span><br/>';
        }
    }

    function drawRightBox($key,$value){
 
        if($key=='url'){
        
        $urlArray= explode("/",$value);
        
        $bigPic = '/swimg/'.$urlArray[4].'/'.$urlArray[5].'/1.jpg';
        
        $href = '?cat='.$urlArray[4].'&specific='.$urlArray[5];
        }
        echo '<div style="background-color:black;float:right;width:35%;padding-top:20px;text-align:center;"><a href="'.$href.'"><img src="'.$bigPic.'" style="max-width:100%;"></a></div>';
        echo '<hr style="clear:both;color:white;margin:5px;">';
        }

    // This DIV is to encapsulate the output.
    echo '<div style="width:80%;margin-left:auto;padding-top:40px;margin-right:auto;background-color:black;">';
    if ($specific===""){
    foreach($jsonSW->results as $item){
    echo '<div style="float:left;background-color:black;color:white;width:60%;padding-top:20px;margin-left:20px;">';
        foreach ($item as $key=>$value){
        drawLeftBox($key,$value);
        }
    echo '</div>';
    drawRightBox($key,$value);
    }
    } else {
    echo '<div style="float:left;background-color:black;color:white;width:60%;padding-top:20px;margin-left:20px;">';
    // Notice the "->results" is missing in the line below? Because the specific data only has info on one character
        foreach ($jsonSW as $key=>$value){
            drawLeftBox($key,$value);
        }
    echo '</div>';
    drawRightBox($key,$value);
}

if ($jsonSW->next!=""){
    $nextPageArray = explode("/", $jsonSW->next);
    $nextPage = str_replace("?page=","",$nextPageArray[5]);
           
echo '<div style="width:100%;padding-right:5px;background-color:black;color:white;text-align:right;">
<a href="?cat='.$category.'&specific='.$specific.'&page='.$nextPage.'">Next Page</a></div>';
}
       
echo '<br/></div>';
       // Look at that! We're at the bottom of the page
       // and that's it. Easy, right?

?>