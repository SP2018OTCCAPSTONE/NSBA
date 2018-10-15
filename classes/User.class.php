<?php
 date_default_timezone_set('America/Chicago');
/**
 * User class
 */

class User
{

    public $errors;

/**
   * Magic getter - read data from a property that isn't set yet
   *
   * @param string $firstName  Property firstName
   * @return mixed
   */
  public function __get($firstName)
  {
  }


    /**************************************************************************************************
   * Get a page of user records and the previous and next page (if there are any)
   *
   * @param string $page  Page number
   * @return array        Previous page, next page and user data. Page elements are null if there isn't a previous or next page.
   ****************************************************************************************************/
  public static function paginate($page)
  {
    $data = [];
    $users_per_page = 5;

    // Calculate the total number of pages
    $total_users = static::_getTotalUsers();
    $total_pages = (int) ceil($total_users / $users_per_page);
      

    // Make sure the current page is valid
    $page = (int) $page;

    if ($page < 1) {
      $page = 1;
    } elseif ($page > $total_pages) {
      $page = $total_pages;
    }


    // Calculate the next and previous pages
    $data['previous'] = $page == 1 ? null : $page - 1;
    $data['next'] = $page == $total_pages ? null : $page + 1;


    // Get the page of users
    try {

      $db = Database::getInstance();

      $offset = ($page - 1) * $users_per_page;

      $data['users'] = $db->query("SELECT * FROM user ORDER BY lastName LIMIT $users_per_page OFFSET $offset")->fetchAll();

    } catch(PDOException $exception) {

      error_log($exception->getMessage());

      $data['users'] = [];
    }

    return $data;
  }




    /********************************************************************************************
   * Authenticate a user by email and password
   *
   * @param string $email     Email address
   * @param string $password  Password
   * @return mixed            User object if authenticated correctly, null otherwise
   **********************************************************************************************/
  public static function authenticate($email, $password)
  {
    $user = static::findByEmail($email);

    if ($user !== null) {

        // Check the user has been activated
        if ($user->is_active) {

            // Check the hashed password stored in the user record matches the supplied password
            if (Hash::check($password, $user->password)) {
                return $user;
            }
        }
    }
  }


  /**********************************************************************************
   * Find the user with the specified ID
   *
   * @param string $id  ID
   * @return mixed      User object if found, null otherwise
   ********************************************************************************/
  public static function findByID($userId)
  {
    try {

      $db = Database::getInstance();

      $stmt = $db->prepare('SELECT * FROM user WHERE userId = :userId LIMIT 1');
      $stmt->execute([':userId' => $userId]);
      $user = $stmt->fetchObject('User');

      if ($user !== false) {
        return $user;
      }

    } catch(PDOException $exception) {

      error_log($exception->getMessage());
    }
  }


/*************************************************************************
   * Get the user by ID or display a 404 Not Found page if not found.
   *
   * @param array $data  $_GET data
   * @return mixed       User object if found, null otherwise
   ************************************************************************/
  public static function getByIDor404($data)
  {
    if (isset($data['userId'])) {
      $user = static::findByID($data['userId']);

      if ($user !== null) {
        return $user;
      }
    }

    Util::showNotFound();
  }

  

  /************************************************************************************
   * Find the user with the specified email address
   *
   * @param string $email  email address
   * @return mixed         User object if found, null otherwise
   ******************************************************************************************************************************************/
  public static function findByEmail($email)
  {
    try {

      $db = Database::getInstance();

      $stmt = $db->prepare('SELECT * FROM user WHERE email = :email LIMIT 1');
      $stmt->execute([':email' => $email]);
      $user = $stmt->fetchObject('User');

      if ($user !== false) {
        return $user;
      }

    } catch(PDOException $exception) {

      error_log($exception->getMessage());
    }
  }

  /****************************************************************************************************
   * See if an user record already exists with the specified email address ****WHERE DOES THIS GO??
   *
   * @param string $email  email address
   * @return boolean
   *******************************************************************************************************************************************/
  public function emailExists($email) {
    try {

      $db = Database::getInstance();

      $stmt = $db->prepare('SELECT COUNT(*) FROM user WHERE email = :email LIMIT 1');
      $stmt->execute([':email' => $this->email]);

      $rowCount = $stmt->fetchColumn(); 
      return $rowCount == 1;

    } catch(PDOException $exception) {

      error_log($exception->getMessage());
      return false;
    }
  }

