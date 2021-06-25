<?php

include '../Payment.php';
include "../config.php";

$config = new config();
$payment = new Payment();

$headers = apache_request_headers();
if ($headers['token'] !== $config->token()) {
    $result['valid'] = false;
    $result['error']['code'] = 403;
    $result['error']['message'] = 'Unauthorized';

    echo json_encode($result);
} else {
    $result = $payment->paymentInformation();

    echo json_encode($result);
}
