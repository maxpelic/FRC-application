<?php
include_once "helper.php";

#get stripe transactions
//https://api.stripe.com/v1/charges

$stripe_key = getenv("stripe_key");

#request list of transactions
$response = request("https://api.stripe.com/v1/charges?created[gte]=" . strtotime("today - 30 days"), "GET", $stripe_key);

if(@$response["error"]){
    file_put_contents("../logs/stripe_error.log", json_encode($response) . "\n\n", FILE_APPEND);
    error(500, "Stripe error (see logs)");
}

#generate summary and information
$transaction_data = [];
$total = 0;
$count = 0;

foreach($response["data"] as $transaction){
    $transaction_data[] = [
        "amount" => $transaction['amount'] / 100,
        "currency" => $transaction['currency'],
        "description" => $transaction['description'],
        "status" => $transaction['status']
    ];
    $count++;
    $total += $transaction['amount'];
}

echo json_encode([
    "total_amount" => $total / 100,
    "total_charges" => $count,
    "charges" => $transaction_data
]);
