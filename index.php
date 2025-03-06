<?php
if (!isset($_GET['user_id'])) {
    header("Content-Type: text/html; charset=UTF-8");
    echo "<!DOCTYPE html>";
    echo "<html lang='en'>";
    echo "<head>";
    echo "  <meta charset='UTF-8'>";
    echo "  <meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo "  <title>Steam Game News Feeds</title>";
    echo "  <style>";
    echo "    body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f8f9fa; margin: 0; }";
    echo "    .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); text-align: center; width: 320px; }";
    echo "    input { width: calc(100%); padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }";
    echo "    button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }";
    echo "    button:hover { background: #0056b3; }";
    echo "  </style>";
    echo "</head>";
    echo "<body>";
    echo "  <div class='container'>";
    echo "    <h2>Steam Game News Feeds</h2>";
    echo "    <p>Enter your Steam username or SteamID64. Your profile must be public.</p>";
    echo "    <form method='GET'>";
    echo "      <input type='text' name='user_id' placeholder='Steam username or SteamID64' required>";
    echo "      <button type='submit'>Generate OPML</button>";
    echo "    </form>";
    echo "  </div>";
    echo "</body></html>";
    exit;
}

header("Content-Type: text/xml; charset=UTF-8");

// Inclui a configuração da API
require_once "_config.php";

if (!defined('STEAM_API_KEY')) {
    die("Erro: Chave da API não definida.");
}

$userId = $_GET['user_id'];

// Se o user_id não for um número, assumimos que é um vanity URL e precisamos convertê-lo
if (!is_numeric($userId)) {
    $vanityUrl = "https://api.steampowered.com/ISteamUser/ResolveVanityURL/v1/?key=" . STEAM_API_KEY . "&vanityurl=" . urlencode($userId);
    $response = file_get_contents($vanityUrl);
    if ($response === false) {
        die("Erro ao acessar a API da Steam para converter Vanity URL.");
    }
    $data = json_decode($response, true);
    if (isset($data['response']['success']) && $data['response']['success'] == 1) {
        $userId = $data['response']['steamid'];
    } else {
        die("Erro: Não foi possível resolver o Vanity URL.");
    }
}

// Obtém os jogos da Steam do usuário
$steamApiUrl = "https://api.steampowered.com/IPlayerService/GetOwnedGames/v1/?key=" . STEAM_API_KEY . "&steamid=$userId&format=json&include_appinfo=1";
$response = file_get_contents($steamApiUrl);
if ($response === false) {
    die("Erro ao acessar a API da Steam.");
}

$data = json_decode($response, true);
$games = $data['response']['games'] ?? [];

// Gera o OPML
$opml = "<?xml version='1.0' encoding='UTF-8'?>\n";
$opml .= "<opml version='1.0'>\n";
$opml .= "  <head>\n";
$opml .= "    <title>Steam Game News Feeds</title>\n";
$opml .= "  </head>\n";
$opml .= "  <body>\n";
$opml .= "    <outline text='Steam Game News Feeds'>\n";

foreach ($games as $game) {
    $gameId = $game['appid'];
    $gameName = htmlspecialchars($game['name'], ENT_QUOTES | ENT_XML1, 'UTF-8');
    $feedUrl = "https://store.steampowered.com/feeds/news/app/$gameId/";
    $opml .= "      <outline text=\"$gameName News\" type='rss' xmlUrl='$feedUrl' />\n";
}

$opml .= "    </outline>\n";
$opml .= "  </body>\n";
$opml .= "</opml>";

echo $opml;
?>
