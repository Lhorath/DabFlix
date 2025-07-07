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
// File Name:   library.php
// File Purpose: Provides a central hub to navigate to different media libraries.
// Last Change:  June 24, 2025
//

// -- Database Fetch Section --
// -- This section retrieves a sample of media from each library. --
require_once(realpath(__DIR__ . '/../core/config.php'));

$library_samples = [
    'movies' => [],
    'tv_shows' => [],
    'audiobooks' => []
];

try {
    // Fetch a few random movies to display
    $stmt = $pdo->query("SELECT id, title, poster_path FROM movies ORDER BY RAND() LIMIT 8");
    $library_samples['movies'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch a few random TV shows to display
    $stmt = $pdo->query("SELECT id, name, poster_path FROM tv_shows ORDER BY RAND() LIMIT 8");
    $library_samples['tv_shows'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch a few random audiobooks to display
    $stmt = $pdo->query("SELECT id, title, author FROM audiobooks ORDER BY RAND() LIMIT 8");
    $library_samples['audiobooks'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // In a real app, you would log this error. For now, we just show a message.
    $error = "Database Error: Could not fetch library samples.";
}
// -- End of Database Fetch Section --
?>

<!-- -- Library Hub Section -- -->
<div class="container mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Library</h1>

    <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="space-y-8">

        <!-- Movies Library Section -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-gray-800">Movies</h2>
                <a href="<?= BASE_URL ?>/movies" class="text-blue-500 hover:text-blue-700 font-semibold">View All &rarr;</a>
            </div>
            <?php if (!empty($library_samples['movies'])): ?>
                <div class="grid grid-cols-4 md:grid-cols-8 gap-4">
                    <?php foreach ($library_samples['movies'] as $movie): ?>
                        <a href="<?= BASE_URL ?>/player?type=movie&id=<?= $movie['id'] ?>">
                            <img src="https://image.tmdb.org/t/p/w342<?= $movie['poster_path'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>" class="rounded-md shadow-sm transform hover:scale-105 transition-transform duration-200" onerror="this.src='https://placehold.co/342x513?text=err'">
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500">No movies found in your library.</p>
            <?php endif; ?>
        </div>

        <!-- TV Shows Library Section -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-gray-800">TV Shows</h2>
                <a href="<?= BASE_URL ?>/tv-shows" class="text-blue-500 hover:text-blue-700 font-semibold">View All &rarr;</a>
            </div>
            <?php if (!empty($library_samples['tv_shows'])): ?>
                <div class="grid grid-cols-4 md:grid-cols-8 gap-4">
                    <?php foreach ($library_samples['tv_shows'] as $show): ?>
                        <?php
                            // -- Robust Poster URL Construction --
                            $posterPath = trim($show['poster_path'] ?? '');
                            if (strpos($posterPath, 'http') === 0) {
                                $posterUrl = $posterPath;
                            } elseif (!empty($posterPath)) {
                                $posterUrl = 'https://artworks.thetvdb.com' . $posterPath;
                            } else {
                                $posterUrl = 'https://placehold.co/342x513/111827/ffffff?text=' . urlencode($show['name']);
                            }
                        ?>
                        <a href="<?= BASE_URL ?>/tv-shows?open=<?= $show['id'] ?>" title="<?= htmlspecialchars($show['name']) ?>">
                             <img src="<?= htmlspecialchars($posterUrl) ?>" alt="<?= htmlspecialchars($show['name']) ?>" class="rounded-md shadow-sm transform hover:scale-105 transition-transform duration-200" onerror="this.src='https://placehold.co/342x513?text=err'">
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500">No TV shows found in your library.</p>
            <?php endif; ?>
        </div>

        <!-- Music Library Section (Placeholder) -->
        <div class="bg-white rounded-lg shadow-lg p-6 opacity-60">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Music</h2>
            <p class="text-gray-500">Music library coming soon.</p>
        </div>

        <!-- Audiobooks Library Section -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-gray-800">Audiobooks</h2>
                <!-- This link can be pointed to a new 'audiobooks.php' page in the future -->
                <a href="<?= BASE_URL ?>/audiobooks" class="text-blue-500 hover:text-blue-700 font-semibold pointer-events-none opacity-50">View All &rarr;</a>
            </div>
            <?php if (!empty($library_samples['audiobooks'])): ?>
                <div class="grid grid-cols-4 md:grid-cols-8 gap-4">
                    <?php foreach ($library_samples['audiobooks'] as $book): ?>
                        <a href="<?= BASE_URL ?>/player?type=audiobook&id=<?= $book['id'] ?>" title="<?= htmlspecialchars($book['title']) ?> by <?= htmlspecialchars($book['author']) ?>">
                             <img src="https://placehold.co/342x513/1a202c/ffffff?text=<?= urlencode($book['title']) ?>" alt="<?= htmlspecialchars($book['title']) ?>" class="rounded-md shadow-sm transform hover:scale-105 transition-transform duration-200" onerror="this.src='https://placehold.co/342x513?text=err'">
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500">No audiobooks found in your library.</p>
            <?php endif; ?>
        </div>

    </div>
</div>

<!-- -- End of Library Hub Section -- -->
