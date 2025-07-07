<?php
echo "<pre>"; // For clean formatting

// Use the path directly from your config
$path = 'F:/Plex Library - 2024';

echo "--- Path Diagnostics ---\n";
echo "Checking path: " . htmlspecialchars($path) . "\n\n";

if (file_exists($path)) {
    echo "SUCCESS: file_exists() found the path.\n";
} else {
    echo "FAILURE: file_exists() could NOT find the path. Check for typos.\n";
}

if (is_dir($path)) {
    echo "SUCCESS: is_dir() confirms this is a directory.\n";
} else {
    echo "FAILURE: is_dir() does NOT see this as a directory.\n";
}

if (is_readable($path)) {
    echo "SUCCESS: is_readable() confirms PHP can read this directory.\n";
} else {
    echo "FAILURE: is_readable() failed. THIS IS LIKELY A PERMISSIONS ISSUE.\n";
}

echo "\n--- PHP Configuration ---\n";
$open_basedir = ini_get('open_basedir');
if ($open_basedir) {
    echo "WARNING: PHP open_basedir restriction is active: " . htmlspecialchars($open_basedir) . "\n";
    echo "Your media path MUST be within this path for PHP to access it.\n";
} else {
    echo "INFO: No open_basedir restriction is active.\n";
}

echo "</pre>";
?>
