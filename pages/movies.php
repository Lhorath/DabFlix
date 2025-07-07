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
// File Name:   movies.php
// File Purpose: Displays the visual movie library with modal popups.
// Last Change:  June 23, 2025
//

// -- Database Fetch Section --
// -- This section retrieves all movies from the database. --
require_once(realpath(__DIR__ . '/../core/config.php'));

try {
    $stmt = $pdo->query("SELECT id, title, overview, poster_path, release_date FROM movies ORDER BY title ASC");
    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
// -- End of Database Fetch Section --
?>

<!-- -- Movie Library Section -- -->
<div class="container mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Movie Library</h1>

    <?php if (empty($movies)): ?>
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <p class="text-gray-600">No movies found in your library. Run the scanner to add some!</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-6">
            <?php foreach ($movies as $movie): ?>
                <?php
                    // Prepare data for the modal
                    $playerLink = BASE_URL . "/player?type=movie&id=" . $movie['id'];
                    $posterUrl = $movie['poster_path']
                        ? 'https://image.tmdb.org/t/p/w500' . $movie['poster_path']
                        : 'https://placehold.co/500x750/111827/ffffff?text=' . urlencode($movie['title']);
                    $releaseYear = $movie['release_date'] ? date('Y', strtotime($movie['release_date'])) : 'N/A';
                ?>
                <!-- This div is now the clickable item that opens the modal -->
                <div class="movie-card group cursor-pointer"
                     data-title="<?= htmlspecialchars($movie['title']) ?>"
                     data-overview="<?= htmlspecialchars($movie['overview']) ?>"
                     data-poster="<?= htmlspecialchars($posterUrl) ?>"
                     data-year="<?= htmlspecialchars($releaseYear) ?>"
                     data-player-link="<?= htmlspecialchars($playerLink) ?>">
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300">
                        <img src="<?= htmlspecialchars(str_replace('w500', 'w342', $posterUrl)) ?>" alt="<?= htmlspecialchars($movie['title']) ?> Poster" class="w-full h-auto" onerror="this.src='https://placehold.co/342x513?text=err'">
                        <div class="p-2">
                            <h3 class="text-sm font-semibold text-gray-800 truncate group-hover:text-blue-600"><?= htmlspecialchars($movie['title']) ?></h3>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<!-- -- End of Movie Library Section -- -->


<!-- -- Movie Info Modal -- -->
<div id="movie-modal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4">
    <!-- Modal content -->
    <div class="bg-white rounded-lg shadow-2xl max-w-4xl w-full relative max-h-full overflow-y-auto">
        <!-- Close button -->
        <button id="modal-close-btn" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 z-10">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <div class="flex flex-col md:flex-row">
            <!-- Poster -->
            <div class="md:w-1/3">
                <img id="modal-poster" src="" alt="Movie Poster" class="w-full h-auto rounded-l-lg">
            </div>
            <!-- Details -->
            <div class="md:w-2/3 p-6 flex flex-col">
                <h2 id="modal-title" class="text-3xl font-bold text-gray-900 mb-2"></h2>
                <p id="modal-year" class="text-md text-gray-500 mb-4"></p>
                <p id="modal-overview" class="text-gray-700 flex-grow mb-6"></p>
                <a id="modal-play-button" href="#" class="mt-auto inline-block text-center bg-blue-500 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-600 transition-colors">
                    Play Now
                </a>
            </div>
        </div>
    </div>
</div>
<!-- -- End of Movie Info Modal -- -->

<script>
document.addEventListener('DOMContentLoaded', () => {
    // -- Modal Control Logic --
    const modal = document.getElementById('movie-modal');
    const modalCloseBtn = document.getElementById('modal-close-btn');
    const modalPoster = document.getElementById('modal-poster');
    const modalTitle = document.getElementById('modal-title');
    const modalYear = document.getElementById('modal-year');
    const modalOverview = document.getElementById('modal-overview');
    const modalPlayButton = document.getElementById('modal-play-button');
    const movieCards = document.querySelectorAll('.movie-card');

    movieCards.forEach(card => {
        card.addEventListener('click', () => {
            // Populate the modal with data from the clicked card
            modalPoster.src = card.dataset.poster;
            modalTitle.textContent = card.dataset.title;
            modalYear.textContent = `Released: ${card.dataset.year}`;
            modalOverview.textContent = card.dataset.overview;
            modalPlayButton.href = card.dataset.playerLink;

            // Show the modal
            modal.classList.remove('hidden');
        });
    });

    function closeModal() {
        modal.classList.add('hidden');
    }

    // Close modal when the close button is clicked
    modalCloseBtn.addEventListener('click', closeModal);

    // Close modal when clicking outside the content area
    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });

    // Close modal with the Escape key
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
    // -- End of Modal Control Logic --
});
</script>