  /*******************************************************************************************************
   * Signup a new user // 
   * ************CREATE A FUNCTION FOR ADMIN TO ADD ADDITIONAL DATA TO OTHER TABLES AND "UNFLAG" USER******* 
   *  
   *
   * @param array $data  POST data
   * @return void
   ****************************************************************************************************/
  public static function signup($data)
  {
    // Create a new user model and set the attributes
    $user = new static();

    $user->firstName = $data['firstName'];
    $user->lastName = $data['lastName'];
    $user->email = $data['email'];
    $user->emailSecondary = $data['emailSecondary'];
    $user->password = $data['password'];

    if ($user->isValid()) {

        // Generate a random token for activation and base64 encode it so it's URL safe
        $token = base64_encode(uniqid(rand(), true));
        $hashed_token = sha1($token);

        try {

        $db = Database::getInstance();

        $stmt = $db->prepare('INSERT INTO user (firstName, lastName, email, emailSecondary, password, activation_token) VALUES (:firstName, :lastName, :email, :emailSecondary, :password, :token)');
        $stmt->bindParam(':firstName', $data['firstName']);
        $stmt->bindParam(':lastName', $data['lastName']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':emailSecondary', $data['emailSecondary']);
        $stmt->bindParam(':password', Hash::make($data['password']));
        $stmt->bindParam(':token', $hashed_token);
        $stmt->execute();

        // Send activation email
        $user->_sendActivationEmail($token);

        } catch(PDOException $exception) {

        // Log the exception message
        error_log($exception->getMessage());
        }
    }
    return $user;
  }


  /*****************************************************************************************
   * Find the user by remember token
   *
   * @param string $token  token
   * @return mixed         User object if found, null otherwise
   **************************************************************************************/
  public static function findByRememberToken($token)
  {
    try {

      $db = Database::getInstance();

      $stmt = $db->prepare('SELECT u.* FROM user u JOIN remembered_logins r ON u.userId = r.userId WHERE token = :token');
      $stmt->execute([':token' => $token]);
      $user = $stmt->fetchObject('User');

      if ($user !== false) {
        return $user;
      }

    } catch(PDOException $exception) {

      error_log($exception->getMessage());
    }
  }

  /*****************************************************************************************************
   * Deleted expired remember me tokens
   *
   * @return integer  Number of tokens deleted
   ****************************************************************************************************/
  public static function deleteExpiredTokens()
  {
    try {

      $db = Database::getInstance();

      $stmt = $db->prepare("DELETE FROM remembered_logins WHERE expires_at < '" . date('Y-m-d H:i:s') . "'");
      $stmt->execute();

      return $stmt->rowCount();

    } catch(PDOException $exception) {

      // Log the detailed exception
      error_log($exception->getMessage());
    }

    return 0;
  }

  /*********************************************************************************************************
   * Find the user for password reset, by the specified token and check the token hasn't expired
   *
   * @param string $token  Reset token
   * @return mixed         User object if found and the token hasn't expired, null otherwise
   *********************************************************************************************************/
  public static function findForPasswordReset($token)
  {
    $hashed_token = sha1($token);

    try {

      $db = Database::getInstance();

      $stmt = $db->prepare('SELECT * FROM user WHERE password_reset_token = :token LIMIT 1');
      $stmt->execute([':token' => $hashed_token]);
      $user = $stmt->fetchObject('User');

      if ($user !== false) {

        // Check the token hasn't expired
        $expiry = DateTime::createFromFormat('Y-m-d H:i:s', $user->password_reset_expires_at);

        if ($expiry !== false) {
          if ($expiry->getTimestamp() > time()) {
            return $user;
          }
        }
      }

    } catch(PDOException $exception) {

      error_log($exception->getMessage());
    }
  }

