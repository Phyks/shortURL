<?php
include('.config/include.php');
if (is_readable($_CONFIG['DATA_DIR'].$_CONFIG['ASSOC_NAME']))
{
    $rawData = file_get_contents($_CONFIG['DATA_DIR'].$_CONFIG['ASSOC_NAME']);
    $data = unserialize($rawData);
}
else
    $data = array();

// If we don't have exactly one $_GET arg, we print a default page
if (count($_GET) > 1 || count($_GET) < 1)
{ 
    if (isset($_GET['add']) && $_GET['url'])
        $default_url = htmlspecialchars($_GET['url']);
    else
        $default_url = "";
?>
            <!DOCTYPE html>
            <html lang="fr">
                <head>
                    <meta charset="utf-8">
                    <title>Réduis moi!</title>
                    <link rel="stylesheet" media="screen" type="text/css" href="misc/design.css" />
                    <link rel="icon" href="favicon.ico" />
                </head>
                <body>
                    <h1>C'est trop long ...</h1>
                    <form method="post" action="process.php">
                        <p>
                        <label for="url">URL : </label><input type="text" size="50" name="url" id="url" value="<?php echo $default_url; ?>"/>
                        </p>
                        <p>
                            <label for="short">Raccourci (optionnel): </label><input type="short" size="50" name="short" id="short"/>
                        </p>
                        <p><input type="submit" value="... raccourcir !"/></p>
                        <p>Ajouter ce lien pour réduire les URL en un seul clic :
                            <a href="javascript:javascript:(function(){var%20url%20=%20location.href;var%20title%20=%20document.title%20||%20url;window.open('<?php echo $_CONFIG['BASE_URL']; ?>/?add&url='%20+%20encodeURIComponent(url),'_blank','menubar=no,height=390,width=600,toolbar=no,scrollbars=no,status=no,dialog=1');})();">Réduis moi !</a>
                        </p>
                    </form>
                </body>
            </html>
<?php
}
// Else, we redirect the visitor to the right URL
else
{
    // We get the shortened url
    $get = each($_GET);
    $short = $get['key'];
    $url = $_CONFIG['BASE_URL'];
    foreach($data as $array)
    {
        if ($array['short'] == $short)
        {
            $url = $array['url'];
            break;
        }
    }
    // $url is now index.php if no element was found, the right url if found
    header('location:'.$url);
}
?>
