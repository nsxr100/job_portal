<?php
$tmpDir = ini_get('upload_tmp_dir');
echo "PHP is trying to use: " . $tmpDir . "\n";
if (is_writable($tmpDir)) {
    echo "SUCCESS: The directory is writable!";
} else {
    echo "FAILURE: The directory is NOT writable. Check permissions.";
}
?>