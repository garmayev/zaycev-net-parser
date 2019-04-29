<?php
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
				$pdo = Tools::getPDO(DSN, user, pass);
				$sth = $pdo->prepare("INSERT INTO ".self::tableName." (`id`, `name`, `bio`, `birth`, `real`, `photo`) VALUES (null, ?, ?, ?, ?, ?)");
				if ( $sth->execute($data) ) {
					return Artist::find(['id', '=', $pdo->lastInsertId()]);
				} else {
					throw new Exception("Система не смогла выполнить запрос на вставку в Базу Данных<br><br>", 1);
				}
			}

			public static function find($search) {
				$pdo = Tools::getPDO(DSN, user, pass);
				$prepare = Tools::preparePDO('SELECT * FROM `'.self::tableName.'` WHERE ', $search);
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
				$pdo = Tools::getPDO(DSN, user, pass);
				$sth = $pdo->prepare("UPDATE `".self::tableName."` SET name = ?, bio = ?, href = ? WHERE id = ?");
				if ( $sth->execute([$this->name, $this->bio, $this->href, $this->id]) ) {
					return $this;
				} else {
					throw new Exception("Система не смогла выполнить запрос на сохранение в Базе Данных\n", 1);
					
				}
			}
	}