<?php
	require 'config.php';
	require 'simple_html_dom.php';

	function preparePDO($query, $search) {
		if (is_array($search[0])) {
			for ($i = 0; $i < count($search) - 1; $i++) {
				$query .= "`{$search[$i][0]}` {$search[$i][1]} :{$search[$i][0]} AND ";
				$data[$search[$i][0]] = $search[$i][2];
			}
			$query .= "`{$search[count($search) - 1][0]}` {$search[count($search) - 1][1]} :{$search[count($search) - 1][0]}";
			$data[$search[count($search) - 1][0]] = $search[count($search) - 1][2];
		} else {
			$query .= "`{$search[0]}` {$search[1]} :{$search[0]} ";
			$data[$search[0]] = $search[2];
		}
		return ['query' => $query, 'values' => $data];
	}

	class Artist {
		const tableName = 'artist';
		private $id;
		private $name;
		private $bio;
		private $href;

		/*** Constructor ***/
			public function __construct($data = null) {
				if (!is_null($data)) {
					$this->name = $data->name;
					$this->bio = $data->bio;
					$this->href = $data->href;
				}
			}

		/*** Getters ***/
			public function name($name = null) {
				if (is_null($name)) {
					return $this->name;
				} else {
					$this->name = $name;
					return $this;
				}
			}

			public function id($id = null) {
				if (is_null($id)) {
					return $this->id;
				} else {
					$this->id = $id;
					return $this;
				}
			}

			public function bio($bio = null) {
				if (is_null($bio)) {
					return $this->bio;
				} else {
					$this->bio = $bio;
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
				$pdo = new PDO(DSN, user, pass);
				$sth = $pdo->prepare("INSERT INTO ".self::tableName." (`id`, `name`, `bio`, `href`) VALUES (null, ?, ?, ?)");
				if ( $sth->execute($data) ) {
					return Artist::find(['id', '=', $pdo->lastInsertId()]);
				} else {
					throw new Exception("Система не смогла выполнить запрос на вставку в Базу Данных<br><br>", 1);
				}
			}

			public static function find($search) {
				$pdo = new PDO(DSN, user, pass);
				$prepare = preparePDO('SELECT * FROM `'.self::tableName.'` WHERE ', $search);
				$sth = $pdo->prepare($prepare['query']);
				if ($sth->execute($prepare['values'])) {
					$sth->setFetchMode(PDO::FETCH_CLASS, 'Artist');
					return $sth->fetch();
				} else {
					throw new Exception("Система не смогла выполнить запрос на поиск по Базе Данных<br/> Query string: {$prepare['query']}", 1);
				}
			}

		/*** Methods ***/
			public function save() {
				$pdo = new PDO(DSN, user, pass);
				$sth = $pdo->prepare("UPDATE `".self::tableName."` SET name = ?, bio = ?, href = ? WHERE id = ?");
				if ( $sth->execute([$this->name, $this->bio, $this->href, $this->id]) ) {
					return $this;
				} else {
					throw new Exception("Система не смогла выполнить запрос на сохранение в Базе Данных\n", 1);
					
				}
			}
	}

	class Track {
		const tableName = 'track';
		private $id;
		private $title;
		private $artist_id;
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
			public function title($title = null) {
				if (is_null($title)) {
					return $this->title;
				} else {
					$this->title = $title;
					return $this;
				}
			}

			public function artist($artist = null) {
				if (is_null($artist)) {
					return Artist::find(['id', '=', $this->artist_id]);
				} else {
					if (((int)$artist == 0) && ($art = Artist::find(['name', 'LIKE', "%{$this->artist_id}%"]) != null)) {
						$this->artist_id = $art->id();
					} else if ( Artist::find(['name', 'LIKE', "%{$this->artist_id}%"]) != null ) {
						$this->artist_id = $id;
					} else {
						throw new Exception("Неизвестный исполнитель", 1);
					}
					return $this;
				}
			}

			public function duration($dur = null) {
				if (is_null($dur)) {
					return round($this->duration / 60).':'.$this->duration % 60;
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
				$pdo = new PDO(DSN, user, pass);
				$art = Artist::find(['name', 'LIKE', "%{$data['artist']}%"]);
				if ( !$art ) {
					$art = Artist::insert([$data['artist'], '', '']);
				}
				$data['artist'] = $art->id();
				$track = Track::find([['title', 'LIKE', "%{$data['title']}%"], ['artist_id', '=', "{$data['artist']}"]]);
				if ( !$track ) {
					$sth = $pdo->prepare("INSERT INTO ".self::tableName." (`id`, `title`, `artist_id`, `duration`, `href`) VALUES (null, :title, :artist, :duration, :url)");
					if ( $sth->execute($data) ) {
						return Track::find(['id', '=', $pdo->lastInsertId()]);
					} else {
						throw new Exception("Система не смогла выполнить запрос на вставку в Базу Данных\n".print_r($this)."\n".print_r($sth), 1);
					}
				} else {
					return $track;
				}
			}

			public static function find($search) {
				$pdo = new PDO(DSN, user, pass);
				$prepare = preparePDO('SELECT * FROM '.self::tableName.' WHERE ', $search);
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
				$pdo = new PDO(DSN, user, pass);
				$sth = $pdo->prepare('SELECT * FROM '.self::tableName);
				if ( $sth->execute() ) {
					return $sth->fetchAll(PDO::FETCH_CLASS, 'Track');
				} else {
					throw new Exception("Система не смогла выполнить запрос на поиск по Базе Данных<br>Query string: {$prepare['query']}<br>", 1);
					
				}
			}
	}
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
			default:
				$tr = Track::findAll();
				foreach ($tr as $item) {
					echo "{$item->artist()->name()} - {$item->title()}   {$item->duration()}<br/>";
				}
			break;
		}
	}

	class myCurl {
		const SERVER = 'http://zaycev.net';
		private $ch;

		public function __construct() {
			$this->ch = curl_init();
			curl_setopt($this->ch, CURLOPT_HEADER, 0);
			curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 0);
		}

		public function getPage($url = '/') {
			curl_setopt($this->ch, CURLOPT_URL, self::SERVER.$url);
			$data = curl_exec($this->ch);
			if (!curl_errno($this->ch)) {
				curl_close($this->ch);
				return $data;
			} else {
				return $data;
				throw new Exception("Страница не загрузилась", 1);
			}
		}
	}
?>