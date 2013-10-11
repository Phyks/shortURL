<?php
require('config.php');

if (is_readable(DATA_FILE))
{
    $data = unserialize(gzinflate(file_get_contents(DATA_FILE)));
}
else
    $data = array();

// If we don't have exactly one $_GET arg, we print a default page
if (count($_GET) != 1) {
    if (!empty($_POST['url']) || (isset($_GET['add']) && !empty($_GET['url']))) {
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
    <!DOCTYPE html>
    <html lang="fr">
        <head>
            <meta charset="utf-8">
            <title>Shorten me</title>
            <link rel="stylesheet" media="screen" type="text/css" href="design.css" />
        </head>
        <body>
            <h1>It's too long…</h1>
            <form method="post" action="process.php">
                <p>
                    <label for="url">URL: </label><input type="text" size="50" name="url" id="url" value="<?php echo $default_url; ?>"/>
                </p>
                <p>
                    <label for="short">Shortcut (optional): </label><input type="short" size="50" name="short" id="short" value="<?php echo $default_short;?>"/>
                </p>
                <p><input type="submit" value="Shorten !"/></p>
                <p>Add this link to your bookmarks to shorten links in one click ! 
                    <a href="javascript:javascript:(function(){var%20url%20=%20location.href;var%20title%20=%20document.title%20||%20url;window.open('<?php echo BASE_URL; ?>/?add&url='%20+%20encodeURIComponent(url),'_blank','menubar=no,height=390,width=600,toolbar=no,scrollbars=no,status=no,dialog=1');})();">Short link</a>
                </p>
            </form>
        </body>
    </html>
<?php
}
// Else, we redirect the visitor to the right URL
else {
    // We get the shortened url
    $get = each($_GET);
    $short = $get['key'];
    $url = BASE_URL;
    if (array_key_exists($short, $data)) {
        $url = $data[$short]['url'];
    }
    // $url is now index.php if no element was found, the right url if found
    header('location:'.$url);
}
?>
