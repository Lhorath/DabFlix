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
// File Name:   home.php
// File Purpose: Contains the content for the website's homepage.
// Last Change:  June 23, 2025
//

?>

<!-- -- Hero Section -- -->
<!-- -- This section contains the main welcome message for the homepage. -- -->
<div class="bg-white rounded-lg shadow-lg p-8 text-center">
    <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4">Welcome to <?= htmlspecialchars(WEBSITE_TITLE) ?></h1>
    <p class="text-lg text-gray-600 mb-6"><?= htmlspecialchars(WEBSITE_DESCRIPTION) ?></p>
    <a href="<?= BASE_URL ?>/library" class="inline-block bg-blue-500 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-600 transition-colors">
        Go to Library
    </a>
</div>
<!-- -- End of the Hero Section -- -->


<!-- -- Features Section -- -->
<!-- -- This section highlights the key features of the application. -- -->
<div class="mt-12 grid md:grid-cols-3 gap-8">

    <!-- Feature 1: TV Shows -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-blue-500 text-white mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
        </div>
        <h3 class="text-xl font-bold mb-2">TV Shows</h3>
        <p class="text-gray-600">Browse your collection of TV shows. All your favorite series in one place, organized and ready to watch.</p>
    </div>

    <!-- Feature 2: Movies -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-blue-500 text-white mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
        </div>
        <h3 class="text-xl font-bold mb-2">Movies</h3>
        <p class="text-gray-600">Your entire movie library at your fingertips. From classic films to the latest blockbusters.</p>
    </div>

    <!-- Feature 3: Music -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-blue-500 text-white mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-12c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2z"></path></svg>
        </div>
        <h3 class="text-xl font-bold mb-2">Music</h3>
        <p class="text-gray-600">Listen to your favorite artists and albums. Create playlists and enjoy your personal soundtrack.</p>
    </div>

</div>
<!-- -- End of the Features Section -- -->
