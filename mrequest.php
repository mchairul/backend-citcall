<?php
/*
|-------------------------------------------------------------------
| REQUEST CALL
|-------------------------------------------------------------------
| before you connect to the citcall API make sure that:
| 1. You have read the citcall API documentation
| 2. your userid has been registered and your IP has been filtered in citcall system
|
*/
include "config.php";

$input = file_get_contents('php://input');
$json = json_decode($input);


define('APIKEY', '<YOUR_API_KEY>');

$data = array(
    "msisdn" => $jsonawal->msisdn,
    "retry" =>$jsonawal->retry,
);

$url = "https://pub.citcall.com/v3/motp";
$content = json_encode($data);
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: Apikey ' . APIKEY,
    'Content-Length: ' . strlen($content))
);
$json_response = curl_exec($curl);
$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);
$data =  json_decode($json_response);
$error = TRUE;

$rc = $data->rc;
if($rc == 0) {
    $error = FALSE;
    $trxid = $data->trxid;
    $msisdn = $data->msisdn;
    $token = $data->token;
    $length = strlen($token);

    $first_token = substr($token, 0, -4);

    $sql = "insert into `call_data` (trx_id,msisdn,token,is_done) values ('$trxid','$msisdn','$token',0)";
    $db->query($sql);

    $return = array(
        "error" => $error,
        "trxid" => $trxid,
        "first_token" => $first_token,
        "length" => $length
    );
} else {
    $info = $data->info;
    $return = array(
        "error" => $error,
        "info" => $info
    );
}
echo json_encode($return);
