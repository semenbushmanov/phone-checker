<?php
$blocked_phones = ['+71111111111', '+72222222222', '+73333333333', '+74444444444', '+75555555555', '+76666666666', '+77777777777'];
$phone_to_check = readline("Input a phone number to check if it's blocked: ");
$blocked = false;

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
        echo 'block';
    } else {
        echo 'ok';
    }
} else {
    echo 'Invalid phone number';
}
?>