   /*****************************************************************************************************
    * // WILL NEED ANOTHER FUNCTION LIKE THIS FOR BASIC USER*******************************************
   * Activate the user account, nullifying the activation token and setting the is_active flag
   *
   * @param string $token  Activation token
   * @return void
   ******************************************************************************************************/
  public static function activateAccount($token)
  {
    $hashed_token = sha1($token);

    try {

      $db = Database::getInstance(); 

      $stmt = $db->prepare('UPDATE user SET activation_token = NULL, is_active = TRUE WHERE activation_token = :token');
      $stmt->execute([':token' => $hashed_token]);

    } catch(PDOException $exception) {

      // Log the detailed exception
      error_log($exception->getMessage());
    }
  }


  /***************************************************************************
   * Delete the user.
   *
   * @return void
   ****************************************************************************/
  public function delete()
  {
    try {

      $db = Database::getInstance();

      $stmt = $db->prepare('DELETE FROM user WHERE userId = :userId');// WILL NEED TO CASCADE THIS FOR RELATED TABLES
      $stmt->bindParam(':userId', $this->userId, PDO::PARAM_INT);
      $stmt->execute();

    } catch(PDOException $exception) {

      // Log the detailed exception
      error_log($exception->getMessage());
    }
  }



/**********************************************************************************************************
   * Update the existing users details based on the data. Data is validated and $this->errors is set if
   * if any values are invalid.
   *
   * @param array $data  Updated data ($_POST array)
   * @return boolean     True if the values were updated successfully, false otherwise.
   *********************************************************************************************************/
  public function save($data)
  {
    $this->firstName = $data['firstName'];
    $this->lastName = $data['lastName'];
    $this->email = $data['email'];
    $this->emailSecondary = $data['emailSecondary'];

    // If editing a user, only validate and update the password if a value provided
    if (isset($this->userId) && empty($data['password'])) {
        unset($this->password);
      } else {
        $this->password = $data['password'];
      }

    // // Only validate and update the password if a value provided
    // if (empty($data['password'])) {
    //   unset($this->password);
    // } else {
    //   $this->password = $data['password'];
    // }

    // Convert values of the checkboxes to boolean
    $this->is_active = isset($data['is_active']) && ($data['is_active'] == '1');
    $this->is_admin = isset($data['is_admin']) && ($data['is_admin'] == '1');

    if ($this->isValid()) {

      try {

        $db = Database::getInstance();

        // Prepare the SQL: Update the existing record if editing, or insert new if adding
        if (isset($this->userId)) {

        // Prepare the SQL
            $sql = 'UPDATE user 
            SET 
            firstName = :firstName, 
            lastName = :lastName, 
            email = :email, 
            emailSecondary = :emailSecondary, 
            is_active = :is_active, 
            is_admin = :is_admin';

            // only update password if set
            if (isset($this->password)) { 
            $sql .= ', password = :password';
            }

            $sql .= ' WHERE userId = :userId';

        } else {
            $sql = 'INSERT INTO user (
            firstName, 
            lastname, 
            email, 
            emailSecondary, 
            password, 
            is_active, 
            is_admin
            ) 
            VALUES (
            :firstName, 
            :lastName, 
            :email, 
            :emailSecondary, 
            :password, 
            :is_active, 
            :is_admin
            )';
        }

        // Bind the parameters
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':firstName', $this->firstName);
        $stmt->bindParam(':lastName', $this->lastName);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':emailSecondary', $this->emailSecondary);
        $stmt->bindParam(':is_active', $this->is_active);
        $stmt->bindParam(':is_admin', $this->is_admin);

        if (isset($this->userId)) {

            // only update password if set
            if (isset($this->password)) {  
            $stmt->bindParam(':password', Hash::make($this->password));
            }

            $stmt->bindParam(':userId', $this->userId, PDO::PARAM_INT);//************** LOOK AT THIS MORE  */

    //Notice: Only variables should be passed by reference in C:\xampp\htdocs\Capstone_Seed\classes\User.class.php on line 493
        } else { 
            // $pass = ':password';
            // $phash = Hash::make($this->password);
            // $stmt->bindParam($pass, $phash);
            $stmt->bindParam(':password', Hash::make($this->password));
            // $stmt->bindValue(':password', Hash::make($this->password));
        }
        // $stmt->bindParam(':is_active', $this->is_active);
        // $stmt->bindParam(':is_admin', $this->is_admin);
        // $stmt->bindParam(':userId', $this->userId, PDO::PARAM_INT);
        $stmt->execute();

