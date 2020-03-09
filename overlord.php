<?php
function overlord() 
{
    if ($_GET['torun'] > 6 || $_GET['torun'] < 1) {
        throw new Exception();
}
    $dsn = "mysql:host=localhost;dbname=netland";
    $user = "root";
    $passwd = "";

    $pdo = new PDO($dsn, $user, $passwd);
    if ($_GET['torun'] == '1' || $_GET['torun'] == '4') {
        $request = $pdo->prepare("SELECT * FROM media WHERE id=?");
        $request->execute([$_GET['id']]);
        $to_show = $request->fetch(PDO::FETCH_ASSOC);
        if($_GET['torun'] == '4') {
            $showable_first = $to_show['duration'] . 'min';
            $showable_second = $to_show['date_release'];
            $showable_third = $to_show['country'];
            $showable_fourth = '';
            $showable_fifth = '<iframe width="420" height="315" src="https://www.youtube.com/embed/' . $to_show['ID_YT'] . '"></iframe>';
            $link_shift = 'overlord.php?id=' . $_GET['id'] . '&torun=6';
}
        else {
            $showable_first = $to_show['rating'] . '/5.0';
            $showable_second = $to_show['awards'] . PHP_EOL . 'awards won';
            $showable_third = $to_show['seasons'] . PHP_EOL . 'seasons';
            $showable_fourth = $to_show['country'] . PHP_EOL;
            $showable_fifth = '';
            $link_shift = 'overlord.php?id=' . $_GET['id'] . '&torun=3';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    <main>
        <h1>Welkom op het netland beheerderspaneel</h1>
        <h2>Hier vindt u all data over<?php echo PHP_EOL . $to_show['title']; ?>:</h2>
        <div style="display:flex; flex-direction:row; width:150px; justify-content: space-around;">
            <h3><?php echo $to_show['title']?></h3>
            <h4><?php echo $showable_first?></h4>
        </div>
        <div style="display:flex; flex-direction:row; width:150px; justify-content: space-around;">
            <h4><?php echo $showable_second?></h4>
            <h4><?php echo $showable_third?></h4>
        </div>
        <div style="display:flex; flex-direction:row; width:150px; justify-content: space-around;">
            <h4><?php echo $to_show['language'].PHP_EOL?></h4>
            <h4><?php echo $showable_fourth?></h4>
        </div>
        <div style="display:flex; flex-direction:row; width:800px;">
            <p style="width:350px;"><?php echo $to_show['description']?></p>
            <?php echo $showable_fifth?>
        </div>
        <div>
            <button onclick="location.href='<?php echo $link_shift?>'">Edit, Only admins ok? I trust you!</button>
        </div>
    </main>
</body>
</html>
        <?php
    }
else {
if ($_GET['torun'] == '3') {
    $series_request = $pdo->prepare("SELECT * FROM media WHERE id=?");
    $series_request->execute([$_GET['id']]);
    $to_show = $series_request->fetch(PDO::FETCH_ASSOC);
    $change_that_header = "Hier kunt u alle data van" . PHP_EOL . $to_show["title"] . " wijzigen:";
    $display_description = $to_show['description'];
    $line_one = '<h2>Title</h2><input type="text" name="title" value="' . $to_show['title'] . '" >';
    $line_two = '<h2>Rating</h2><input type="text" name="rating" value="' . $to_show['rating'] . '">';
    $line_three = '<h2>Amount of Awards</h2><input type="number" name="has_won_awards" value="' . $to_show['awards'] . '">';
    $line_four = '<h2>Seasons</h2><input type="number" name="seasons" value="' . $to_show['seasons'] . '">';
    $line_five = '<h2>Country of Origin</h2><input type="text" name="country" value="' . $to_show['country'] . '">';
    $line_six = '<h2>Language</h2><input type="text" name="language" value="' . $to_show['language'] . '">';
    if(isset($_POST['title'])) {
        $awards = boolval($_POST['has_won_awards']);
        $updating_series = $pdo->prepare("UPDATE media SET title=?, rating=?, description=?, awards=?, seasons=?, country=?, language=? WHERE id=?");
        $updating_series->execute(
            [$_POST['title'], 
            $_POST['rating'], 
            $_POST['description'], 
            $awards, 
            $_POST['seasons'], 
            $_POST['country'], 
            $_POST['language'], 
            $_GET['id']]
        );
    }
}
if ($_GET['torun'] == '2') {
    $change_that_header = "Hier kunt u een nieuwe serie toevoegen:";
    $display_description = 'placeholder="Hier de omschrijving"';
    $line_one = '<h2>Title</h2><input type="text" name="title" placeholder="Hier de titel">';
    $line_two = '<h2>Rating</h2><input type="text" name="rating" placeholder="Hier de rating">';
    $line_three = '<h2>Amount of Awards</h2><input type="number" name="has_won_awards" placeholder="Hier de awards">';
    $line_four = '<h2>Seasons</h2><input type="number" name="seasons" placeholder="Hier de hoeveelheid seizoenen">';
    $line_five = '<h2>Country of Origin</h2><input type="text" name="country" placeholder="Hier het land van herkomst">';
    $line_six = '<h2>Language</h2><input type="text" name="language" placeholder="Hier de gesproken taal">';
    if(isset($_POST['title'])) {
        $awards = boolval($_POST['has_won_awards']);
        $updating_series = $pdo->prepare("INSERT INTO media (title, rating, description, awards, seasons, country, language) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $updating_series->execute(
            [$_POST['title'], 
            $_POST['rating'], 
            $_POST['description'], 
            $awards, 
            $_POST['seasons'], 
            $_POST['country'], 
            $_POST['language']]
        );
    }
}
if ($_GET['torun'] == '6') {
    $series_request = $pdo->prepare("SELECT * FROM media WHERE id=?");
    $series_request->execute([$_GET['id']]);
    $to_show = $series_request->fetch(PDO::FETCH_ASSOC);
    $change_that_header = "Hier kunt u alle data van" . PHP_EOL . $to_show["title"] . " wijzigen:";
    $display_description = $to_show['description'];
    $line_one = '<h2>Title</h2><input type="text" name="title" value="' . $to_show['title'] . '" >';
    $line_two = '<h2>Duration</h2><input type="number" name="duration" value="' . $to_show['duration'] . '">';
    $line_three = '<h2>Date of release</h2><input type="date" name="date_release" value="' . $to_show['date_release'] . '">';
    $line_four = '<h2>Country of Origin</h2><input type="text" name="country" value="' . $to_show['country'] . '">';
    $line_five = '<h2>Language</h2><input type="text" name="language" value="' . $to_show['language'] . '">';
    $line_six = '<h2>Youtube Identifier</h2><input type="text" name="ID_YT" value="' . $to_show['ID_YT'] . '">';
    if(isset($_POST['title'])) {
        $updating_series = $pdo->prepare("UPDATE media SET title=?, duration=?, description=?, date_release=?, country=?, language=?, ID_YT=? WHERE id=?");
        $updating_series->execute(
            [$_POST['title'], 
            $_POST['duration'], 
            $_POST['description'], 
            $_POST['date_release'], 
            $_POST['country'], 
            $_POST['language'],
            $_POST['ID_YT'],
            $_GET['id']]
        );
    }
}
if ($_GET['torun'] == '5') {
    $change_that_header = "Hier kunt u een nieuwe film toevoegen:";
    $display_description = 'placeholder="Hier de omschrijving"';
    $line_one = '<h2>Title</h2><input type="text" name="title" placeholder="Hier de titel">';
    $line_two = '<h2>Duration</h2><input type="number" name="duration" placeholder="Hier de duur">';
    $line_three = '<h2>Date of release</h2><input type="date" name="date_release" placeholder="Hier de datum van uitkomst">';
    $line_four = '<h2>Country of Origin</h2><input type="text" name="country" placeholder="Hier het land van herkomst">';
    $line_five = '<h2>Language</h2><input type="text" name="language" placeholder="Hier de gesproken taal">';
    $line_six = '<h2>Youtube Identifier</h2><input type="text" name="ID_YT" placeholder="Hier de youtube trailer id">';
    if(isset($_POST['title'])) {
        $updating_series = $pdo->prepare("INSERT INTO media (title, duration, description, date_release, country, language, ID_YT) VALUES (?, ?, ?, ?, ?, ?)");
        $updating_series->execute(
            [$_POST['title'], 
            $_POST['duration'], 
            $_POST['description'], 
            $_POST['date_release'], 
            $_POST['country'],
            $_POST['language'], 
            $_POST['ID_YT']]
        );
    }
}

?>

<!DOCTYPE html>
<html>
<head>
<style>
.sort {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    width: 30%;
    align-self: center;
}
input:not([name=submit]) {
    text-align: center;
    width: 300px;
}
textarea[name=description] {
    resize: none;
}
</style>
</head>
<body>
    <main>
        <h1>Welkom op het netland beheerderspaneel</h1>
        <h2><?php echo $change_that_header;?></h2>
        <form method="post">
            <div class='sort'>
                <?php echo $line_one;?>
            </div>
            <div class='sort'>
                <?php echo $line_two;?>
            </div>
            <div class='sort'>
                <h2>Description</h2>
                <textarea rows="15" cols="40"type="text" name="description"
                    <?php if ($_GET['torun'] == '2' || $_GET['torun'] == '5') { echo $display_description;}?>
                    ><?php if ($_GET['torun'] == '3' || $_GET['torun'] == '6') { echo $display_description;}?></textarea>
            </div>
            <div class='sort'>
                <?php echo $line_three;?>
            </div>
            <div class='sort'>
                <?php echo $line_four;?>
            </div>
            <div class='sort'>
                <?php echo $line_five;?>
            </div>
            <div class='sort'>
                <?php echo $line_six;?>
            </div>
            <input type="submit" name='submit' value='Wijzig/Add'>
        </form>
    </main>
</body>
</html>
<?php
}
}
try {
    overlord();
} catch (Exception $e) {
    echo "Error caught; Out of Bounds";
}
?>