<?php
	require 'config.php';
	require_once 'php/Tools.php';
	require_once 'php/Track.php';
	require_once 'php/Artist.php';
	require_once 'php/Artist_Track.php';

	if (isset($_GET['action'])) {
		switch ($_GET['action']) {
			case 'getAllOnPage':
				$tracks = json_decode(file_get_contents('php://input'), true);
				if ($tracks) {
					try {
						foreach ($tracks as $item) {
							Track::insert($item);
						}
					} catch (Exception $e) {
						echo $e->getMessage();
					}
				} else {
					echo 'Никаких треков не обнаружено!';
				}
			break;
			case 'getByArtist':
				$artists = json_decode(file_get_contents('php://input'), true);
				var_dump($artists);

				if ($artists) {
					try {
						foreach ($artists as $item) {
							if (!is_null($item['name'])) {
								$a = str_replace(array("\r\n", "\r", "\n", "\t"), '',  strip_tags($item['name']));
								if (!Artist::find(['name', 'LIKE', "%{$a}%"])) {
									Artist::insert([$a, '', $item['href']]);
								}
							}
						}
					} catch (Exception $e) {
						echo $e->getMessage();
					}
				}
			break;
			case 'ALL_ARTISTS':
				var_dump(json_decode(file_get_contents('php://input'), true));
			break;
			default:
				$tr = Track::findAll();
				foreach ($tr as $item) {
					echo "{$item->artist()->name()} - {$item->title()}   {$item->duration()}<br/>";
				}
			break;
		}
	}
	$qs = explode("/", $_SERVER["REQUEST_URI"]);
	unset($qs[0]);
	if ($qs[1] == 'api') {
		switch($qs[2]) {
			case 'track':
				// var_dump($qs);
				if (isset($qs[3])) {
					if ($qs[3] == 'getTrack') {
						$tracks = Track::getTrack($qs[4]);
						foreach ( $tracks as $item ) {
							echo "<a href='{$item->href()}' class='track'><span class='byArtist'>{$item->artist()}</span><span class='byName'>{$item->title()}</span><span class='duration'>{$item->duration()}</span></a>";
						}
						// var_dump(  );
					} else {
						$data = json_decode(file_get_contents('php://input'), true);
						Track::{$qs[3]}($data);
					}
				} else {
					$tracks = Track::findAll();
					echo "<div class='tracklist'>";
						foreach ( $tracks as $item ) {
							echo "<a href='{$item->href()}' class='track'><span class='byArtist'>{$item->artist()}</span><span class='byName'>{$item->title()}</span><span class='duration'>{$item->duration()}</span></a>";
						}
					echo '</div>';
				}
			break;
		}
	}
?>