        // Set the ID if a new record
        if ( ! isset($this->userId)) {
            $this->userId = $db->lastInsertId();
        }

        return true;

      } catch(PDOException $exception) {

        // Set generic error message and log the detailed exception
        $this->errors = ['error' => 'A database error occurred.'];
        error_log($exception->getMessage());
      }
    }

    return false;
  }



  /*************************************************************************************************
   * Remember the login by storing a unique token associated with the user ID
   *
   * @param integer $expiry  Expiry timestamp
   * @return mixed           The token if remembered successfully, false otherwise
   **************************************************************************************************/
  public function rememberLogin($expiry)
  {
    
    // Generate a unique token
    $token = uniqid($this->email, true);

    try {

      $db = Database::getInstance();

      $stmt = $db->prepare('INSERT INTO remembered_logins (token, userId, expires_at) VALUES (:token, :userId, :expires_at)');
      $stmt->bindParam(':token', sha1($token));  // store a hash of the token
      $stmt->bindParam(':userId', $this->userId, PDO::PARAM_INT);
      $stmt->bindParam(':expires_at', date('Y-m-d H:i:s', $expiry));
      $stmt->execute();

      if ($stmt->rowCount() == 1) {
        return $token;
      }

    } catch(PDOException $exception) {

      // Log the detailed exception
      error_log($exception->getMessage());
    }

    return false;
  }

  /**********************************************************************************
   * Forget the login based on the token value
   *
   * @param string $token  Remember token
   * @return void
   *********************************************************************************/
  public function forgetLogin($token)
  {
    if ($token !== null) {

      try {

        $db = Database::getInstance();

        $stmt = $db->prepare('DELETE FROM remembered_logins WHERE token = :token');
        $stmt->bindParam(':token', $token);
        $stmt->execute();

      } catch(PDOException $exception) {

        // Log the detailed exception
        error_log($exception->getMessage());
      }
    }
  }

  /**********************************************************************************************************
   * Start the password reset process by generating a unique token and expiry and saving them in the user model
   *
   * @return boolean  True if the user model was updated successfully, false otherwise
   *********************************************************************************************************/
  public function startPasswordReset()
  {
    // Generate a random token and base64 encode it so it's URL safe
    $token = base64_encode(uniqid(rand(), true));
    $hashed_token = sha1($token);

    // Set the token to expire in one hour
    $expires_at = date('Y-m-d H:i:s', time() + 60 * 60);
   
    try {

      $db = Database::getInstance();

      $stmt = $db->prepare('UPDATE user SET password_reset_token = :token, password_reset_expires_at = :expires_at WHERE userId = :userId');
      $stmt->bindParam(':token', $hashed_token);
      $stmt->bindParam(':expires_at', $expires_at);
      $stmt->bindParam(':userId', $this->userId, PDO::PARAM_INT);
      $stmt->execute();

      if ($stmt->rowCount() == 1) {
        $this->password_reset_token = $token;
        $this->password_reset_expires_at = $expires_at;

        return true;
      }

    } catch(PDOException $exception) {

      // Log the detailed exception
      error_log($exception->getMessage());
    }

    return false;
  }


  /**********************************************************************************************
   * Reset the password
   *
   * @return boolean  true if the password was changed successfully, false otherwise
   **********************************************************************************************/
  public function resetPassword()
  {
    $password_error = $this->_validatePassword();

    if ($password_error === null) {

      try {

        $db = Database::getInstance();

        $stmt = $db->prepare('UPDATE user SET password = :password, password_reset_token = NULL, password_reset_expires_at = NULL WHERE userId = :userId');
        $stmt->bindParam(':password', Hash::make($this->password));
        $stmt->bindParam(':userId', $this->userId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
          return true;
        }

      } catch(PDOException $exception) {

        // Set generic error message and log the detailed exception
        $this->errors = ['error' => 'A database error occurred.'];
        error_log($exception->getMessage());
      }
      
    } else {
      $this->errors['password'] = $password_error;
    }

    return false;
  }

  /***********************************************************************************
   * Validate the properties and set $this->errors if any are invalid
   *
   * @return boolean  true if valid, false otherwise
   *********************************************************************************/
  public function isValid()
  {
    $this->errors = [];

    // 
    // firstName *ADD REGEX
    // 
    if ($this->firstName == '') {
      $this->errors['firstName'] = 'Please enter a valid name';
    }

    // 
    // lastName *ADD REGEX
    // 
    if ($this->firstName == '') {
        $this->errors['lastName'] = 'Please enter a valid name';
      }

    // 
    // email (this will be user's USERNAME)
    //MAKE THIS CONDITIONAL IF ITS A REG USER IN HTML required= '<?php user->is_admin ? 'required' : '';
    // if(is_null($this->email))???
    //if($this->email != ''){
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
        $this->errors['email'] = 'Please enter a valid email address';
        }
    //}
   
    // // COMMENTED FOR TESTING
    // // emailSecondary (this will be optional)
    // //
    // //if($this->email != ''){
    //     if (filter_var($this->emailSecondary, FILTER_VALIDATE_EMAIL) === false) {
    //         $this->errors['emailSecondary'] = 'Please enter a valid email address';
    //     }
    //// }
    if ($this->_emailTaken($this->email)) {
    $this->errors['email'] = 'That email address is already taken';
    }

    // 
    // password
    //
    $password_error = $this->_validatePassword();
    if ($password_error !== null) {
    $this->errors['password'] = $password_error;
    }
    

    // if ($this->emailExists($this->email)) {
    //   $this->errors['email'] = 'That email address is already taken';
    // }


    // // 
    // // password *ADD REGEX
    // // 
    // if (strlen($this->password) < 9) {
    //   $this->errors['password'] = 'Please enter a longer password';
    // }

    return empty($this->errors);
  }



  /*********************************************************************************
   * Get the total number of users
   *
   * @return integer
   ********************************************************************************/
  private static function _getTotalUsers()
  {
    try {

      $db = Database::getInstance();
      $count = (int) $db->query('SELECT COUNT(*) FROM user')->fetchColumn(); 

    } catch(PDOException $exception) {

      error_log($exception->getMessage());
      $count = 0;
    }

    return $count;
  }


