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
// File Name:   about.php
// File Purpose: Contains the content for the website's "About" page.
// Last Change:  June 23, 2025
//
?>

<!-- -- About Us Section -- -->
<!-- -- This section provides information about the purpose of the website. -- -->
<div class="bg-white rounded-lg shadow-lg p-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-4">About <?= htmlspecialchars(WEBSITE_TITLE) ?></h1>

    <div class="prose max-w-none text-gray-600">
        <p>
            Welcome to Dacks-Site, your personal media center designed to bring your entire collection of movies, TV shows, and music together in one clean, easy-to-use interface. Our goal is to provide a seamless and enjoyable media consumption experience, allowing you to access your content from anywhere.
        </p>

        <h2 class="text-2xl font-bold mt-6 mb-2">Our Mission</h2>
        <p>
            In a world of scattered files and multiple streaming services, we believe in the power of owning and organizing your own media. This project was born from a desire to create a simple, yet powerful, platform that puts you in control of your library. No more searching through folders or wondering what to watch next—everything is organized, tagged, and ready for you.
        </p>

        <h2 class="text-2xl font-bold mt-6 mb-2">The Technology</h2>
        <p>
            This website is built using a combination of modern web technologies to ensure it is both robust and flexible. The backend is powered by PHP, handling the file system scanning and database interactions. We use a MySQL database to store metadata for all your media, making lookups fast and efficient. The front-end player is built with HTML5, CSS (via Tailwind CSS), and JavaScript, utilizing the powerful Howler.js library to ensure smooth and reliable audio playback across all browsers.
        </p>

    </div>
</div>
<!-- -- End of the About Us Section -- -->
