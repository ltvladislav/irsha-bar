<?

class MethodResult {
    public $success; // bool
    public $message; // string
    
    function __construct($succ = true, $mess = null) {
        $this->success = $succ;
        $this->message = $mess;
    }
}

class SiteHelper {
    
    const SITE_URL = "irshabar.kl.com.ua";
    const SITE_NAME = "IRSHA BAR";
    
    public static function RegisterUser($userConfig) {
        require_once 'constants.php';
        require_once 'ESQ.php';
        
        $esq = new EntitySchemaQuery("User");
        $esq->AddColumn("Id");
        $esq->Filters->AddItem($esq->CreateFilterWithParameter("Email", ConditionType::Equal, $userConfig["email"]));
        
        $collection = $esq->GetEntityCollection();
        
        if (count($collection) > 0) {
            return new MethodResult(false, "Вже є користувач з таким Email!");
        }
        
        $newUser = EntitySchemaQuery::CreateEntity("User");
        
        $newUser->SetDefaultValues();
        
        $newUser->SetColumnValue("Name", $userConfig["name"]);
        $newUser->SetColumnValue("Birdthday", $userConfig["birthday"]);
        $newUser->SetColumnValue("PhoneNumber", $userConfig["phone"]);
        $newUser->SetColumnValue("Email", $userConfig["email"]);
        $newUser->SetColumnValue("Password", $userConfig["password"]);
        $newUser->SetColumnValue("ActivationStatus", 0);
        $newUser->SetColumnValue("RoleId", Role::$clientId);
        $newUser->SetColumnValue("Discount", 0);
        $newUser->SetColumnValue("Score", 0);
        
        $newUser->Save();
        
        self::SendMessAboutRegister($userConfig["email"], $newUser->GetColumnValue("Id"));
        
        return new MethodResult(true);
    }
    
    private static function SendMessAboutRegister($email, $userId) {
        $subject = "Підтвердження реєстрації";
    	$body = "Для підтвердження реєстрації на сайті " . self::SITE_NAME . " перейдіть за посиланням : " . self::SITE_URL . "/php/activation?code=" . $userId;
        
        $mailheader = "Content-type: text/plain; charset=\"utf-8\"\nFrom: admin@irshabar.kl.com.ua \n";
    	mail($email, $subject, $body, $mailheader);
    }
    
    public static function LoginUser($userConfig) {

        require_once 'ESQ.php';
        
        $esq = new EntitySchemaQuery("User");
        $esq->AddColumn("Id");
        $esq->AddColumn("Password");
        $esq->AddColumn("Name");
        $esq->AddColumn("Email");
        $esq->AddColumn("ActivationStatus");
        $esq->AddColumn("RoleId");
        $esq->Filters->AddItem($esq->CreateFilterWithParameter("Email", ConditionType::Equal, $userConfig["email"]));
        
        $collection = $esq->GetEntityCollection();
        
        if (count($collection) == 0) {
            return new MethodResult(false, "Облікового запису не знайдено");
        }
        $user = $collection[0];
        
        if ($userConfig["password"] != $user->GetColumnValue("Password")) {
            return new MethodResult(false, "Невірний пароль");
        }
        if ($user->GetColumnValue("ActivationStatus") == 0) {
            return new MethodResult(false, "Email " . $user->GetColumnValue("Email") . " не підтверджено. Підтвердіть будь ласка вашу електронну адресу.");
        }
        
        self::SetUserToSession($user);
        
        return new MethodResult(true);
    }
    
    
    private static function SetUserToSession($userEntity) {
        session_start();
        $_SESSION['userEmail'] = $userEntity->GetColumnValue("Email");
        $_SESSION['isLogin'] = true;
        $_SESSION['userId'] = $userEntity->GetColumnValue("Id");
        $_SESSION['userName'] = $userEntity->GetColumnValue("Name");
    	$_SESSOIN['userRole'] = $userEntity->GetColumnValue("RoleId");
    }
    public static function DeleteUserFromSession() {
        session_start();
        $_SESSION['userEmail'] = "";
        $_SESSION['isLogin'] = false;
        $_SESSION['userId'] = "";
        $_SESSION['userName'] = "";
        $_SESSOIN['userRole'] = "";
    }
    
    public static function ForgotPassword($userConfig) {
        require_once 'ESQ.php';
        
        $esq = new EntitySchemaQuery("User");
        $esq->AddColumn("Id");
        $esq->AddColumn("Password");
        $esq->AddColumn("Email");
        $esq->Filters->AddItem($esq->CreateFilterWithParameter("Email", ConditionType::Equal, $userConfig["email"]));
        
        
        $collection = $esq->GetEntityCollection();
        
        if (count($collection) == 0) {
            return new MethodResult(false, "Користувача з таким E-mail не знайдено");
        }
        $user = $collection[0];
        
        $email = $user->GetColumnValue("Email");
        $subject = "Відновлення паролю";
        $body = "Дані для входу користувача на сайт " . self::SITE_NAME . " : Логін - " . $user->GetColumnValue("Email") . "  Пароль - " . $user->GetColumnValue("Password");
		
        $mailheader = "Content-type: text/plain; charset=\"utf-8\"\nFrom: admin@irshabar.kl.com.ua \n";
        mail($email, $subject, $body, $mailheader);
        
        return new MethodResult(true);
        
    }


    public static function GetUserInfoFromSession() {
        session_start();
        $info = [];

        $info['isLogin'] = $_SESSION['isLogin'];

        if ($_SESSION['isLogin']) {
            $info['Email'] = $_SESSION['userEmail'];
            $info['Id'] = $_SESSION['userId'];
            $info['Name'] = $_SESSION['userName'];
            $info['RoleId'] = $_SESSION['userRole'];
        }
        return $info;
    }


    public static function ChangePassword($passwordConfig) {
        require_once 'ESQ.php';

        $userInfo = self::GetUserInfoFromSession();

        $esq = new EntitySchemaQuery("User");
        $esq->AddColumn("Id");
        $esq->AddColumn("Password");
        $user = $esq->GetEntity($userInfo["Id"]);

        if ($passwordConfig["oldPassword"] != $user->GetColumnValue("Password")) {
            return new MethodResult(false, "Старий пароль невірний!");
        }
        $user->SetColumnValue("Password", $passwordConfig["newPassword"]);
        $user->Save();
        return new MethodResult(true);
        
    }
}




?>