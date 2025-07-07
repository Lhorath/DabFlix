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
// File Name:   functions.php
// File Purpose: Contains custom functions for use across the website.
// Last Change:  June 24, 2025
//

// -- TMDb API Functions Section --
function get_movie_data($movieTitle) {
    if (!defined('TMDB_API_KEY')) { return null; }
    $apiKey = TMDB_API_KEY;
    $query = urlencode($movieTitle);
    $apiUrl = "https://api.themoviedb.org/3/search/movie?api_key={$apiKey}&query={$query}";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $response = curl_exec($ch);
    if (curl_errno($ch)) { curl_close($ch); return null; }
    curl_close($ch);
    $data = json_decode($response, true);
    return (isset($data['results']) && count($data['results']) > 0) ? $data['results'][0] : null;
}
// -- End of TMDb API Functions Section --


// -- TheTVDB API Functions Section --
function get_tvdb_token() {
    if (isset($_SESSION['tvdb_token'])) { return $_SESSION['tvdb_token']; }
    if (!defined('TVDB_API_KEY')) { return null; }
    $apiKey = TVDB_API_KEY;
    $apiUrl = 'https://api4.thetvdb.com/v4/login';
    $postData = json_encode(['apikey' => $apiKey]);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = curl_exec($ch);
    if (curl_errno($ch)) { curl_close($ch); return null; }
    curl_close($ch);
    $data = json_decode($response, true);
    if (isset($data['data']['token'])) {
        $_SESSION['tvdb_token'] = $data['data']['token'];
        return $data['data']['token'];
    }
    return null;
}

function get_tv_show_data($showTitle) {
    $token = get_tvdb_token();
    if (!$token) { return null; }
    $query = urlencode($showTitle);
    $apiUrl = "https://api4.thetvdb.com/v4/search?query={$query}";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', "Authorization: Bearer {$token}"]);
    $response = curl_exec($ch);
    if (curl_errno($ch)) { curl_close($ch); return null; }
    curl_close($ch);
    $data = json_decode($response, true);
    return (isset($data['data']) && count($data['data']) > 0) ? $data['data'][0] : null;
}

/**
 * Fetches extended TV show data, including seasons and artwork.
 *
 * @param string $tvdb_id The TVDB ID of the show.
 * @return array|null Returns extended data if found, otherwise null.
 */
function get_tv_show_extended_data($tvdb_id) {
    $token = get_tvdb_token();
    if (!$token || !$tvdb_id) { return null; }
    $apiUrl = "https://api4.thetvdb.com/v4/series/{$tvdb_id}/extended";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', "Authorization: Bearer {$token}"]);
    $response = curl_exec($ch);
    if (curl_errno($ch)) { curl_close($ch); return null; }
    curl_close($ch);
    $data = json_decode($response, true);
    return $data['data'] ?? null;
}
// -- End of TheTVDB API Functions Section --
?>
