<?php
	class Tools {
		public static function preparePDO($query, $search) {
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

		public static function getPDO() {
			return new PDO(DSN, user, pass);
		}
	}
?>