<?php
require('config.php');

function sort_array(&$array, $key, $order=SORT_DESC) {
    $sort_keys = array();

    foreach ($array as $key2 => $entry) {
        $sort_keys[$key2] = $entry[$key];
    }


    return array_multisort($sort_keys, $order, $array);
}

if(empty($_POST['url'])) {
    header('location : index.php');
}
else {
    if (is_readable(DATA_FILE)) {
        $data = unserialize(gzinflate(file_get_contents(DATA_FILE)));
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
        <title>Shorten me !</title>
        <style type="text/css">
            body {
                text-align: center;
                background-color: #333;
                color: #DDD;
            }

            a:link, a:visited, a:hover, a:active {
                color: yellow;
            }
        </style>
    </head>
    <body>
        <h1><a href="index.php">It was too long !</a></h1>
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

    if (count($data) >= MAX_SAVED_URLS)
    {
        // Delete the first element
        sort_array($data, 'timestamp');
        array_shift($data);
    }

    // Store short link in the data array
    $data[$short] = array('timestamp'=>time(), 'url'=>$url);

    // Save it in the file
    file_put_contents(DATA_FILE, gzdeflate(serialize($data)));

    // Echoes the result
    $new_url = BASE_URL.'/?'.$short;
?>
                    <p>Your shorten URL:<br/>
                        <strong><a href="<?php echo $new_url ?>"><?php echo $new_url; ?></a></strong>
                    </p>
                    <p>Short link for: <?php echo '<a href="'.$url.'">'.$url.'</a>'; ?></p>
<?php
}
else {
    echo "<p>Missing URL. <a href='index.php'>Back to index page</a>.</p>";
}
?>
    </body>
</html>
