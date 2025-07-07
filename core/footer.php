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
// File Name:   footer.php
// File Purpose: Contains the closing HTML, footer content, and site-wide JavaScript.
// Last Change:  June 23, 2025
//
?>

    </main>
    <!-- -- End of the Main Content Section -- -->

    <!-- -- Footer Section -- -->
    <!-- -- This section contains the footer content for the website. -- -->
    <footer class="bg-white shadow-md mt-8">
        <div class="container mx-auto px-4 lg:px-6 py-6 text-center text-gray-600">
            <p>&copy; <?= date('Y') ?> <?= htmlspecialchars(WEBSITE_TITLE) ?>. All Rights Reserved.</p>
        </div>
    </footer>
    <!-- -- End of the Footer Section -- -->


    <!-- -- JavaScript Section -- -->
    <!-- -- Contains JavaScript for site functionality, like the mobile menu. -- -->
    <script>
        // JavaScript for toggling the mobile menu
        const menuButton = document.getElementById('mobile-menu-button');
        const mainMenu = document.getElementById('main-menu');

        if (menuButton && mainMenu) {
            menuButton.addEventListener('click', () => {
                mainMenu.classList.toggle('hidden');
            });
        }
    </script>
    <!--
        NOTE: For better organization, the script above should be moved
        into your /style/js/main.js file and linked here instead.
        <script src="<?= BASE_URL ?>/style/js/main.js"></script>
    -->
    <!-- -- End of the JavaScript Section -- -->

</body>
</html>
