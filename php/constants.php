<?php

class Role {

	public static $adminId = '1ADA585D-33E2-4245-B44F-991746FD0F62';

	public static $clientId = 'A01CE044-DBEE-42ED-B1C2-5A209F1F494F';

	public static $employeId = 'A200E93A-D408-407B-9D11-B705EED31075';

}

class OrderStatus {

	public static $registerId = '96B68296-7E92-4ED2-9B97-3BE106706636';

	public static $executeId = '3E3AF932-8998-40C2-A11E-2ED141E68584';

	public static $receivedId = '916E7B13-528E-4BAA-9973-85D7CC50652F';

	public static $canceledId = '0F503281-1EEE-4FE8-A551-04E11B66CEED';

}





class ConnectionConfig {


	public static $host = 'localhost'; // адрес сервера 
	public static $database = 'irshabar'; // имя базы данных
	public static $user = 'root'; // имя пользователя
	public static $password = ''; // пароль

	public static function getConnection() {		

		return new mysqli(self::$host, self::$user, self::$password, self::$database);

	}

}

?>