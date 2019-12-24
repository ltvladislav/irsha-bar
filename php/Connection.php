<?

class Connection {
	public static $host = 'localhost'; // адрес сервера 
	public static $database = 'irshabar'; // имя базы данных
	public static $user = 'root'; // имя пользователя
	public static $password = ''; // пароль

	public static function getConnection() {		
		return new mysqli(self::$host, self::$user, self::$password, self::$database);
	}
}

?>