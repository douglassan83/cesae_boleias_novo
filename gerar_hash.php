<?php
$senha = 'admin@cesae.pt';
echo password_hash($senha, PASSWORD_BCRYPT) . "\n";
echo "Use este hash no INSERT.\n";
?>
