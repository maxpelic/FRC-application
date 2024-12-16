<?php


//return JSON response to the user
header('Content-Type: application/json');

#error function (return a standard JSON error)
function error(int $code, string $message) {
    header('Content-Type: application/json');
    header("HTTP/1.1 $code $message");
    echo json_encode([
        "error" => $message,
        "code" => $code
    ]);
    exit;
}

#make a CURL request to an API
#add support for other types of requests (including JSON data) if needed
function request(string $url, string $method = "GET"){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

    $response = curl_exec($ch);

    $curl_error = curl_error($ch);

    if(!empty($curl_error)){
        #send as error code (this should be disabled in production)
        error(500, $curl_error);
    }

    return json_decode($response, true);
}

#allow access from any domain (should be restricted in production)
header("Access-Control-Allow-Origin: *");