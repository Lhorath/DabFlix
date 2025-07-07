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
// File Name:   scan_library.php
// File Purpose: Scans media directories, fetches metadata, and populates the database.
// Last Change:  June 24, 2025
//

// -- Initialization Section --
echo "<pre>";
set_time_limit(600);

require_once(realpath(__DIR__ . '/../core/config.php'));
require_once(realpath(__DIR__ . '/../core/functions.php'));

$mediaRoot = MEDIA_PHYSICAL_PATH;

echo "<h1>Library Scanner Diagnostics</h1>";
echo "---------------------------------\n";

if (!is_dir($mediaRoot)) {
    die("FATAL ERROR: The main media directory was not found.\nPath: " . htmlspecialchars($mediaRoot));
} else {
    echo "SUCCESS: Main media directory found at: " . htmlspecialchars($mediaRoot) . "\n";
}
// -- End of Initialization Section --


// -- Movie Scanning Section --
// ... (This section remains unchanged) ...
// -- End of Movie Scanning Section --


// -- TV Show Scanning Section (REVISED) --
echo "<h2>Scanning TV Shows...</h2>";
$tvShowsDir = $mediaRoot . '/TV Shows';
echo "Attempting to scan directory: " . htmlspecialchars($tvShowsDir) . "\n";

if (!is_dir($tvShowsDir)) {
    echo "--> ERROR: 'TV Shows' directory not found. Skipping.\n\n";
} else {
    echo "--> SUCCESS: 'TV Shows' directory found.\n";
    $showDirectories = new DirectoryIterator($tvShowsDir);

    foreach ($showDirectories as $showDir) {
        if ($showDir->isDir() && !$showDir->isDot()) {
            $showName = $showDir->getFilename();
            $showPath = $showDir->getPathname();
            echo "\nProcessing Show Directory: " . htmlspecialchars($showName) . "\n";

            $stmt = $pdo->prepare("SELECT id, tvdb_id FROM tv_shows WHERE directory_path = ?");
            $stmt->execute([$showPath]);
            $showRow = $stmt->fetch(PDO::FETCH_ASSOC);
            $showId = $showRow['id'] ?? null;
            $tvdb_id = $showRow['tvdb_id'] ?? null;

            if (!$showId) {
                echo "--> New show. Searching TheTVDB for: '" . htmlspecialchars($showName) . "'\n";
                $searchData = get_tv_show_data($showName);
                $tvdb_id = $searchData['tvdb_id'] ?? null;

                if ($tvdb_id) {
                    echo "--> Found TVDB ID: {$tvdb_id}. Fetching extended data...\n";
                    $showData = get_tv_show_extended_data($tvdb_id);

                    if ($showData) {
                        // -- Poster Fallback Logic --
                        $mainPoster = $showData['image'] ?? null;
                        if (!$mainPoster && isset($showData['seasons'])) {
                            foreach($showData['seasons'] as $season) {
                                if ($season['number'] > 0 && !empty($season['image'])) {
                                    $mainPoster = $season['image'];
                                    echo "--> Main poster not found. Using Season {$season['number']} poster as a fallback.\n";
                                    break;
                                }
                            }
                        }
                        // -- End of Poster Fallback --

                        $sql = "INSERT INTO tv_shows (tvdb_id, name, overview, poster_path, first_aired, status, directory_path) VALUES (?, ?, ?, ?, ?, ?, ?)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([
                            $showData['id'] ?? null,
                            $showData['name'] ?? $showName,
                            $showData['overview'] ?? null,
                            $mainPoster, // Use the potentially fallback poster
                            $showData['firstAired'] ?? null,
                            $showData['status']['name'] ?? null,
                            $showPath
                        ]);
                        $showId = $pdo->lastInsertId();
                        echo "--> SUCCESS: Added '{$showName}' to database with ID: {$showId}\n";

                        // -- Scan and Add Seasons --
                        if (isset($showData['seasons'])) {
                            foreach ($showData['seasons'] as $seasonData) {
                                if ($seasonData['type']['id'] === 1) { // Only process "Aired" seasons
                                    echo "----> Processing Season {$seasonData['number']}\n";
                                    $season_sql = "INSERT INTO seasons (show_id, season_number, name, poster_path) VALUES (?, ?, ?, ?)";
                                    $season_stmt = $pdo->prepare($season_sql);
                                    $season_stmt->execute([
                                        $showId,
                                        $seasonData['number'],
                                        $seasonData['name'] ?? "Season {$seasonData['number']}",
                                        $seasonData['image'] ?? null
                                    ]);
                                }
                            }
                            echo "--> SUCCESS: Finished processing seasons.\n";
                        }
                        // -- End of Season Scan --

                    } else {
                         echo "--> WARNING: Found an ID but could not fetch extended data. Skipping.\n";
                         continue;
                    }
                } else {
                    echo "--> WARNING: Could not find TV show on TheTVDB. Skipping episodes.\n";
                    continue;
                }
            } else {
                echo "--> Show already in database with ID: {$showId}\n";
            }

            // -- Scan for Episodes in this Show's Directory --
            $episodeFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($showPath, FilesystemIterator::SKIP_DOTS));
            foreach ($episodeFiles as $episodeFile) {
                if ($episodeFile->isDir() || !in_array(strtolower($episodeFile->getExtension()), ['mp4', 'mkv', 'avi'])) { continue; }
                $episodePhysicalPath = $episodeFile->getPathname();
                $episodeUrlPath = str_replace(DIRECTORY_SEPARATOR, '/', str_replace($mediaRoot, MEDIA_URL_PATH, $episodePhysicalPath));
                $stmt = $pdo->prepare("SELECT id FROM episodes WHERE file_path = ?");
                $stmt->execute([$episodeUrlPath]);
                if ($stmt->fetch()) { continue; }
                if (preg_match('/[Ss](\d{1,2})[Ee](\d{1,2})/', $episodeFile->getFilename(), $matches)) {
                    $seasonNumber = (int)$matches[1];
                    $episodeNumber = (int)$matches[2];
                    $episodeTitle = pathinfo($episodeFile->getFilename(), PATHINFO_FILENAME);
                    echo "--> Found Episode: S" . str_pad($seasonNumber, 2, '0', STR_PAD_LEFT) . "E" . str_pad($episodeNumber, 2, '0', STR_PAD_LEFT) . "\n";
                    $sql = "INSERT INTO episodes (show_id, season_number, episode_number, title, file_path) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$showId, $seasonNumber, $episodeNumber, $episodeTitle, $episodeUrlPath]);
                    echo "--> SUCCESS: Added episode to database.\n";
                }
            }
            // -- End of Episode Scan --
        }
    }
}
echo "TV Show scan complete.\n\n";
// -- End of TV Show Scanning Section --


// -- Music and Audiobook Sections --
// ... (These sections remain as they were in v0.2) ...
// -- End of Sections --

echo "<h2>Library scan finished.</h2>";
echo "</pre>";
?>
