<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hide Your Data</title>
</head>
<body>
<h1>Hide Your Data</h1>

<p>The only personal data that is stored and/or appears on this site related to individual ASL players is their name, as used by them to play in a tournament and as provided to ASL Player Ratings by TDs, past, present and future.</p>

<p>Individual players have the right to request that their names not be displayed anywhere on the site. These requests will be honoured.</p>
<p>Players who request that their names not be used will not appear in any of the Player Listings, either Alphabetical or Ranked. Nor will their
    personal playing history be displayed. Game results involving such Players will show "Hidden" in place of their name in the display.</p>

<p>Players who request that they be Hidden will still be included in the database and ranked so that their oppoents can be ranked using the most complete information.</p>

<p>For those who do not wish their data to be included in the ASL Player Rating site whatsoever, there are two courses of action available.</p>

<p>1. For data submitted by TDs prior to the launch of the ASL Player Rating site, they should use this page to request that their data be removed.</p>
<p>2. For data submitted by TDs after the launch of the ASL Player Rating site, they should contact the TD of any and all tournaments in which they choose to participate. Any information provided to this site
    by TDs following its launch will be incorporated into the database and used to generate rankings. It may still be Hidden from view at the Player's choice as described previously. </p>

<p>Check the box below to Hide Your Data</p>
<form action="web/PHP/UpdateTables/updatehideplayer.php" method="post">
    Hide My Data
    <input type="checkbox" name="formHYD" value="Yes" />
    Player Name: <input type="text" name="pname" /><br />
    <input type="submit" name="formSubmit" value="Submit" />
</form>

<form action="web/PHP/UpdateTables/removemydata.php" method="post">
    Remove My Data (2007 and earlier)
    <input type="checkbox" name="formRMD" value="Yes" />
    Player Name: <input type="text" name="playername" /><br />
    Email: <input type="text" name="emailadd" /><br />
    <input type="submit" name="formSubmit" value="Submit" />
</form>
</body>
</html>
