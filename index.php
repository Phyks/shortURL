<?php
// CONFIG
// Edit this according to your needs

// The file to which we should store the data
define('DATA_FILE', 'data');
// The base URL with which you will access the script. No trailing slash.
define('BASE_URL', 'http://localhost/tinyURL');
// Max number of URLs to keep
define('MAX_SAVED_URLS', 100);
// ======

function sort_array(&$array, $key, $order=SORT_DESC) {
    $sort_keys = array();

    foreach ($array as $key2 => $entry) {
        $sort_keys[$key2] = $entry[$key];
    }


    return array_multisort($sort_keys, $order, $array);
}

if (is_readable(DATA_FILE)) {
    $data = unserialize(gzinflate(file_get_contents(DATA_FILE)));
}
else {
    $data = array();
}

// If we have exactly one GET arg, we redirect user
if (count($_GET) == 1) {
    // We get the shortened url
    $get = each($_GET);
    $short = $get['key'];
    $url = BASE_URL;
    if (array_key_exists($short, $data)) {
        $url = $data[$short]['url'];
    }
    if ($url != BASE_URL) {
        // $url is now index.php if no element was found, the right url if found
        header('location:'.$url);
        exit();
    }
}
if (empty($_GET['api'])) {
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Shorten me</title>
        <style type="text/css">
            body {
                text-align: center;
                background-color: #333;
                color: #DDD;
            }

            a:link, a:visited, a:hover, a:active {
                color: yellow;
            }

            label {
                display: block;
            }
        </style>
    </head>
    <body>
        <h1><a href="index.php">It's too long…</a></h1>
<?php
}
if(!empty($_POST['url'])) {
    if(!empty($_POST['short'])) {
        $short = htmlspecialchars($_POST['short']);
    }
    else {
        $short = dechex(crc32($_POST['url']));
    }

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

    if (empty($_GET['api'])) {
?>
                <p>Your shortened URL:<br/>
                    <strong><a href="<?php echo $new_url ?>"><?php echo $new_url; ?></a></strong>
                </p>
                <p>Short link for: <?php echo '<a href="'.$url.'">'.$url.'</a>'; ?></p>
<?php
    }
    else {
        echo $new_url;
        exit();
    }
}
else {
    if (isset($_GET['add']) && !empty($_GET['url'])) {
        $default_url = htmlspecialchars($_GET['url']);
    }
    else {
        $default_url = '';
    }

    if (!empty($_POST['short'])) {
        $default_short = htmlspecialchars($_GET['short']);
    }
    else {
        $default_short = '';
    }
?>
        <form method="post" action="index.php">
            <p>
                <label for="url">URL: </label><input type="text" size="50" name="url" id="url" value="<?php echo $default_url; ?>"/>
            </p>
            <p>
                <label for="short">Shortcut (optional): </label><input type="short" size="50" name="short" id="short" value="<?php echo $default_short;?>"/>
            </p>
            <p><input type="submit" value="Shorten !"/></p>
            <p>Add this link to your bookmarks to shorten links in one click !
                <a href="javascript:javascript:(function(){var%20url%20=%20location.href;var%20title%20=%20document.title%20||%20url;window.open('<?php echo BASE_URL; ?>/?add&amp;url='%20+%20encodeURIComponent(url),'_blank','menubar=no,height=390,width=600,toolbar=no,scrollbars=no,status=no,dialog=1');})();">Short link</a>
            </p>
        </form>
<?php
    }
?>
    </body>
</html>
