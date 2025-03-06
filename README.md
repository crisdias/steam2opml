# Steam Game News OPML Generator

This PHP script generates an OPML file containing RSS news feeds for all games owned by a given Steam user. Users can enter their Steam username or SteamID64, and the script retrieves their game library via the Steam API, formatting the results into an OPML document with links to each game's news feed.

## Features
- Converts Steam vanity usernames to SteamID64 automatically
- Fetches the user's owned games from the Steam API
- Generates an OPML file with RSS news feed links for each game
- Provides a simple HTML form for users to enter their Steam ID
- Responsive and minimalist design

## Requirements
- PHP 7.4+
- A valid Steam API key
- A web server

## Installation
1. Clone this repository or download the script files.
2. Create a `_config.php` file in the project root with the following content:
   ```php
   <?php
   define('STEAM_API_KEY', 'your_steam_api_key_here');
   ?>
   ```
3. Deploy the script to your web server.
4. Access the script via a web browser and enter a Steam username or SteamID64.

## Usage
- Open the script in a web browser.
- Enter your Steam username or SteamID64.
- Click "Generate OPML".
- The script will fetch your owned games and provide an OPML file with links to the Steam news feeds for each game.

## Example OPML Output
```xml
<?xml version='1.0' encoding='UTF-8'?>
<opml version='1.0'>
  <head>
    <title>Steam Game News Feeds</title>
  </head>
  <body>
    <outline text='Steam Game News Feeds'>
      <outline text='Half-Life News' type='rss' xmlUrl='https://store.steampowered.com/feeds/news/app/70/' />
      <outline text='Portal 2 News' type='rss' xmlUrl='https://store.steampowered.com/feeds/news/app/620/' />
    </outline>
  </body>
</opml>
```

## Notes
- The Steam profile must be **public** for the script to retrieve the game list.
- If the user enters a vanity username, the script converts it to a SteamID64 before querying the Steam API.

## License
This project is open-source and licensed under the MIT License.
