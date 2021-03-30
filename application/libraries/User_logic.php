<?PHP


/**
 * Messageテーブルから$user_nameのメッセージ未読数所得
 * @param string $user_name
 * @return int $result
*/

class User_logic
{
    /**
	 * [__construct description]
	 *
	 * @param array $config
	 */
	public function connect($data)
	{
           
        $host   = $data['host'];
        $dbname = $data['dbname'];
        $user   = $data['username'];
        $pass   = $data['password'];
        $dsn    = "mysql:host=$host;dbname=$dbname;charset=utf8;";

        try {
            $pdo =  new PDO($dsn,$user,$pass,[
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            error_log($e, 3, APPPATH."logs/test.log");
            exit();
        };
        return $pdo;
	}
    function countMessage($pdo,$user_name){
        try{
            $sql = 'SELECT * FROM message WHERE IsRead = 0 and isdelete = 0 and ToAccount = ? ';
            $arr = [];
            $arr[] = $user_name;
            
            $stmt = $pdo -> prepare($sql);
            $stmt -> execute($arr);
            $result = $stmt -> rowCount();
            if (!$result){
                $result = 0;
            }
            return $result;
    
        }catch(Exception $e){
            return ;
        }
    }
    
    
    
    
    
    /**
     * productテーブルの中から指定されたtypeIDの個数所得
     * @param int $type_id
     * @return int $result
     */
    function gragh_type_count($pdo,$type_id){
        try{
            $sql = 'SELECT * FROM product WHERE isdelete = 0 and TypeID = ? ';
            $arr = [];
            $arr[] = $type_id;
            
            $stmt = $pdo -> prepare($sql);
            $stmt -> execute($arr);
            $result = $stmt -> rowCount();
    
            return $result;
    
        }catch(Exception $e){
            return ;
        }
    }
    
    /**
     * producttype テーブルから登録されているセンサのデータを所得
     */
    function typeGet($pdo){
        try{
            $sql = 'SELECT * FROM ProductType WHERE isdelete = 0';
    
            $stmt = $pdo ->query($sql);
            $result = $stmt->fetchall(PDO::FETCH_ASSOC);
    
        return $result;
            $stmt = null;
        }
        catch(Exception $e){
            return;
        }
    }
    /**
     * 登録されているセンサからカラム名＄columnを検索し配列に
     * @param string $column
     * @return array $result
     */
    function getFromType($column){
        $arr = typeGet();
        $result = array_column($arr,$column);
        return $result;
    }
    
    // 下記処理で登録済みセンサーの個数を表示する円グラフのパラメータが表示できると考えました
    
    // $ids = getFromType('ID');
    // $typenames = getFromType('TypeName');
    // foreach ($ids as $value) {
    //     $count = gragh_type_count($value);
    //     echo $count;
    // }
    // foreach ($typenames as $value) {
    //     echo $value;
    // }
    
    
    
    
    
    
    //ここまでがホーム関連
    
    
    
    
    
    /**
     * UserNameからユーザを所得
     * @param string $user_name
     * @return array|bool  $user|false
     */
    function getUser($pdo,$user_name){
        //SQLの準備
        //SQLの実行
    $sql = 'SELECT * FROM users WHERE  UserName = ?';
     //user_nameを配列に入れる
     $arr = [];
     $arr[] = $user_name;
     try{
         $stmt=$pdo->prepare($sql);
         $stmt->execute($arr);
         //SQLの結果を返す
         $user = $stmt -> fetch();
         return $user;
             
     }catch(Exception $e){
         return false;
     }
    }    
    
    /**
    * ログイン処理
    * @param string $user_name
    * @param string $password
    * @return bool $result
    */
    function login($user_name,$password){
    
        $result = false;
        //ユーザをuser_nameから検索して所得
        $user = getUser($user_name);
    
        //パスワードの照会
        if(password_verify($password,$user['Password'])){
        // if($password==$user['Password']){
            //セッションIDを再生成　セッションハイジャック対策
            //ログイン成功
            session_regenerate_id(true);
            $result = true;
            return $result;
        }
        return $result;
    }
    
    
       /**
         * ログアウト処理
         */
    function logout(){
        $_SESSION = array();
        session_destroy();
    }
         
    /**
     *  XSS対策：エスケープ処理
     * @param string $str 対象の文字列
     * @return string 処理された文字列
     */
    function h($str){
        return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
    }
    
    /**
     * CSRF対策
     * @param toid
     * @return string $csrf_token
     */
    function setToken(){
        //トークンを生成
        // フォームからそのトークンを送信
        // 送信後の画面でそのトークンを照会
        // トークンを削除
        
        $csrf_token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrf_token;
        
        return $csrf_token;
    }
    
}

?>