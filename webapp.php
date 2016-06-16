<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Webapp</title>
</head>
<body>
</body>
</html>

<h1>Web app</h1>

<?php
if(isset($_COOKIE["webapp_access_token"])){
    getCalendarItems($_COOKIE["webapp_access_token"]);
}
else if(isset($_GET['code'])){
    if ($code = $_GET['code']) {

        $postdata = http_build_query(
            array(
                "code"=> $code,
                "client_id"=>"886305963993-m1din7q7n6vkvp3fp9gbjt0ljpkj0gci.apps.googleusercontent.com",
                "client_secret"=> "IOZcsocwljUiv1Z-CTmsMlZc",
                "redirect_uri"=>"http://localhost:8080/security2/webapp.php",
                "grant_type"=>"authorization_code"
            )
        );

        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => urldecode($postdata)
            )
        );

        $context  = stream_context_create($opts);

        $result = json_decode(file_get_contents('https://www.googleapis.com/oauth2/v4/token', false, $context), true);
        $access_token = $result["access_token"];
        getCalendarItems($access_token);
    }
}
else{
    echo "<a href=\"https://accounts.google.com/o/oauth2/auth?response_type=code&client_id=886305963993-m1din7q7n6vkvp3fp9gbjt0ljpkj0gci.apps.googleusercontent.com
&scope=https://www.googleapis.com/auth/calendar&redirect_uri=http://localhost:8080/security2/webapp.php\">Klik hier om je agenda te bekijken</a>";
}

function getCalendarItems($access_token){
    //    // Create a stream
    $getOpts = array(
        'http'=>array(
            'method'=>"GET",
            'header'=>"Authorization : Bearer " . $access_token
        )
    );
    setcookie("webapp_access_token", $access_token, time()+3600); // set access_token cookie for 1 hour

    $getContext = stream_context_create($getOpts);
    $calender_items = json_decode(file_get_contents("https://www.googleapis.com/calendar/v3/calendars/erik.vink95@gmail.com/events", false, $getContext),true)["items"];

    echo "<h2>De volgende activiteiten staan op je Google calendar:</h2>";

    echo "<ul>";
    foreach ($calender_items as $calender_item) {
        echo "<li>";
        echo $calender_item["summary"];
        if(isset($calender_item["start"]["dateTime"])){echo " op " . date('m/d/Y',strtotime($calender_item["start"]["dateTime"]));}
        echo "</li>";
    }
    echo "</ul>";
}






