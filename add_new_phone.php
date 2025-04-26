<?php
header('Content-Type: application/json');

define('AUTHORIZATION_TOKEN_HASH', '9af43540ee7649049a0abfdedd934be6f9062f9a72abb3ff7f2b83818e8f69cc');
$blocked_phones_file = 'blocked_phones.txt';

$security_token = $_GET['token'] ?? $_POST['token'] ?? null;
$phone_to_add = $_GET['phone'] ?? $_POST['phone'] ?? null;

if (hash('sha256', $security_token) !== AUTHORIZATION_TOKEN_HASH) {
    echo json_encode(['error' => 'incorrect security token']);
    http_response_code(401);
    exit;
}

if (!$phone_to_add) {
    echo json_encode(['error' => 'no phone number provided']);
    http_response_code(400);
    exit;
}

$phone_to_add = preg_replace('/\D+/', '', $phone_to_add);
$blocked_phones = file('blocked_phones.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
sort($blocked_phones);
$already_blocked = false;

if (preg_match('/^8(\d{10})$/', $phone_to_add, $matched_phone) || preg_match('/^7(\d{10})$/', $phone_to_add, $matched_phone)) {
    $phone_to_add = '+7' . $matched_phone[1];

    $left_edge = 0;
    $right_edge = count($blocked_phones) - 1;

    while (($left_edge <= $right_edge) && ($already_blocked == false)) {
        $middle_point = intdiv($left_edge + $right_edge, 2);
        $blocked_phone = $blocked_phones[$middle_point];

        if ($phone_to_add == $blocked_phone) {
            $already_blocked = true;
        } elseif ($phone_to_add > $blocked_phone) {
            $left_edge = $middle_point + 1;
        } else {
            $right_edge = $middle_point - 1;
        }
    }

    if ($already_blocked) {
        echo json_encode(['error' => 'phone already exists']);
        http_response_code(409);
    } else {
        file_put_contents($blocked_phones_file, $phone_to_add . PHP_EOL, FILE_APPEND | LOCK_EX);
        echo json_encode(['status' => 'phone number added']);
        http_response_code(201);
    }
} else {
    echo json_encode(['status' => 'invalid phone number']);
    http_response_code(422);
}
?>
