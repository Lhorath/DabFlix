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
// File Name:   tv_shows.php
// File Purpose: Displays the visual TV show library.
// Last Change:  June 24, 2025
//

// -- Database Fetch Section --
// -- This section retrieves all TV shows from the database. --
require_once(realpath(__DIR__ . '/../core/config.php'));

try {
    $stmt = $pdo->query("SELECT id, name, overview, poster_path, first_aired, status FROM tv_shows ORDER BY name ASC");
    $shows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
// -- End of Database Fetch Section --
?>

<style>
    /* Custom styles for the accordion */
    .season-content { max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; }
</style>

<!-- -- TV Show Library Section -- -->
<div class="container mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">TV Show Library</h1>

    <?php if (empty($shows)): ?>
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <p class="text-gray-600">No TV shows found in your library. Run the scanner to add some!</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-6">
            <?php foreach ($shows as $show): ?>
                <?php
                    // -- Robust Poster URL Construction --
                    $posterPath = trim($show['poster_path'] ?? '');
                    // Check if the path is already a full URL or just a partial path.
                    if (strpos($posterPath, 'http') === 0) {
                        $posterUrl = $posterPath;
                    } elseif (!empty($posterPath)) {
                        $posterUrl = 'https://artworks.thetvdb.com' . $posterPath;
                    } else {
                        // If no poster_path exists, use a placeholder.
                        $posterUrl = 'https://placehold.co/342x513/111827/ffffff?text=' . urlencode($show['name']);
                    }
                    $firstAired = $show['first_aired'] ? date('Y', strtotime($show['first_aired'])) : 'N/A';
                ?>
                <div class="show-card group cursor-pointer"
                     data-show-id="<?= $show['id'] ?>"
                     data-name="<?= htmlspecialchars($show['name']) ?>"
                     data-overview="<?= htmlspecialchars($show['overview']) ?>"
                     data-poster="<?= htmlspecialchars($posterUrl) ?>"
                     data-year="<?= htmlspecialchars($firstAired) ?>"
                     data-status="<?= htmlspecialchars($show['status']) ?>">
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300">
                        <img src="<?= htmlspecialchars($posterUrl) ?>" alt="<?= htmlspecialchars($show['name']) ?> Poster" class="w-full h-auto" onerror="this.src='https://placehold.co/342x513?text=err'">
                        <div class="p-2">
                            <h3 class="text-sm font-semibold text-gray-800 truncate group-hover:text-blue-600"><?= htmlspecialchars($show['name']) ?></h3>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<!-- -- End of TV Show Library Section -- -->


<!-- -- TV Show Info Modal -- -->
<div id="show-modal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl max-w-4xl w-full relative max-h-full overflow-y-auto flex flex-col">
        <!-- Close button -->
        <button id="modal-close-btn" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 z-20 bg-white rounded-full p-1">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <!-- Header with background image -->
        <div class="h-48 md:h-64 bg-cover bg-center" id="modal-backdrop">
            <div class="h-full w-full bg-black bg-opacity-50 flex items-end p-4">
                <h2 id="modal-title" class="text-3xl font-bold text-white"></h2>
            </div>
        </div>

        <!-- Content Area -->
        <div class="p-6 flex-grow overflow-y-auto">
            <div class="flex justify-between items-baseline mb-4">
                <p id="modal-year-status" class="text-md text-gray-500"></p>
            </div>
            <p id="modal-overview" class="text-gray-700 mb-6"></p>

            <!-- Seasons and Episodes Accordion -->
            <div id="seasons-container" class="space-y-2">
                <div class="text-center p-8"><p class="text-gray-500">Loading seasons...</p></div>
            </div>
        </div>
    </div>
</div>
<!-- -- End of TV Show Info Modal -- -->

<script>
document.addEventListener('DOMContentLoaded', () => {
    // -- Modal Control Logic --
    const modal = document.getElementById('show-modal');
    const modalCloseBtn = document.getElementById('modal-close-btn');
    const modalBackdrop = document.getElementById('modal-backdrop');
    const modalTitle = document.getElementById('modal-title');
    const modalYearStatus = document.getElementById('modal-year-status');
    const modalOverview = document.getElementById('modal-overview');
    const seasonsContainer = document.getElementById('seasons-container');
    const showCards = document.querySelectorAll('.show-card');

    showCards.forEach(card => {
        card.addEventListener('click', () => {
            const showId = card.dataset.showId;
            modalTitle.textContent = card.dataset.name;
            modalBackdrop.style.backgroundImage = `url(${card.dataset.poster})`;
            modalYearStatus.textContent = `First Aired: ${card.dataset.year} | Status: ${card.dataset.status}`;
            modalOverview.textContent = card.dataset.overview;

            modal.classList.remove('hidden');
            fetchAndDisplaySeasons(showId);
        });
    });

    async function fetchAndDisplaySeasons(showId) {
        seasonsContainer.innerHTML = '<div class="text-center p-8"><p class="text-gray-500">Loading seasons...</p></div>';
        try {
            const response = await fetch(`<?= BASE_URL ?>/core/ajax_get_episodes.php?show_id=${showId}`);
            const seasonsData = await response.json();

            seasonsContainer.innerHTML = '';
            if (Object.keys(seasonsData).length === 0) {
                seasonsContainer.innerHTML = '<p class="text-gray-500 p-4">No episodes found for this show.</p>';
                return;
            }

            for (const seasonNumber in seasonsData) {
                const season = seasonsData[seasonNumber];
                const seasonPoster = season.poster_path ? `https://artworks.thetvdb.com${season.poster_path}` : 'https://placehold.co/140x207?text=No+Poster';

                const seasonElement = document.createElement('div');
                seasonElement.innerHTML = `
                    <button class="season-header w-full text-left p-3 bg-gray-100 hover:bg-gray-200 rounded-md font-semibold flex items-center space-x-4">
                        <img src="${seasonPoster}" class="w-12 h-auto rounded-sm" onerror="this.style.display='none'">
                        <span>Season ${seasonNumber}</span>
                    </button>
                    <div class="season-content bg-gray-50 p-2 rounded-b-md"></div>
                `;
                const contentDiv = seasonElement.querySelector('.season-content');
                if (season.episodes.length > 0) {
                    season.episodes.forEach(ep => {
                        const playerLink = `<?= BASE_URL ?>/player?type=episode&id=${ep.id}`;
                        const episodeItem = document.createElement('a');
                        episodeItem.href = playerLink;
                        episodeItem.className = 'flex justify-between items-center p-2 hover:bg-gray-200 rounded-md';
                        episodeItem.innerHTML = `
                            <span>${ep.episode_number}. ${ep.title}</span>
                            <span class="text-sm bg-blue-500 text-white font-bold py-1 px-2 rounded-full">Play</span>
                        `;
                        contentDiv.appendChild(episodeItem);
                    });
                } else {
                    contentDiv.innerHTML = '<p class="p-2 text-gray-500">No episode files found for this season.</p>';
                }
                seasonsContainer.appendChild(seasonElement);
            }

            document.querySelectorAll('.season-header').forEach(header => {
                header.addEventListener('click', () => {
                    document.querySelectorAll('.season-content').forEach(c => {
                        if (c !== header.nextElementSibling) {
                             c.style.maxHeight = null;
                        }
                    });
                    const content = header.nextElementSibling;
                    if (content.style.maxHeight) {
                        content.style.maxHeight = null;
                    } else {
                        content.style.maxHeight = content.scrollHeight + "px";
                    }
                });
            });

        } catch (error) {
            console.error('Failed to fetch seasons:', error);
            seasonsContainer.innerHTML = '<p class="text-red-500 p-4">Could not load season data.</p>';
        }
    }

    function closeModal() {
        modal.classList.add('hidden');
        seasonsContainer.innerHTML = '';
    }
    modalCloseBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal(); });

    const urlParams = new URLSearchParams(window.location.search);
    const showIdToOpen = urlParams.get('open');
    if (showIdToOpen) {
        const cardToOpen = document.querySelector(`.show-card[data-show-id="${showIdToOpen}"]`);
        if (cardToOpen) {
            cardToOpen.click();
        }
    }
});
</script>
