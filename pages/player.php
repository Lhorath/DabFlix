<?php
require_once(realpath(__DIR__ . '/../core/config.php'));

$mediaUrl = null;
$mediaTitle = "Player";
$posterUrl = null;
$prevEpisodeId = null;
$nextEpisodeId = null;

$type = $_GET['type'] ?? null;
$id = (int)($_GET['id'] ?? 0);

if ($type && $id > 0) {
    $tableName = '';
    $isEpisode = false;
    switch ($type) {
        case 'movie': $tableName = 'movies'; break;
        case 'episode': $tableName = 'episodes'; $isEpisode = true; break;
        case 'music': $tableName = 'music'; break;
        case 'audiobook': $tableName = 'audiobooks'; break;
    }

    if ($tableName) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM `{$tableName}` WHERE id = ?");
            $stmt->execute([$id]);
            $mediaItem = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($mediaItem) {
                $mediaUrl = $mediaItem['file_path'];
                $mediaTitle = $mediaItem['title'];
                if (isset($mediaItem['poster_path'])) {
                    $posterUrl = 'https://image.tmdb.org/t/p/original' . $mediaItem['poster_path'];
                }

                if ($isEpisode) {
                    $showId = $mediaItem['show_id'];
                    $seasonNum = $mediaItem['season_number'];
                    $epNum = $mediaItem['episode_number'];

                    $prevStmt = $pdo->prepare("SELECT id FROM episodes WHERE show_id = ? AND season_number = ? AND episode_number = ?");
                    $prevStmt->execute([$showId, $seasonNum, $epNum - 1]);
                    $prevEpisodeId = $prevStmt->fetchColumn();

                    $nextStmt = $pdo->prepare("SELECT id FROM episodes WHERE show_id = ? AND season_number = ? AND episode_number = ?");
                    $nextStmt->execute([$showId, $seasonNum, $epNum + 1]);
                    $nextEpisodeId = $nextStmt->fetchColumn();
                }
            }
        } catch (PDOException $e) {
            die("Database Error: " . $e->getMessage());
        }
    }
}

if (!$mediaUrl) {
    $mediaTitle = "Media Not Found";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($mediaTitle) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Shaka Player & Cast SDK -->
    <script src="https://cdn.jsdelivr.net/npm/shaka-player@4.3.4/dist/shaka-player.ui.js"></script>
    <script src="https://www.gstatic.com/cv/js/sender/v1/cast_sender.js?loadCastFramework=1"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/shaka-player@4.3.4/dist/controls.css">

    <style>
    /* Force show volume control on all screen sizes */
.shaka-controls-container .shaka-volume-bar {
    display: flex !important;
}

@media (max-width: 640px) {
    .shaka-controls-container .shaka-volume-bar {
        display: flex !important;
        width: 80px;
    }
}

        .player-wrapper {
            width: 100%;
            max-width: 1280px;
            margin: auto;
            padding: 1rem;
        }
        .shaka-player-container {
            width: 100%;
            padding-top: 56.25%;
            position: relative;
            background-color: #000;
        }
        .shaka-player-container video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        .nav-button {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background-color: #3b82f6;
            color: white;
            font-weight: bold;
            border-radius: 0.5rem;
            transition: background-color 0.2s;
        }
        .nav-button:hover {
            background-color: #2563eb;
        }
        .nav-button.disabled {
            background-color: #9ca3af;
            cursor: not-allowed;
        }
        @media (max-width: 640px) {
            .nav-button {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }
            .player-wrapper {
                padding: 1rem;
            }
        }
        .shaka-controls-button {
            min-width: 48px;
            min-height: 48px;
        }
    </style>
</head>
<body>

<div class="player-wrapper">
    <h1 class="text-2xl font-bold text-gray-900 mb-4"><?= htmlspecialchars($mediaTitle) ?></h1>

    <?php if ($mediaUrl): ?>
        <div data-shaka-player-container class="shaka-player-container rounded-lg shadow-lg overflow-hidden">
            <video autoplay muted playsinline data-shaka-player id="video" poster="<?= htmlspecialchars($posterUrl ?? '') ?>"></video>
        </div>

        <div id="episode-nav" class="hidden flex justify-between mt-4">
            <a id="prev-episode-btn" href="#" class="nav-button disabled">Previous Episode</a>
            <a id="next-episode-btn" href="#" class="nav-button disabled">Next Episode</a>
        </div>

    <?php else: ?>
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <h2 class="text-xl font-semibold text-red-600">Error</h2>
            <p class="text-gray-600 mt-2">The requested media could not be loaded. It may have been removed or the link is incorrect.</p>
            <a href="<?= BASE_URL ?>/movies" class="inline-block mt-4 bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-600">Return to Library</a>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const mediaUrl = <?= json_encode($mediaUrl) ?>;
    const mediaTitle = <?= json_encode($mediaTitle) ?>;
    const prevEpisodeId = <?= json_encode($prevEpisodeId) ?>;
    const nextEpisodeId = <?= json_encode($nextEpisodeId) ?>;

    const videoElement = document.getElementById('video');
    const playerContainer = videoElement.parentElement;
    let player;

    function initPlayer() {
        shaka.polyfill.installAll();
        if (shaka.Player.isBrowserSupported()) {
            player = new shaka.Player(videoElement);

            const uiConfig = {
                controlPanelElements: [
                    'play_pause',
                    'rewind',
                    'fast_forward',
                    'time_and_duration',
                    'progress_bar',
                    'spacer',
                    'volume',
                    'fullscreen',
                    'overflow_menu'
                ],
                overflowMenuButtons: [
                    'captions',
                    'quality',
                    'language',
                    'playback_rate',
                    'picture_in_picture'
                ],
                addSeekBar: true,
                addBigPlayButton: true,
                enableKeyboardPlaybackControls: true,
                showUnbufferedStart: true
            };

            const ui = new shaka.ui.Overlay(player, playerContainer, videoElement);
            ui.configure(uiConfig);

            player.addEventListener('error', e => console.error('Shaka Player Error:', e.detail));

            player.load(mediaUrl).then(() => {
                console.log(`Successfully loaded: ${mediaTitle}`);
            }).catch(e => console.error('Error loading media:', e));
        } else {
            console.error('Browser not supported!');
        }
    }

    function setupEpisodeNavigation() {
        const episodeNav = document.getElementById('episode-nav');
        const prevBtn = document.getElementById('prev-episode-btn');
        const nextBtn = document.getElementById('next-episode-btn');

        if (prevEpisodeId || nextEpisodeId) {
            episodeNav.classList.remove('hidden');
            if (prevEpisodeId) {
                prevBtn.href = "<?= BASE_URL ?>/player?type=episode&id=" + prevEpisodeId;
                prevBtn.classList.remove('disabled');
            }
            if (nextEpisodeId) {
                nextBtn.href = "<?= BASE_URL ?>/player?type=episode&id=" + nextEpisodeId;
                nextBtn.classList.remove('disabled');
            }
        }
    }

    if (mediaUrl && videoElement) {
        initPlayer();
        setupEpisodeNavigation();
    }
});
</script>

</body>
</html>
