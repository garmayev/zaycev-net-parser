<?php
	class Artist_Track {
		const tableName = 'artist_track';
		public function __construct() {

		}

		public static function setLink($artist, $track) {
			$pdo = Tools::getPDO();
			$sth = $pdo->prepare("INSERT INTO ".self::tableName." (`artist_id`, `track_id`) VALUES (:artist, :track)");
			if ($sth->execute(['artist' => $artist, 'track' => $track])) {
				echo "Link artist {$artist} for track {$track} is set";
			} else {
				throw new Exception("Системе не удалось установить связь между Артистом {$artist} и треком {$track}".print_r("INSERT INTO {self::tableName} (`artist_id`, `track_id`) VALUES (:artist, :track)"), 1);
				
			}
		}

		public static function checkLink( $artist, $track ) {
			$pdo = Tools::getPDO();
			$sth = $pdo->prepare("SELECT * FROM ".self::tableName." WHERE `artist_id` = :artist AND `track_id` = :track");
			if ($sth->execute(['artist' => $artist, 'track' => $track])) {
				if (count($sth->fetchAll()) > 0) {
					return true;
				} else {
					return false;
				}
			} else {
				throw new Exception("Системе не удалось найти связь между Артистом {$artist} и Треком {$track}", 1);
				
			}
		}
	}
?>