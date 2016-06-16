<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Webapp</title>
</head>
<body>
</body>
</html>

<h1>Web app</h1>

<a href="https://accounts.google.com/o/oauth2/auth?response_type=code&client_id=886305963993-m1din7q7n6vkvp3fp9gbjt0ljpkj0gci.apps.googleusercontent.com
&scope=https://www.googleapis.com/auth/calendar&redirect_uri=http://localhost:8080/security2/webapp.php">Klik hier om je agenda te bekijken</a>

<?php

if(isset($_GET['code'])){
    if ($code = $_GET['code']) {
        var_dump($code);

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
        setcookie("webapp_access_token", $access_token);

        //    // Create a stream
        $getOpts = array(
            'http'=>array(
                'method'=>"GET",
                'header'=>"Authorization : Bearer " . $access_token
            )
        );

        $getContext = stream_context_create($getOpts);
        $calender_items = json_decode(file_get_contents("https://www.googleapis.com/calendar/v3/calendars/erik.vink95@gmail.com/events", false, $getContext),true)["items"];

        echo "<ul>";
        foreach ($calender_items as $calender_item) {
            echo "<li>";
            echo $calender_item["summary"];
            if(isset($calender_item["start"]["dateTime"])){echo " op " . date('m/d/Y',strtotime($calender_item["start"]["dateTime"]));}
            echo "</li>";
        }
        echo "</ul>";

//        var_dump(json_decode($response, true));

//        https://www.googleapis.com/calendar/v3/calendars/erik.vink95@gmail.com/events
//        Headers: Authorization: Bearer ya29.CjHnAuc0Wtu1IQJpJtSdp0zruqYltYzrcR3PuL_yyuZleQZV7shF2FE-UZnBfWSMyVy5
//
//        var_dump($result);

//        POST /oauth2/v4/token HTTP/1.1
//Host: www.googleapis.com
//Content-Type: application/x-www-form-urlencoded

        //The parameter you need is present
//        var_dump($result);
//
//
//        header('header: Authorization : Bearer' . 'ya29.CjHnAuc0Wtu1IQJpJtSdp0zruqYltYzrcR3PuL_yyuZleQZV7shF2FE-UZnBfWSMyVy5');
//        $response = http_get("https://www.googleapis.com/calendar/v3/calendars/erik.vink95@gmail.com/events");


//    // Create a stream
//    $opts = array(
//        'http'=>array(
//            'method'=>"GET",
//            'header'=>"Authorization : Bearer " . 'ya29.CjHnAuc0Wtu1IQJpJtSdp0zruqYltYzrcR3PuL_yyuZleQZV7shF2FE-UZnBfWSMyVy5'
//        )
//    );
//
//    $context = stream_context_create($opts);
//
//// Open the file using the HTTP headers set above
//    $file = file_get_contents('https://www.googleapis.com/calendar/v3/calendars/erik.vink95@gmail.com/events', false, $context);
//
//    var_dump($file);

//    $response = http_get("https://www.googleapis.com/calendar/v3/calendars/erik.vink95@gmail.com/events");
//
    }
}









//
//    Headers: Authorization: Bearer ya29.CjHnAuc0Wtu1IQJpJtSdp0zruqYltYzrcR3PuL_yyuZleQZV7shF2FE-UZnBfWSMyVy5

//require_once __DIR__ . '/vendor/autoload.php';
//
//
//define('APPLICATION_NAME', 'Google Calendar API PHP Quickstart');
//define('CREDENTIALS_PATH', '~/.credentials/calendar-php-quickstart.json');
//define('CLIENT_SECRET_PATH', __DIR__ . '/client_secret.json');
//// If modifying these scopes, delete your previously saved credentials
//// at ~/.credentials/calendar-php-quickstart.json
//define('SCOPES', implode(' ', array(
//        Google_Service_Calendar::CALENDAR_READONLY)
//));
//
////if (php_sapi_name() != 'cli') {
////    throw new Exception('This application must be run on the command line.');
////}
//
///**
// * Returns an authorized API client.
// * @return Google_Client the authorized client object
// */
//function getClient() {
//    $client = new Google_Client();
//    $client->setApplicationName(APPLICATION_NAME);
//    $client->setScopes(SCOPES);
//    $client->setAuthConfigFile(CLIENT_SECRET_PATH);
//    $client->setAccessType('offline');
//
//    // Load previously authorized credentials from a file.
//    $credentialsPath = expandHomeDirectory(CREDENTIALS_PATH);
//    if (file_exists($credentialsPath)) {
//        $accessToken = file_get_contents($credentialsPath);
//    } else {
//        // Request authorization from the user.
//        $authUrl = $client->createAuthUrl();
//        printf("Open the following link in your browser:\n%s\n", $authUrl);
//        print 'Enter verification code: ';
//        $authCode = trim(fgets(STDIN));
//
//        // Exchange authorization code for an access token.
//        $accessToken = $client->authenticate($authCode);
//
//        // Store the credentials to disk.
//        if(!file_exists(dirname($credentialsPath))) {
//            mkdir(dirname($credentialsPath), 0700, true);
//        }
//        file_put_contents($credentialsPath, $accessToken);
//        printf("Credentials saved to %s\n", $credentialsPath);
//    }
//    $client->setAccessToken($accessToken);
//
//    // Refresh the token if it's expired.
//    if ($client->isAccessTokenExpired()) {
//        $client->refreshToken($client->getRefreshToken());
//        file_put_contents($credentialsPath, $client->getAccessToken());
//    }
//    return $client;
//}
//
///**
// * Expands the home directory alias '~' to the full path.
// * @param string $path the path to expand.
// * @return string the expanded path.
// */
//function expandHomeDirectory($path) {
//    $homeDirectory = getenv('HOME');
//    if (empty($homeDirectory)) {
//        $homeDirectory = getenv("HOMEDRIVE") . getenv("HOMEPATH");
//    }
//    return str_replace('~', realpath($homeDirectory), $path);
//}
//
//// Get the API client and construct the service object.
//$client = getClient();
//$service = new Google_Service_Calendar($client);
//
//// Print the next 10 events on the user's calendar.
//$calendarId = 'primary';
//$optParams = array(
//    'maxResults' => 10,
//    'orderBy' => 'startTime',
//    'singleEvents' => TRUE,
//    'timeMin' => date('c'),
//);
//$results = $service->events->listEvents($calendarId, $optParams);
//
//if (count($results->getItems()) == 0) {
//    print "No upcoming events found.\n";
//} else {
//    print "Upcoming events:\n";
//    print "<ul>";
//    foreach ($results->getItems() as $event) {
//        $start = $event->start->dateTime;
//        if (empty($start)) {
//            $start = $event->start->date;
//        }
//        print "<li>";
//        printf("%s (%s)\n", $event->getSummary(), $start);
//        print "</li>";
//    }
//    print "</ul>";
//}




