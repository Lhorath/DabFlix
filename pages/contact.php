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
// File Name:   contact.php
// File Purpose: Contains the content for the website's "Contact" page.
// Last Change:  June 23, 2025
//

// -- Contact Form Logic Section --
// -- This section will handle the form submission in the future. --
$form_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // In a real application, you would process the form data here.
    // For example: sanitize input, send an email, save to database.
    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');

    // For now, we will just display a success message.
    $form_message = "Thank you, {$name}. Your message has been received!";
}
// -- End of the Contact Form Logic Section --
?>

<!-- -- Contact Form Section -- -->
<!-- -- This section displays the contact form. -- -->
<div class="bg-white rounded-lg shadow-lg p-8 max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-6 text-center">Contact Us</h1>

    <?php if (!empty($form_message)): ?>
        <!-- -- Success Message Display -- -->
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline"><?= $form_message ?></span>
        </div>
        <!-- -- End of Success Message Display -- -->
    <?php else: ?>
        <form action="<?= BASE_URL ?>/contact" method="POST">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
                <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-6">
                <label for="message" class="block text-gray-700 text-sm font-bold mb-2">Message</label>
                <textarea id="message" name="message" rows="5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Send Message
                </button>
            </div>
        </form>
    <?php endif; ?>
</div>
<!-- -- End of the Contact Form Section -- -->
