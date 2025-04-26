<?php
header('Content-Type: application/json');

$blocked_phones = $blocked_phones = file('blocked_phones.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
sort($blocked_phones);
$phone_to_check = $_GET['phone'] ?? $_POST['phone'] ?? null;
$blocked = false;

if (!$phone_to_check) {
    echo json_encode(['error' => 'no phone number provided']);
    http_response_code(400);
    exit;
}

$phone_to_check = preg_replace('/\D+/', '', $phone_to_check);

if (preg_match('/^8(\d{10})$/', $phone_to_check, $matched_phone) || preg_match('/^7(\d{10})$/', $phone_to_check, $matched_phone)) {
    $phone_to_check = '+7' . $matched_phone[1];

    $left_edge = 0;
    $right_edge = count($blocked_phones) - 1;

    while (($left_edge <= $right_edge) && ($blocked == false)) {
        $middle_point = intdiv($left_edge + $right_edge, 2);
        $blocked_phone = $blocked_phones[$middle_point];

        if ($phone_to_check == $blocked_phone) {
            $blocked = true;
        } elseif ($phone_to_check > $blocked_phone) {
            $left_edge = $middle_point + 1;
        } else {
            $right_edge = $middle_point - 1;
        }
    }

    if ($blocked) {
        echo json_encode(['status' => 'block']);
        http_response_code(200);
    } else {
        echo json_encode(['status' => 'ok']);
        http_response_code(200);
    }
} else {
    echo json_encode(['status' => 'invalid phone number']);
    http_response_code(422);
}
?>
