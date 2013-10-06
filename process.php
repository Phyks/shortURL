<?php
include('.config/include.php');
// Add a new line to $data
function add($that)
{
	global $_CONFIG;
	global $data;
	if (count($data) >= $_CONFIG['SAVED_URL'])
	{
		// Delete the first element
		array_shift($data);
	}
	// Add that to the array
	array_push($data, $that);
	return $data;
}

if(empty($_POST['url']))
{
	header('location : message.php?m=1');
}
else
{
	if (is_readable($_CONFIG['DATA_DIR'].$_CONFIG['ASSOC_NAME']))
		$rawData = file_get_contents($_CONFIG['DATA_DIR'].$_CONFIG['ASSOC_NAME']);
	else
	{
		touch($_CONFIG['DATA_DIR'].$_CONFIG['ASSOC_DIR']);
		$rawData = "";
	}
	if (empty($rawData))
		$data = array();
	else
		$data = unserialize($rawData);
	}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Réduit moi !</title>
		<link rel="stylesheet" media="screen" type="text/css" href="misc/design.css" />
		<link rel="icon" href="favicon.ico" />
	</head>
	<body>
		<h1>C'était trop long !</h1>
<?php

				if (isset($_POST['short']) && $_POST['short'] != "")
					$short = htmlspecialchars($_POST['short']);
				else
					$short = dechex(crc32($_POST['url']));
				if (isset($_POST['url']) && $_POST['url'] != "")
				{
					$url = htmlspecialchars($_POST['url']);
					$array = array("url"=>$url, "short"=>$short);
					// Add the association at the end of $data array
					$data = add($array);
					// Save it in the file
					file_put_contents($_CONFIG['DATA_DIR'].$_CONFIG['ASSOC_NAME'], serialize($data));
					// Echoes the result
					$new_url = $_CONFIG['BASE_URL'].'/?'.$short;
?>
					<p>Votre raccourci :<br/>
					<b><a href="<?php echo $new_url ?>"><?php echo $new_url; ?></a></b></p><p>Réduction de : <?php echo $url; ?>					<?php
				}
				else
				{
					echo "Url manquant. <a href='index.php'>Retour à l'acceuil</a>.";
				}?>
		</p>
	</body>
</html>
