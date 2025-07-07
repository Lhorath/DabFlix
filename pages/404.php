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
// File Name:   404.php
// File Purpose: Contains the content for the 404 "Page Not Found" error.
// Last Change:  June 23, 2025
//
?>

<!-- -- 404 Error Section -- -->
<!-- -- This section displays the content for the 404 page. -- -->
<div class="bg-white rounded-lg shadow-lg p-8 text-center">
    <h1 class="text-6xl font-bold text-blue-500 mb-4">404</h1>
    <h2 class="text-3xl font-bold text-gray-900 mb-2">Page Not Found</h2>
    <p class="text-lg text-gray-600 mb-6">
        Sorry, the page you are looking for could not be found. It might have been removed, had its name changed, or is temporarily unavailable.
    </p>
    <a href="<?= BASE_URL ?>/home" class="inline-block bg-blue-500 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-600 transition-colors">
        Return to Homepage
    </a>
</div>
<!-- -- End of the 404 Error Section -- -->
