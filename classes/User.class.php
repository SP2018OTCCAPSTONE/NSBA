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
    $users_per_page = 50;

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
  public static function authenticate($email1, $password)
  {
    $user = static::findByEmail($email1);

    if ($user !== null) {

        // Check the user has been activated
        if ($user->isActive) {

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

      $stmt = $db->prepare('SELECT * FROM user WHERE user_id = :userId LIMIT 1');
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
  public static function findByEmail($email1)
  {
    try {

      $db = Database::getInstance();

      $stmt = $db->prepare('SELECT * FROM user WHERE email_1 = :email1 LIMIT 1');
      $stmt->execute([':email1' => $email1]);
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
  public function emailExists($email1) {
    try {

      $db = Database::getInstance();

      $stmt = $db->prepare('SELECT COUNT(*) FROM user WHERE email_1 = :email1 LIMIT 1');
      $stmt->execute([':email1' => $this->email1]);

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

        $stmt = $db->prepare('INSERT INTO user (first_name, last_name, email_1, email_2, password, activation_token) VALUES (:firstName, :lastName, :email1, :email2, :password, :token)');
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

        signupAffiliate($data, 1);

        signupAffiliate($data, 2);
        }
    }
    return $user;
  }



  /*******************************************************************************************************
   * Signup a new user // 
   * ************CREATE A FUNCTION FOR ADMIN TO ADD ADDITIONAL DATA TO OTHER TABLES AND "UNFLAG" USER******* 
   *  
   *
   * @param array $data  POST data
   * @return void
   ****************************************************************************************************/
  public static function signupAffiliate($data, $index)
  {
    // Create a new user model and set the attributes
    $user = new static();

    $user->firstName = $data['firstName'];
    $user->lastName = $data['lastName'];
    $user->email = $data['email1'];
    $user->emailSecondary = $data['email2'];
    $user->password = $data['password'];

    if ($user->isValid()) {

        // Generate a random token for activation and base64 encode it so it's URL safe
        $token = base64_encode(uniqid(rand(), true));
        $hashed_token = sha1($token);

        try {

        $db = Database::getInstance();

        $stmt = $db->prepare('INSERT INTO user (firstName, lastName, email_1, email_2, password, activation_token) VALUES (:firstName, :lastName, :email, :emailSecondary, :password, :token)');
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

        signupSubmember();
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

      $stmt = $db->prepare('DELETE FROM user WHERE user_id = :userId');// WILL NEED TO CASCADE THIS FOR RELATED TABLES
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
    $this->email1 = $data['email1'];
    $this->email2 = $data['email2'];
    $this->line1 = $data['line1'];
    $this->line2 = $data['line2'];
    $this->city = $data['city'];
    $this->state = $data['state'];
    $this->zip = $data['zip'];
    $this->company = $data['company'];
    $this->workphone = $data['workphone'];
    $this->cellphone = $data['cellphone'];
    $this->fax = $data['fax'];
    $this->website = $data['website'];
    $this->image = $data['image'];
    $this->memberType = $data['memberType'];
    $this->meals = $data['meals'];
    $this->notes = $data['notes'];
    $this->referredBy = $data['referredBy'];

    // EXPERIMENTS
    //$email1_filled = ! isEmptyString($data['email1']);// true if email1 is not an empty string
    //$password_filled = ! isEmptyString($data['password']);
    //$image_filled = ! isEmptyString($data['image']);
    $is_non = isset($data['memberType']) && $data['memberType'] == '0';
    $is_sub = isset($data['memberType']) && $data['memberType'] == '8';
    
    

    // If EDITING an existing user, only validate and update the password if a value provided
    if (isset($this->userId) && empty($data['password'])) {
        unset($this->password);
      } else {
        $this->password = $data['password'];
      }

    // Convert values of the checkboxes to boolean
    $this->isEnabled = isset($data['isEnabled']) && ($data['isEnabled'] == '1');
    $this->isAdmin = isset($data['isAdmin']) && ($data['isAdmin'] == '1');
    $this->hasPermissions = isset($data['hasPermissions']) && ($data['hasPermissions'] == '1');
    $this->boardMember = isset($data['boardMember']) && ($data['boardMember'] == '1');
    $this->isListed = isset($data['isListed']) && ($data['isListed'] == '1');

    if ($this->isValid()) {// TODO: PASS ARGS FOR SUBMEMBER STATUS, ETC FOR EXCEPTIONS TO VALIDATION RULES 

      try {

        $db = Database::getInstance();

        // Prepare the SQL: Update the existing record if editing, or insert new if adding
        if (isset($this->userId)) {

            // Prepare the SQL
            $sql = 'UPDATE user 
            SET 
            first_name = :firstName, 
            last_name = :lastName,
            email_1 ='; isEmptyString($data['email1']) ? $sql .= ' DEFAULT, ' : $sql .= ' :email1, ';
            $sql .= '
            email_2 = :email2,
            line_1 = :line1,
            line_2 = :line2,
            city = :city,
            state = :state,
            zip = :zip,
            company = :company,
            work_phone = :workphone,
            cell_phone = :cellphone,
            fax = :fax,
            website = :website,
            image ='; isEmptyString($data['image']) ? $sql .= ' DEFAULT, ' : $sql .= ':image, ';
            $sql .= '
            member_type = :memberType,
            meals = :meals,
            notes = :notes,
            referred_by = :referredBy,
            is_enabled = :isEnabled, 
            is_admin = :isAdmin,
            has_permissions = :hasPermissions
            board_member = :boardMember,
            is_listed = :isListed';

            // only update password if set
            if (isset($this->password)) { 
            $sql .= ', password = :password';
            }
            $sql .= ' WHERE userId = :userId';



        // If not editing existing user, INSERT new user
        } else {
            $sql = 'INSERT INTO user (
            first_name, 
            last_name,
            email_1
            email_2,
            line_1,
            line_2,
            city,
            state,
            zip,
            company,
            work_phone,
            cell_phone,
            fax,
            website,
            image,
            member_type,
            meals,
            notes,
            referred_by,
            is_enabled, 
            is_admin,
            has_permissions,
            board_member,
            is_listed,
            password
            )
            VALUES (
            :firstName, 
            :lastName,';
            isEmptyString($data['email1']) ? $sql .= ' DEFAULT, ' : $sql .= ' :email1, ';
            $sql .= ' 
            :email2, 
            :line1,
            :line2,
            :city,
            :state,
            :zip,
            :company,
            :workphone,
            :cellphone,
            :fax,
            :website,';
            isEmptyString($data['image']) ? $sql .= ' DEFAULT, ' : $sql .= ' :image, ';
            $sql .= '
            :memberType,
            :meals,
            :notes,
            :referredBy,
            :isEnabled, 
            :isAdmin,
            :hasPermissions,
            :boardMember,
            :isListed';
            isEmptyString($data['password']) ? $sql .= ' DEFAULT, ' : $sql .= ' :password, ';
            $sql .= '
            )';
          }

        // Bind the parameters
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':firstName', $this->firstName);
        $stmt->bindParam(':lastName', $this->lastName);
        // if(isset($data['email1']) && (string) $data['email1'] !== '') {
        //   $stmt->bindParam(':email1', $this->email1);
        // }
        $stmt->bindParam(':email1', $this->email1);
        $stmt->bindParam(':email2', $this->email2);//IF HERE
        $stmt->bindParam(':line1', $this->line1);
        $stmt->bindParam(':line2', $this->line2);
        $stmt->bindParam(':city', $this->city);
        $stmt->bindParam(':state', $this->state);
        $stmt->bindParam(':zip', $this->zip);
        $stmt->bindParam(':company', $this->company);
        $stmt->bindParam(':workPhone', $this->workPhone);
        $stmt->bindParam(':cellPhone', $this->cellPhone);
        $stmt->bindParam(':fax', $this->fax);
        $stmt->bindParam(':website', $this->website);
        $stmt->bindParam(':image', $this->image);//IF HERE
        $stmt->bindParam(':memberType', $this->memberType);
        $stmt->bindParam(':meals', $this->meals);
        $stmt->bindParam(':notes', $this->notes);
        $stmt->bindParam(':referredBy', $this->referredBy);
        $stmt->bindParam(':isEnabled', $this->isEnabled);
        $stmt->bindParam(':isAdmin', $this->isAdmin);
        $stmt->bindParam(':hasPermissions', $this->hasPermissions);
        $stmt->bindParam(':boardMember', $this->boardMember);
        $stmt->bindParam(':isListed', $this->isListed);
        //If EDITING an existing user
        if (isset($this->userId)) {
            // only update password if set
            if (isset($this->password)) {  
            $stmt->bindParam(':password', Hash::make($this->password));
            }
            //
            $stmt->bindParam(':userId', $this->userId, PDO::PARAM_INT);//************** LOOK AT THIS MORE  */

        // If not editing existing user, and user has entered data into the field, INSERT password, else password will DEFAULT NULL
        } else { 
            if( ! isEmptyString($data['password'])) {
              $stmt->bindParam(':password', Hash::make($this->password));
              //Notice: Only variables should be passed by reference 
            // $stmt->bindValue(':password', Hash::make($this->password));
            }
        }
      
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

 
  /******************************************************************************************************
   * Determines if the supplied string is an empty string. 
   * 
   * 
   * 
   ******************************************************************************************************/                                                                                                          
  public function isEmptyString($str) {
    return !(isset($str) && (string) $str !== ''); //(strlen(trim($str)) > 0)
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

      $stmt = $db->prepare('INSERT INTO remembered_logins (token, user_id, expires_at) VALUES (:token, :userId, :expires_at)');
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
  public function isValid()// TODO: ADD PARAMS FOR VARIABLES TO MAKE VALIDATION EXCEPTIONS FOR SUBMEMBERS ETC
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
    if ($this->lastName == '') {
        $this->errors['lastName'] = 'Please enter a valid name';
      }

    // email (this will be user's USERNAME)
    
        if (filter_var($this->email1, FILTER_VALIDATE_EMAIL) === false) {
        $this->errors['email1'] = 'Please enter a valid email address';
        }
   
    // // COMMENTED FOR TESTING******************************************************************************************************************
    // // emailSecondary (this will be optional)
    // //
    // //if($this->email != ''){
    //     if (filter_var($this->emailSecondary, FILTER_VALIDATE_EMAIL) === false) {
    //         $this->errors['emailSecondary'] = 'Please enter a valid email address';
    //     }
    //// }
    if ($this->_emailTaken($this->email1)) {
    $this->errors['email1'] = 'That email address is already taken';
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
    // // password *ADD REGEX // 
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
