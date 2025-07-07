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
// File Name:   index.php
// File Purpose: Main controller for the website. Handles page routing and includes header/footer.
// Last Change:  June 23, 2025
//

// -- Website Session Section --
session_start();
// -- End of the Website Session Section --


// -- Core Files Section --
require_once('core/config.php');
require_once('core/header.php');
// -- End of the Core Files Section --


// -- Page Router Section --
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Whitelist of allowed page URLs from v0.2
$allowedPages = ['home', 'about', 'contact', 'player', 'movies', 'tv-shows', 'library', 'audiobooks'];
$page_to_load = 'pages/home.php'; // Default page

if (in_array($page, $allowedPages)) {
    // Convert hyphenated URL to underscore for the filename (e.g., tv-shows -> tv_shows.php)
    $filename = str_replace('-', '_', $page);
    $filePath = 'pages/' . $filename . '.php';

    if (file_exists($filePath)) {
        $page_to_load = $filePath;
    } else {
        $page_to_load = 'pages/404.php';
    }
} else {
    $page_to_load = 'pages/404.php';
}

include($page_to_load);
// -- End of the Page Router Section --


// -- Footer Section --
require_once('core/footer.php');
// -- End of the Footer Section --

?>
