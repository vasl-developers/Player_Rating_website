<h2>ASL Player Rating System</h2>

<p>View player rankings, ratings, and game-by-game results. See tournament listings and game-by-game results.</p>
<p>Statistical reports by player, tournament and scenario. Check player vs player matchups.</p>
<br>

<div class="row">
  <div class="col-md-5">
    <h3>ASL Player Rating Information: Rankings and Alphabetical Display</h3>
    <div class="list-group">
      <a class="list-group-item list-group-item-action list-group-item-primary flex-fill" href="web/pages/AlphabeticalListingofActivePlayers.php">Alphabetical listing of Active Players</a>
      <a class="list-group-item list-group-item-action list-group-item-primary flex-fill" href="web/pages/RankedListingofActivePlayers.php">Ranked listing of Active Players</a>
      <a class="list-group-item list-group-item-action list-group-item-primary flex-fill" href="web/pages/AlphabeticalListingofAllPlayers.php">Alphabetical listing of All Players</a>
      <a class="list-group-item list-group-item-action list-group-item-primary flex-fill" href="web/pages/RankedListingofAllPlayers.php">Ranked listing of All Players</a>
    </div>
  </div>

  <div class="col-md-5">
    <h3>ASL Tournament Information: Tournament List and Game Results</h3>
    <div class="list-group">
      <a class="list-group-item list-group-item-action list-group-item-primary flex-fill" href="web/pages/tableTournamentsRecentlyAdded.php">Tournaments Recently Added</a>
      <a class="list-group-item list-group-item-action list-group-item-primary flex-fill" href="web/pages/tableTournamentsbyYear.php">Tournaments Included</a>
      <a class="list-group-item list-group-item-action list-group-item-primary flex-fill" href="web/pages/selectTournamentforResultsDisplay.php">Show Tournament Results</a>

    </div>
  </div>
</div>
<?php
include_once "web/pages/gamecount.php";
?>

<p>As of <?php echo $date ?>, the system contains the results of <?php echo $gamestotal ?> games</p>
<p>As of October 1, 2021, player ratings will reflect several improvements to the rating calculation method. </p>
<p>Firstly, everyone’s initial rating is now 1500. The original AREA had different starting positions (1650, 1500, 1400) for players based on ad hoc assessments at the time. Now that players have enough playing history, everyone starts from the same rating. For almost all players this produces a very minor rating change. </p>
<p>Secondly, a decay factor for players who have stopped playing for a long time has been introduced to make sure rankings are up-to-date and reflect current skills. Ratings begin to decline three years after their last tournament playing at a rate of about 35 points per year and are capped at a fraction (15%) of a player’s rating at the time decay came into effect. Thus, the effect is muted and concerns only players having stopped for a long time. The cap on the maximum total decay is based on the concept that players' skills, while slowly atrophying, never fall too far away from the last recorded rating. Decay factors are often found in ratings tools. It is hoped that this approach to skills' decay will be less opaque than others, which use very complex formulas hard for players to comprehend. It does raise again the question of whether to use another ratings tool such as Glicko in the future if there's a need for it. </p>
<p>Finally, game eligibility has been streamlined to include only competitive games played in real-time (whether FtF or VASL).</p>
<p>In combination, the impact is a very small decrease in ratings for most active players. This is primarily due to the decay factor, not so much due to the direct impact of decay, but because the ratings of some inactive opponents have decreased. Questions or comments always welcomed!</p>
<p>This tool replaces the <a href="http://asl-area.org" target="_blank">AREA rating tool</a> managed by Bruno Nitrosso until 2017. It uses the same tournament-based information and rules algorithm for determining ratings and rankings as did Bruno's AREA. It is a player rating system whose goal is to support ranking/seeding players during tournament play.</p>



<div class="row">
    <div class="col-md-5">
        <h3>ASL Top Ten Information</h3>
        <div class="list-group">
            <a class="list-group-item list-group-item-action list-group-item-primary flex-fill" href="web/pages/playertoptens.php">Player Leaders</a>
        </div>
        <div class="col-md-12 mt-3">
            <p>Which players have won the most tournament games, who has the best winning percentage? Find out this and more in these links which are based on recorded tournament games. Find the rankings behind the ratings!</p>
        </div>
    </div>
  <div class="col-md-5">
    <h3>ASL Scenario Information</h3>
    <div class="list-group">
      <a class="list-group-item list-group-item-action list-group-item-primary flex-fill" href="web/pages/mostPlayedScenarios.php">Most Played Scenarios</a>
    </div>
  </div>
</div>
