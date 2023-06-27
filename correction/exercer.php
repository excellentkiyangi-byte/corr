<?php

$hash = '$2y$10$qnX15WeqmfxqtoDyq0dAS.F2Q.cK2w14x4yTNUAwulrZ7B00h3DY6';
$valid = password_verify('Fqsg1986', $hash);

echo $valid ? 'Valid' : 'Not valid';