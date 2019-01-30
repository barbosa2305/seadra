<?php

$pwd_user_new_hash = password_hash('admin', PASSWORD_DEFAULT);
echo 'admin: ' . $pwd_user_new_hash . '<br/>';

$pwd_user_new_hash = password_hash('admin123', PASSWORD_DEFAULT);
echo 'admin123: ' . $pwd_user_new_hash. '<br/>';

$pwd_user_new_hash = password_hash('12345678', PASSWORD_DEFAULT);
echo '12345678: ' . $pwd_user_new_hash . '<br/>';

$pwd_user_new_hash = password_hash('@seadraadm', PASSWORD_DEFAULT);
echo '@seadraadm: ' . $pwd_user_new_hash . '<br/>';
