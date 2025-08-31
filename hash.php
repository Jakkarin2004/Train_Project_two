<?php
$password = '12345678';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Hash for '$password' is:\n$hash\n";
?>