/**
   * See if the email address is taken (already exists), ignoring the current user if already saved.
   *
   * @param string $email  Email address
   * @return boolean       True if the email is taken, false otherwise
   **********************************************************************************************************************************************/
  private function _emailTaken($email)
  {
    $isTaken = false;
    $user = $this->findByEmail($email);

    if ($user !== null) {

      if (isset($this->userId)) {  // existing user

        if ($this->userId != $user->userId) {  // different user
          $isTaken = true;
        }

      } else {  // new user
        $isTaken = true;
      }
    }

    return $isTaken;
  }


  /******************************************************************************************
   * Validate the password
   *
   * @return mixed  The first error message if invalid, null otherwise
   ******************************************************************************************/
  private function _validatePassword()
  {
    if (isset($this->password) && (strlen($this->password) < 9)) {
      return 'Please enter a longer password';
    }

    if (isset($this->password_confirmation) && ($this->password != $this->password_confirmation)) {
      return 'Please enter the same password';
    }
  }



//   /**********************************************************************************
//    * Validate the password
//    *
//    * @return mixed  The first error message if invalid, null otherwise
//    ********************************************************************************/
//   private function _validatePassword()
//   {
//     if (strlen($this->password) < 5) {
//       return 'Please enter a longer password';
//     }

//     if (isset($this->password_confirmation) && ($this->password != $this->password_confirmation)) {
//       return 'Please enter the same password';
//     }
//   }



/*************************************************************************************
   * Send activation email to the user based on the token
   *
   * @param string $token  Activation token
   * @return mixed         User object if authenticated correctly, null otherwise
   ************************************************************************************/
  private function _sendActivationEmail($token)
  {
    // Note hardcoded protocol
    $url = 'http://'.$_SERVER['HTTP_HOST'].'/NSBA/activate_account.php?token=' . $token;

    $body = <<<EOT

<p>Please click on the following link to activate your account.</p>

<p><a href="$url">$url</a></p>

EOT;

    Mail::send($this->firstName, $this->email, 'Activate account', $body);
  }

}
