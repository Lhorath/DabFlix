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
// File Name:   header.php
// File Purpose: Contains the opening HTML, head section, and navigation for the site.
// Last Change:  June 23, 2025
//
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars(WEBSITE_TITLE) ?></title>
    <meta name="description" content="<?= htmlspecialchars(WEBSITE_DESCRIPTION) ?>">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.4/howler.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">

    <!-- -- Header and Navigation Section -- -->
    <header class="bg-white shadow-md">
        <nav class="container mx-auto px-4 lg:px-6 py-4 flex justify-between items-center">

            <!-- Logo -->
            <a href="<?= BASE_URL ?>" class="flex items-center">
                <img src="<?= BASE_URL ?>/style/images/logo.png" class="h-8 mr-3" alt="Dacks-Site Logo" onerror="this.onerror=null;this.style.display='none';this.nextSibling.style.display='block';">
                <span class="self-center text-xl font-semibold whitespace-nowrap hidden"><?= htmlspecialchars(WEBSITE_TITLE) ?></span>
            </a>

            <!-- Mobile Menu Button (Hamburger) -->
            <button id="mobile-menu-button" class="lg:hidden p-2 rounded-md text-gray-700 hover:text-gray-900 hover:bg-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            </button>

            <!-- Navigation Links -->
            <div id="main-menu" class="hidden lg:flex lg:items-center lg:w-auto">
                <ul class="flex flex-col lg:flex-row lg:space-x-8 mt-4 lg:mt-0 font-medium">
                    <li>
                        <a href="<?= BASE_URL ?>/home" class="block py-2 pr-4 pl-3 text-gray-700 rounded hover:bg-gray-100 lg:hover:bg-transparent lg:border-0 lg:hover:text-blue-700 lg:p-0">Home</a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>/library" class="block py-2 pr-4 pl-3 text-gray-700 rounded hover:bg-gray-100 lg:hover:bg-transparent lg:border-0 lg:hover:text-blue-700 lg:p-0">Library</a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>/about" class="block py-2 pr-4 pl-3 text-gray-700 rounded hover:bg-gray-100 lg:hover:bg-transparent lg:border-0 lg:hover:text-blue-700 lg:p-0">About</a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>/contact" class="block py-2 pr-4 pl-3 text-gray-700 rounded hover:bg-gray-100 lg:hover:bg-transparent lg:border-0 lg:hover:text-blue-700 lg:p-0">Contact</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- -- End of the Header and Navigation Section -- -->

    <!-- -- Main Content Section -- -->
    <main class="container mx-auto p-4 md:p-8">
