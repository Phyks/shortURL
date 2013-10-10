<?php
require('config.php');
// Add a new line to $data
function add($that) {
    global $data;
    if (count($data) >= MAX_SAVED_URLS)
    {
        // Delete the first element
        array_shift($data);
    }
    // Add that to the array
    array_push($data, $that);
    return $data;
}

if(empty($_POST['url'])) {
    header('location : index.php');
}
else {
    if (is_readable(DATA_FILE)) {
        $data = unserialize(gzinflate(file_get_contents(DATA_DIR.ASSOC_NAME)));
    }
    else {
        $data = array(); 
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Shorten me !<title>
        <link rel="stylesheet" media="screen" type="text/css" href="design.css" />
    </head>
    <body>
        <h1>It was too long !</h1>
<?php
if (isset($_POST['short']) && $_POST['short'] != "") {
    $short = htmlspecialchars($_POST['short']);
}
else {
    $short = dechex(crc32($_POST['url']));
}
if (isset($_POST['url']) && $_POST['url'] != "") {
    $url = htmlspecialchars($_POST['url']);
    $array = array("url"=>$url, "short"=>$short);
    // Add the association at the end of $data array
    $data = add($array);
    // Save it in the file
    file_put_contents(DATA_FILE, gzdeflate(serialize($data)));
    // Echoes the result
    $new_url = $BASE_URL.'/?'.$short;
?>
                    <p>Your shorten URL:<br/>
                        <strong><a href="<?php echo $new_url ?>"><?php echo $new_url; ?></a></strong>
                    </p>
                    <p>Short link for:<?php echo '<a href="'.$url.'">'.$url.'</a>'; ?></p>
<?php
}
else {
    echo "<p>Missing URL. <a href='index.php'>Back to index page</a>.</p>";
}
?>
    </body>
</html>
