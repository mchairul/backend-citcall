<?php
/*
|-------------------------------------------------------------------
| VERIFICATION
|-------------------------------------------------------------------
| example of call verification
|
*/
header("Content-type: application/json");

$input = file_get_contents('php://input');
$json = json_decode($input);

include "config.php";
$sql = "SELECT trx_id FROM call_data WHERE trx_id='" . $json->trxid . "' AND token='" . $json->code . "' and is_done = 0";

$result = $db->query($sql);
$error = TRUE;
$info = "invalid token";
if ($result->num_rows > 0) {
     $sqlu = "UPDATE call_data SET is_done  = 1 WHERE trx_id = '" . $json->trxid . "'";
     $db->query($sqlu);
     $info = "ok";
     $error = FALSE;
}

$db->close();

$resp=array(
     "error" => $error,
     "info" => $info
);
echo json_encode($resp);
