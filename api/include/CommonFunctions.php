<?php
$response = array("error" => FALSE); // json response array

$request_body 	= 	file_get_contents('php://input');

$data 			= 	json_decode($request_body);

$task			=	(!empty($data->task)?$data->task:"list");

@$data->apiKey			=	(!empty($data->apiKey)?$data->apiKey:base64_encode("jimmydued"));

class Common_Functions {
	
    protected $conn;
 
    // constructor
    function __construct() {
		require_once 'DB_Connect.php';
        // connecting to database
        $db = new Db_Connect();
        $this->conn = $db->connect();
    }
 
	function keepValidateUser($input_data){
		
		$input_data->apiKey = base64_decode($input_data->apiKey);
	
		if (isset($input_data->apiKey)){
			// get the user by username
			$user = $this->isUserExistedByUsername($input_data->apiKey);
			
			if (!$user){
				// user is not found with the credentials
				$response["error"] = TRUE;
				$response["error_msg"] = "Login credentials are wrong. Please try again!";
				echo json_encode($response);
				exit;
			}
		} 
		else {
			// required post params is missing
			$response["error"] = TRUE;
			$response["error_msg"] = "You are not authorize to access!";
			echo json_encode($response);
			exit;
		}
	}
	
    /**
     * Get user by email and password
     */
    public function getUserByEmailAndPassword($email, $password) {
 
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
 
        $stmt->bind_param("s", $email);
 
        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            // check for password equality
            if ($user['password'] == md5($password)) {
                // user authentication details are correct
                return $user;
            }
        } else {
            return NULL;
        }
    }
 
    /**
     * Check user is existed or not
     */
    public function isUserExisted($email) {
        $stmt = $this->conn->prepare("SELECT email from users WHERE email = ?");
 
        $stmt->bind_param("s", $email);
 
        $stmt->execute();
 
        $stmt->store_result();
 
        if ($stmt->num_rows > 0) {
            // user existed 
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }
	
	/**
     * Check user is existed or not by username
     */
    public function isUserExistedByUsername($username) {
        
		$stmt = $this->conn->prepare("SELECT * from admins WHERE username = '".$username."' OR email='".$username."'");
			
        $stmt->execute();
 
        $stmt->store_result();
 
        if ($stmt->num_rows > 0) {
            // user existed 
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }
	
	/**
		 * Get Countries Data
	 */
	function getCountriesData() { 
	 
					
			$stmt = $this->conn->prepare("SELECT * from countries WHERE 1=1");
	            
			if ($stmt->execute()) {
				$result = $stmt->get_result()->fetch_assoc();
				$stmt->close();
				return $result;
				
			} else {
				return NULL;
			}
	}

    function fethArray($stmt){

        $data = array();

        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        $stmt->close();
        return $data;
    }
}
 
?>