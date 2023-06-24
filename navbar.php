<!DOCTYPE HTML>
<html>
<head>
    <title>Scorejedi</title>
    <link rel="stylesheet" href="main2.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Add CSS styles for the navbar and footer */
        .navbar {
            background-color: #333;
            color: #fff;
            padding: 10px;
        }

        .navbar a {
            color: #fff;
            margin-right: 10px;
            text-decoration: none;
        }

        .footer {
            background-color: #f4f4f4;
            padding: 10px;
            text-align: center;
        }
    </style>
    <script>
        // Function to fetch live match data
        function fetchLiveMatches() {
            $.ajax({
                url: "https://v3.football.api-sports.io/fixtures?live=all",
                method: "GET",
                headers: {
                    "x-rapidapi-host": "v3.football.api-sports.io",
                    "x-rapidapi-key": "7c8b6208c94aaaaf7861641703244162"
                },
                success: function(data) {
                    var liveMatches = data.response;
                    var matchTable = $("#matchTable");

                    // Clear existing match data
                    matchTable.empty();

                    // Loop through live matches and update match data dynamically
                    for (var i = 0; i < liveMatches.length; i++) {
                        var match = liveMatches[i];
                        var matchDate = match.fixture.date.substring(0, 10); // Extract the first 10 characters (YYYY-MM-DD)
                        var homeTeamName = match.teams.home.name;
                        var homeTeamLogo = match.teams.home.logo;
                        var awayTeamName = match.teams.away.name;
                        var awayTeamLogo = match.teams.away.logo;
                        var score = match.goals.home + " - " + match.goals.away;
                        var elapsed = match.fixture.status.elapsed;
                        var leagueName = match.league.name;
                        var leagueLogo = match.league.logo;

                        // Create HTML elements to display match data
                        var matchTile = $("<div class='match-tile'></div>");
                        var date = $("<p>Date: " + matchDate + "</p>");
                        var league = $("<div class='league'><img data-src='" + leagueLogo + "'><p>" + leagueName + "</p></div>");
                        var homeTeam = $("<div class='team'><img data-src='" + homeTeamLogo + "'><p>" + homeTeamName + "</p></div>");
                        var goals = $("<p id='goals'>" + score + "</p>");
                        var awayTeam = $("<div class='team'><img data-src='" + awayTeamLogo + "'><p>" + awayTeamName + "</p></div>");
                        var minute = $("<p>Minute: " + elapsed + "'</p>");

                        // Append match data to the match tile
                        matchTile.append(date);
                        matchTile.append(league);
                        matchTile.append(homeTeam);
                        matchTile.append(goals);
                        matchTile.append(awayTeam);
                        matchTile.append(minute);
                        matchTable.append(matchTile);
                    }

                    // Lazy load images
                    $("img[data-src]").each(function() {
                        var img = $(this);
                        img.attr("src", img.attr("data-src"));
                        img.on("load", function() {
                            img.removeAttr("data-src");
                        });
                    });
                },
                error: function(error) {
                    console.log("Error fetching live match data: " + error);
                }
            });
        }

        // Function to fetch fixtures for the day
        function fetchFixturesForDay() {
            var today = new Date().toISOString().slice(0, 10);
            $.ajax({
                url: "https://v3.football.api-sports.io/fixtures?date=" + today,
                method: "GET",
                headers: {
                    "x-rapidapi-host": "v3.football.api-sports.io",
                    "x-rapidapi-key": "7c8b6208c94aaaaf7861641703244162"
                },
                success: function(data) {
                    var fixtures = data.response;
                    var matchTable1 = $("#matchTable1");

                    // Sort fixtures based on starting time
                    fixtures.sort(function(a, b) {
                        var timeA = new Date(a.fixture.date).getTime();
                        var timeB = new Date(b.fixture.date).getTime();
                        return timeA - timeB;
                    });

                    // Loop through fixtures and update match data dynamically
                    for (var i = 0; i < fixtures.length; i++) {
                        var fixture = fixtures[i];
                        var matchDate = fixture.fixture.date.substring(0, 10); // Extract the first 10 characters (YYYY-MM-DD)
                        var matchTime = new Date(fixture.fixture.date).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                        var homeTeamName = fixture.teams.home.name;
                        var homeTeamLogo = fixture.teams.home.logo;
                        var awayTeamName = fixture.teams.away.name;
                        var awayTeamLogo = fixture.teams.away.logo;
                        var leagueName = fixture.league.name;
                        var leagueLogo = fixture.league.logo;
                        var score = "";

                        // Check if the match has ended and set the score
                        if (fixture.fixture.status.short === "FT") {
                            score = fixture.goals.home + " - " + fixture.goals.away;
                        }

                        // Create HTML elements to display match data
                        var matchTile1 = $("<div class='match-tile1'></div>");
                        var date = $("<p>Date: " + matchDate + "</p>");
                        var time = $("<p>Time: " + matchTime + "</p>");
                        var league = $("<div class='league'><img data-src='" + leagueLogo + "'><p>" + leagueName + "</p></div>");
                        var homeTeam = $("<div class='team'><img data-src='" + homeTeamLogo + "'><p>" + homeTeamName + "</p></div>");
                        var scoreElement = $("<p class='score'>" + score + "</p>");
                        var awayTeam = $("<div class='team'><img data-src='" + awayTeamLogo + "'><p>" + awayTeamName + "</p></div>");

                        // Append match data to the match tile
                        matchTile1.append(date);
                        matchTile1.append(time);
                        matchTile1.append(league);
                        matchTile1.append(homeTeam);
                        matchTile1.append(scoreElement);
                        matchTile1.append(awayTeam);
                        matchTable1.append(matchTile1);
                    }

                    // Lazy load images
                    $("img[data-src]").each(function() {
                        var img = $(this);
                        img.attr("src", img.attr("data-src"));
                        img.on("load", function() {
                            img.removeAttr("data-src");
                        });
                    });
                },
                error: function(error) {
                    console.log("Error fetching fixtures for the day: " + error);
                }
            });
        }

        // Call the fetchLiveMatches function initially and every 10 seconds (adjust as needed)
        fetchLiveMatches();
        setInterval(fetchLiveMatches, 10000);

        // Call the fetchFixturesForDay function initially
        fetchFixturesForDay();
    </script>
</head>
<body>
    <header class="navbar">
        <h1>ScoreBoard</h1>
        <a href="#">Home</a>
        <a href="#">Live Matches</a>
        <a href="#">Fixtures</a>
        <a href="#">Results</a>
    </header>

    <div class="container">
        <div id="matchTable" class="matches-table"></div>
        <div id="matchTable1" class="matches-table1"></div>
    </div>

    <footer class="footer">
        <p>&copy; 2023 Scorejedi. All rights reserved.</p>
    </footer>
</body>
</html>
