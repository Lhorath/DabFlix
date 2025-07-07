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
// File Name:   audiobooks.php
// File Purpose: Displays the visual audiobook library.
// Last Change:  June 24, 2025
//

// -- Database Fetch Section --
require_once(realpath(__DIR__ . '/../core/config.php'));

try {
    $stmt = $pdo->query("SELECT id, title, author, series FROM audiobooks ORDER BY author, series, title ASC");
    $audiobooks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
// -- End of Database Fetch Section --
?>

<!-- -- Audiobook Library Section -- -->
<div class="container mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Audiobook Library</h1>

    <?php if (empty($audiobooks)): ?>
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <p class="text-gray-600">No audiobooks found in your library. Run the scanner to add some!</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-6">
            <?php foreach ($audiobooks as $book): ?>
                <?php
                    $playerLink = BASE_URL . "/player?type=audiobook&id=" . $book['id'];
                    $posterUrl = 'https://placehold.co/500x750/1a202c/ffffff?text=' . urlencode($book['title']);
                ?>
                <div class="audiobook-card group cursor-pointer"
                     data-title="<?= htmlspecialchars($book['title']) ?>"
                     data-author="<?= htmlspecialchars($book['author']) ?>"
                     data-series="<?= htmlspecialchars($book['series'] ?? 'N/A') ?>"
                     data-poster="<?= htmlspecialchars($posterUrl) ?>"
                     data-player-link="<?= htmlspecialchars($playerLink) ?>">
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300">
                        <img src="<?= htmlspecialchars(str_replace('500x750', '342x513', $posterUrl)) ?>" alt="<?= htmlspecialchars($book['title']) ?> Poster" class="w-full h-auto" onerror="this.src='https://placehold.co/342x513?text=err'">
                        <div class="p-2">
                            <h3 class="text-sm font-semibold text-gray-800 truncate group-hover:text-blue-600"><?= htmlspecialchars($book['title']) ?></h3>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<!-- -- End of Audiobook Library Section -- -->


<!-- -- Audiobook Info Modal -- -->
<div id="audiobook-modal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl max-w-4xl w-full relative max-h-full overflow-y-auto">
        <button id="modal-close-btn" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 z-10">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <div class="flex flex-col md:flex-row">
            <div class="md:w-1/3">
                <img id="modal-poster" src="" alt="Audiobook Cover" class="w-full h-auto rounded-l-lg">
            </div>
            <div class="md:w-2/3 p-6 flex flex-col">
                <h2 id="modal-title" class="text-3xl font-bold text-gray-900 mb-2"></h2>
                <p id="modal-author" class="text-lg text-gray-600 mb-2"></p>
                <p id="modal-series" class="text-md text-gray-500 mb-4"></p>
                <a id="modal-play-button" href="#" class="mt-auto inline-block text-center bg-blue-500 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-600 transition-colors">
                    Play Now
                </a>
            </div>
        </div>
    </div>
</div>
<!-- -- End of Audiobook Info Modal -- -->

<script>
document.addEventListener('DOMContentLoaded', () => {
    // -- Modal Control Logic --
    const modal = document.getElementById('audiobook-modal');
    const modalCloseBtn = document.getElementById('modal-close-btn');
    const modalPoster = document.getElementById('modal-poster');
    const modalTitle = document.getElementById('modal-title');
    const modalAuthor = document.getElementById('modal-author');
    const modalSeries = document.getElementById('modal-series');
    const modalPlayButton = document.getElementById('modal-play-button');
    const audiobookCards = document.querySelectorAll('.audiobook-card');

    audiobookCards.forEach(card => {
        card.addEventListener('click', () => {
            modalPoster.src = card.dataset.poster;
            modalTitle.textContent = card.dataset.title;
            modalAuthor.textContent = `by ${card.dataset.author}`;
            modalSeries.textContent = card.dataset.series !== 'N/A' ? `Series: ${card.dataset.series}` : '';
            modalPlayButton.href = card.dataset.playerLink;
            modal.classList.remove('hidden');
        });
    });

    function closeModal() {
        modal.classList.add('hidden');
    }

    modalCloseBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', (event) => { if (event.target === modal) closeModal(); });
    document.addEventListener('keydown', (event) => { if (event.key === 'Escape' && !modal.classList.contains('hidden')) closeModal(); });
    // -- End of Modal Control Logic --
});
</script>
