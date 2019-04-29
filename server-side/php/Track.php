<?php
	class Track {
		const tableName = 'track';
		private $id;
		private $title;
		private $artist_name;
		private $duration;
		private $href;

		/*** Constructor ***/
			public function __construct($data = null) {
				if (!is_null($data)) {
					$this->title = $data->title;
					$artist = Artist::find(['name', 'LIKE', "%{$data->artist}%"]);
					$this->artist_id = $artist->getId();
					$this->duration = $data->duration;
					$this->href = $data->url;
				}
			}

		/*** Getters ***/
			public function id($id = null) {
				if (is_null($id)) {
					return $this->id;
				} else {
					$this->id = $id;
					return $this;
				}
			}

			public function title($title = null) {
				if (is_null($title)) {
					return $this->title;
				} else {
					$this->title = $title;
					return $this;
				}
			}

			public function artist($artist_name = null) {
				if (is_null($artist_name)) {
					$t = explode('|', $this->artist_name);
					$result = '';
					if ( count($t) > 1) {
						for ( $i = 0; $i < count($t); $i++ ) {
							if ($i == 0) {
								$result .= $t[$i].' feat.';
							} else {
								if ($i == (count($t) - 1)) {
									$result .= $t[$i];
								} else {
									$result .= $t[$i].' & ';
								}
							}
						}
					} else {
						return $t[0];
					}
					return $result;
				} else {
					$t = explode($artist_name);
					for ( $i = 0; $i < count($t); $i++ ) {
						if (is_numeric($t[$i])) {
							if ( $a = Artist::find(['id', '=', $t[$i]]) ) {
								Artist_Track::setLink($a, $this->id());
							}
						} else {
							if ( $a = Artist::find(['name', 'LIKE', "%{$t[$i]}%"]) ) {
								Artist_Track::setLink($a, $this->id());
							}
						}
					} 
				}
			}

			public function duration($dur = null) {
				if (is_null($dur)) {
					$d = $this->duration % 60;
					if ( $d > 9) { 
						$s = $d;
					} else {
						$s = '0'.$d;
					}
					return round($this->duration / 60).':'.$s;
				} else {
					if ( strpos($dur, ':') ) {
						$items = explode(':', $dur);
						if (count($items) == 2) {
							$this->duration = $items[0] * 60 + $items[1];
						}
					} else {
						$this->duration = $dur;
					}
					return $this;
				}
			}

			public function href($href = null) {
				if (is_null($href)) {
					return $this->href;
				} else {
					$this->href = $href;
					return $this;
				}
			}

		/*** Static Methods ***/
			public static function insert($data) {
				$pdo = Tools::getPDO();
				$result = [];
				try {
					$art = Artist::find(['name', 'LIKE', mb_convert_encoding("%{$data['artist']}%", 'utf-8', mb_detect_encoding("%{$data['artist']}%"))]);
					if ( !$art ) {
						$art = Artist::insert([$data['artist'], '', '', '', '']);
					}
					$track = Track::find([['title', 'LIKE', "%{$data['title']}%"]]);
					if ( !$track ) {
						$sth = $pdo->prepare("INSERT INTO ".self::tableName." (`id`, `title`, `duration`, `href`) VALUES (null, :title, :duration, :url)");
						unset($data['artist']);
						if ( $sth->execute($data) ) {
							$track = Track::find(['id', '=', $pdo->lastInsertId()]);
							Artist_Track::setLink($art->id(), $track->id());
							return $track;
						} else {
							throw new Exception("Система не смогла выполнить запрос на вставку в Базу Данных\n".print_r($this)."\n".print_r($sth), 1);
						}
					} else {
						if (!Artist_Track::checkLink( $art->id(), $track->id() )) {
							Artist_Track::setLink( $art->id(), $track->id() );
						}
						return $track;
					}
				} catch (Exception $e) {
					echo $e->getMessage();
				}
			}

			public static function getTrack($id) {
				$pdo = Tools::getPDO(DSN, user, pass);
				$sth = $pdo->prepare("SELECT track.*, GROUP_CONCAT(artist.name SEPARATOR '|') AS artist_name FROM track LEFT JOIN artist_track ON track.id = artist_track.track_id LEFT JOIN artist ON artist.id = artist_track.artist_id WHERE artist_track.track_id = :id GROUP BY track.id");
				if ($sth->execute(['id' => $id])) {
					return $sth->fetchAll(PDO::FETCH_CLASS, 'Track');
				} else {
					throw new Exception("Система не смогла найти Трек", 1);
					
				}
			}

			public static function find($search) {
				$pdo = Tools::getPDO(DSN, user, pass);
				$prepare = Tools::preparePDO('SELECT * FROM '.self::tableName.' WHERE ', $search);
				$sth = $pdo->prepare($prepare['query']);
				if ($sth->execute($prepare['values'])) {
					$sth->setFetchMode(PDO::FETCH_CLASS, 'Track');
					return $sth->fetch();
				} else {
					var_dump($prepare);
					throw new Exception("Система не смогла выполнить запрос на поиск по Базе Данных<br>Query string: {$prepare['query']}<br>", 1);
				}
			}

			public static function findAll() {
				$pdo = Tools::getPDO(DSN, user, pass);
				$pdo = Tools::getPDO(DSN, user, pass);
				$sth = $pdo->prepare("SELECT track.*, GROUP_CONCAT(artist.name SEPARATOR '|') AS artist_name FROM track LEFT JOIN artist_track ON track.id = artist_track.track_id LEFT JOIN artist ON artist.id = artist_track.artist_id GROUP BY track.id");
				if ( $sth->execute() ) {
					return $sth->fetchAll(PDO::FETCH_CLASS, 'Track');
				} else {
					throw new Exception("Система не смогла выполнить запрос на поиск по Базе Данных<br>Query string: {$prepare['query']}<br>", 1);
					
				}
			}
	}
