# tennis-match-scoreboard

## Project "Tennis match scoreboard"

A web application that implements a tennis match counter table.

## Features
- PHP 8.1
- MySQL 8.0
- Slim microframework
- Redis
- Twig
- Clean HTML and CSS

## Project motivation

- Create a client-server application with a web interface.
- Get hands-on experience with Doctirne ORM.
- Create a simple web interface without third-party libraries.
- Gain hands-on experience with Redis key-value storage.
- Get hands-on experience with Twig.
- Get hands-on experience tests with PHP-Unit.
- Familiarize yourself with the MVC(S) architectural pattern.

## Application functionality

Working with tennis matches:

- Creating a new tennis match
- View completed matches, search for matches by player names
- Scoring in the current match

## Scoring a tennis match

Tennis has a special scoring system - https://www.gotennis.ru/read/world_of_tennis/pravila.html.

- The match is played to two sets (best of 3)
- If the set score is 6/6, a tiebreaker is played to 7 points.

## Application interface

### Homepage

- Links leading to new match pages and a list of completed matches

### New match page

The address is `/new-match`.

Interface:
- HTML form with the fields “Player Name 1”, “Player Name 2” and the “Start” button.
- Clicking the Start button will cause a POST request to be sent to `/new-match`.

POST request handler:
- Checks the presence of players in the “Players” table. If a player with that name does not exist, create
- Create an instance of the GameMatchScore class (containing player names and current scores) and place it in the current matches collection (which only exists in Redis key value storage). The collection key is the UUID, the value is an instance of the GameMatchScore class.
- Redirect to the page `/match-score?uuid=$match_id`

### Match score page - `/match-score`

Address: `/match-score?uuid=$match_id`. The GET `uuid` parameter contains the UUID of the match.

Interface:
- Table with player names, current score
- Forms and action buttons - “player 1 won the current point”, “player 2 won the current point”
- Clicking on the buttons leads to a POST request to the address `/match-score?uuid=$match_id`, the fields of the submitted form contain of the player who won the point

POST request handler:
- Retrieves an instance of the Match class from the collection.
- Updates the match score depending on which player scores the point.
- If the match has not yet ended, the match score table is displayed using the buttons described above.
- If the match ends:
       - Remove a match from the collection of current matches
       - Write the completed match to the SQL database.
       - Display final score

### Played matches page - `/matches`

Address: `/matches?page=$page_number&filter_by_player_name=$player_name`. GET parameters:
- `page` - page number. If the parameter is not specified, the first page is assumed.
— `filter_by_player_name` — the name of the player whose matches we are looking for. If the parameter is not specified, all matches are displayed.

Displays a page-by-page list of matches played. Allows you to search for a player's matches by his name. For pagination, page numbering will be required.

Interface:
- Form with filter by player name. Name input field and Search button. When clicked, a GET request of the form `/matches?filter_by_player_name=${NAME}` is generated.
- List of found matches
- Switch pages if more matches are found than fit on one page.

## Tests

- If player 1 wins a point when the score is 40-40, the game will not end.
- If player 1 wins a point when the score is 40-0, then he wins and plays.
- When the score is 6-6, a tiebreak begins instead of a regular game
