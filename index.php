<?php
$blocked_phones = ['+71111111111', '+72222222222', '+73333333333'];
$phone_to_check = readline("Введите номер телефона для проверки: ");
$blocked = false;

foreach ($blocked_phones as $blocked_phone) {
    if ($phone_to_check == $blocked_phone) {
        $blocked = true;
    }
}

if ($blocked) {
    echo 'block';
} else {
    echo 'ok';
}
?>