<?php
//
// ::::    ::::    <y_bin_551>    :::::::::       :::       :::::::::
// +:+:+: :+:+:+  :+:    :+:  :+:    :+:    :+: :+:     :+:    :+:
// +:+ +:+:+ +:+  +:+         +:+    +:+   +:+   +:+    +:+    +:+
// +#+  +:+  +#+  +#+         +#+    +:+  +#++:++#++:   +#++:++#+
// +#+       +#+  +#+         +#+    +#+  +#+     +#+   +#+    +#+
// #+#       #+#  #+#    #+#  #+#    #+#  #+#     #+#   #+#    #+#
// ###       ###   ########   #########   ###     ###   #########
//
// File Name:   config.php
// File Purpose: Contains the core configuration settings for the website.
// Last Change:  June 23, 2025
//

// -- Error Reporting Section --
error_reporting(E_ALL);
ini_set('display_errors', 1);
// -- End of the Error Reporting Section --


// -- Website Details Section --
define('WEBSITE_TITLE', 'Dacks-Site');
define('WEBSITE_DESCRIPTION', 'Your Personal Media Library');
define('BASE_URL', 'http://dab.nerdygamertools.com');
// -- End of the Website Details Section --


// -- API Keys Section --
define('TMDB_API_KEY', '1396fd92211972bfdc42d194cf33e4d4');
define('TVDB_API_KEY', '0bb21847-564e-4dca-86d8-66b4fe99c4e5');
// -- End of the API Keys Section --


// -- Media Paths Section --
define('MEDIA_PHYSICAL_PATH', 'F:/Plex Library - 2024');
define('MEDIA_URL_PATH', '/media');
// -- End of Media Paths Section --


// -- Database Connection Section --
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'dabflix');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    die("ERROR: Could not connect. " . $e->getMessage());
}
// -- End of the Database Connection Section --

?>
