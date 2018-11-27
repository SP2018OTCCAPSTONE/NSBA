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
    $users_per_page = 40;

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

      //("SELECT * FROM user ORDER BY last_name LIMIT $users_per_page OFFSET $offset")->fetchAll();
      $data['users'] = $db->query("SELECT * FROM user u
      JOIN user_data d
      ON u.user_id = d.user_id
      JOIN annual_membership m
      ON d.user_id = m.user_id
      JOIN member_type t
      ON m.member_type_id = t.member_type_id
      JOIN invoice i
      ON i.annual_membership_id = m.annual_membership_id
      ORDER BY last_name
      LIMIT $users_per_page OFFSET $offset")->fetchAll();

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
       //var_dump($user);// troubleshooter
    
      // Check the user has been activated
      if ($user->is_enabled) {
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
  public static function findByID($user_id)
  {
    try {

      $db = Database::getInstance();
    //('SELECT * FROM user WHERE user_id = :userId LIMIT 1');
      $stmt = $db->prepare('SELECT * FROM user u
      JOIN user_data d
      ON u.user_id = d.user_id
      JOIN annual_membership m
      ON d.user_id = m.user_id
      JOIN member_type t
      ON m.member_type_id = t.member_type_id
      JOIN invoice i
      ON i.annual_membership_id = m.annual_membership_id
      WHERE u.user_id = :user_id LIMIT 1;');

      $stmt->execute([':user_id' => $user_id]);
      $user = $stmt->fetchObject('User');

      if ($user !== false) {
        return $user;
      }

    } catch(PDOException $exception) {

      error_log($exception->getMessage());
    }
  }

  // JOIN member_type t
  // ON m.member_type_id = t.member_type_id
  // JOIN invoice i
  // ON i.annual_membership_id = m.annual_membership_id



/*************************************************************************
   * Get the user by ID or display a 404 Not Found page if not found.
   *
   * @param array $data  $_GET data
   * @return mixed       User object if found, null otherwise
   ************************************************************************/
  public static function getByIDor404($data)
  {
    if (isset($data['user_id'])) {
      $user = static::findByID($data['user_id']);

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

      $stmt = $db->prepare('SELECT * FROM user u
      JOIN user_data d
      ON u.user_id = d.user_id
      JOIN annual_membership m
      ON d.user_id = m.user_id
      JOIN member_type t
      ON m.member_type_id = t.member_type_id
      JOIN invoice i
      ON i.annual_membership_id = m.annual_membership_id
      WHERE email_1 = :email1 LIMIT 1;');
      //('SELECT * FROM user
      //WHERE email_1 = :email1 LIMIT 1');// OG
      //$stmt->bindParam()
      $stmt->execute([':email1' => $email1]);
      $user = $stmt->fetchObject('User');
      //var_dump($user);// troubleshooter
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
   * Signup a new user 
   * 
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

      $stmt = $db->prepare('DELETE FROM user WHERE user_id = :user_id');
      $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
      $stmt->execute();

    } catch(PDOException $exception) {

      // Log the detailed exception
      error_log($exception->getMessage());
    }
  }

  /***************************************************************************
   * Retrieve the member type data to calculate renewal rate/amount due.
   *
   * @return $data
   ****************************************************************************/
  public function getMemberTypeData()
  {
    try {

      $db = Database::getInstance();
      //('SELECT membership_price, meal_price FROM member_type WHERE member_type_id = :memberType')->fetchAll();
      $data = $db->query('SELECT * FROM member_type')->fetchAll();
      
    } catch(PDOException $exception) {

      // Log the detailed exception
      error_log($exception->getMessage());
      //$data['prices'] = [];
    }
    return $data;
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
    $this->workPhone = $data['workPhone'];
    $this->cellPhone = $data['cellPhone'];
    $this->fax = $data['fax'];
    $this->website = $data['website'];
    //$this->image = $data['image'];
    $this->memberType = $data['memberType'];
    $this->meals = $data['meals'];
    $this->renewalAmount = $data['renewalAmount'];
    $this->amountPaid = $data['amountPaid'];
    $this->notes = $data['notes'];
    $this->referredBy = $data['referredBy'];
    $this->currentPage = $data['currentPage'];

    $this->first = "Member";
    $this->last = "Associate";

    $is_non = isset($data['memberType']) && $data['memberType'] == '0';
    $is_sub = isset($data['memberType']) && $data['memberType'] == '8';
    
    //If user is a sub-member get the last parent_membership_id inserted in DB
    if ($is_sub) {
      $this->parentMembershipId = User::getParentMembershipId();
    }

    // If EDITING an existing user, only validate and update the password if a value provided
    if (isset($this->user_id) && empty($data['password'])) {
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

        // if (isset($this->user_id)) {
        if ($this->currentPage == 'edit') {
          $user_id = $this->user_id;
          // echo($user_id);
          //TODO: Add logic for editing parent_id
          
            // Prepare the SQL
            $sql = "BEGIN; 
            UPDATE user SET first_name = :firstName, last_name = :lastName,"; if(isset($data['password']) && $data['password'] !== '') { $sql .= 'password = :password,';}; $sql .= " is_enabled = :isEnabled, is_admin = :isAdmin, has_permissions = :hasPermissions, board_member = :boardMember, referred_by = :referredBy, notes = :notes WHERE user_id = :user_id; 
            UPDATE user_data SET email_1 = :email1, email_2 = :email2, line_1 = :line1, line_2 = :line2, city = :city, state = :state, zip = :zip, company = :company, work_phone = :workPhone, cell_phone = :cellPhone, fax = :fax, website = :website, image = DEFAULT WHERE user_id = :user_id; 
            UPDATE annual_membership SET member_type_id = :memberType, is_listed = :isListed  WHERE user_id = :user_id AND annum = YEAR(CURDATE()); 
            SELECT @annual_id := annual_membership_id FROM annual_membership WHERE user_id = :user_id AND annum = YEAR(CURDATE()); 
            UPDATE invoice SET meals = :meals, amount_paid = :amountPaid WHERE annual_membership_id = @annual_id; 
            COMMIT;";

        // If not editing existing user, INSERT new user
        } else {
            $sql = 
            'BEGIN;

            INSERT INTO user (
              first_name, 
              last_name,
              password,
              is_enabled, 
              is_admin,
              has_permissions,
              board_member,
              referred_by,
              notes
            )
            VALUES (';
              $sql .= isset($data['firstName']) && (string) $data['firstName'] !== '' ? ':firstName,' : 'DEFAULT,';
              $sql .= isset($data['lastName']) && (string) $data['lastName'] !== '' ? ':lastName,' : 'DEFAULT,';
              $sql .= isset($data['password']) && (string) $data['password'] !== '' ? ':password,' : 'DEFAULT,';
              $sql .= 
              ':isEnabled, 
              :isAdmin,
              :hasPermissions,
              :boardMember,
              :referredBy,
              :notes
            );

            SELECT LAST_INSERT_ID() INTO @id;
           
            INSERT INTO user_data (
              user_id,
              email_1,
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
              image
            )
            VALUES (
              @id,';
              $sql .= isset($data['email1']) && (string) $data['email1'] !== '' ? ':email1,' : 'DEFAULT,';
              $sql .=  
              ':email2, 
              :line1,
              :line2,
              :city,
              :state,
              :zip,
              :company,
              :workPhone,
              :cellPhone,
              :fax,
              :website,
              DEFAULT';
              //$sql .= isEmptyString($data['image']) ? 'DEFAULT' : ':image';
              $sql .=  
            ');

            INSERT INTO annual_membership (
              user_id,
              parent_membership_id,
              member_type_id,
              is_listed,
              annum
            )
            VALUES (
              @id,';
              $sql .= $is_sub ? ':parentMembershipId,' : 'DEFAULT,';
              $sql .=
              ':memberType,
              :isListed,
              YEAR(CURDATE())
            );

            SELECT LAST_INSERT_ID() INTO @annual_id;
            
            INSERT INTO invoice (
              annual_membership_id,
              meals,
              amount_paid
            )
            VALUES (
              @annual_id,
              :meals,';
              $sql .= isset($data['amountPaid']) && (string) $data['amountPaid'] !== '' ? ':amountPaid' : 'DEFAULT';// Need date_paid logic
              $sql .= 
            ');';
          $sql .=
          'COMMIT;';
          }

        // Bind the parameters
        $stmt = $db->prepare($sql);
        if(isset($data['firstName']) &&  (string) $data['firstName'] !== '') {
          $stmt->bindParam(':firstName', $this->firstName);
        }
        if(isset($data['lastName']) &&  (string) $data['lastName'] !== '') {
          $stmt->bindParam(':lastName', $this->lastName);
        }
        // $stmt->bindParam(':firstName', $this->firstName);
        // $stmt->bindParam(':lastName', $this->lastName);

        if(isset($data['email1']) &&  (string) $data['email1'] !== '') {
          $stmt->bindParam(':email1', $this->email1);
        }

        //$stmt->bindParam(':email1', $this->email1);
        $stmt->bindParam(':email2', $this->email2);
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

        // if(! isEmptyString($data['image'])) {
        //   $stmt->bindParam(':image', $this->image);
        // }

        if($is_sub) {
          $stmt->bindParam(':parentMembershipId', $this->parentMembershipId);
        }

        $stmt->bindParam(':memberType', $this->memberType);
        $stmt->bindParam(':meals', $this->meals);

        if(isset($data['amountPaid']) &&  (string) $data['amountPaid'] !== '') {
          $stmt->bindParam(':amountPaid', $this->amountPaid);
        }

        $stmt->bindParam(':notes', $this->notes);
        $stmt->bindParam(':referredBy', $this->referredBy);
        $stmt->bindParam(':isEnabled', $this->isEnabled);
        $stmt->bindParam(':isAdmin', $this->isAdmin);
        $stmt->bindParam(':hasPermissions', $this->hasPermissions);
        $stmt->bindParam(':boardMember', $this->boardMember);
        $stmt->bindParam(':isListed', $this->isListed);

        // If EDITING an existing user
        if (isset($this->user_id)) {
            // only update password if set
            if (isset($this->password)) {  
            $stmt->bindValue(':password', Hash::make($this->password));//***************************************** */
            }
            //
            $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);//************** LOOK AT THIS MORE  */

        // If CREATING new user, and there is data in the field, INSERT password, else password will DEFAULT NULL
        } else { 
            if(isset($data['password']) &&  (string) $data['password'] !== '') {
              //$stmt->bindParam(':password', Hash::make($this->password));
              //Notice: Only variables should be passed by reference 
              $stmt->bindValue(':password', Hash::make($this->password));
            }
        }
      
        $stmt->execute();

        $file = '../../debug_log/interpolated_queries.txt';
        $qry = serialize($stmt->fullQuery);
        file_put_contents($file, $qry);
        //$qry = json_decode(file_get_contents($file), TRUE);

        return true;

      } catch(PDOException $exception) {

        // Set generic error message and log the detailed exception
        $this->errors = ['error' => 'A database error occurred.'];
        error_log($exception->getMessage());
      }
    }

    return false;
  }

  /***************************************************************************************************** 
  * Retrieves the last parent_membership_id inserted from DB
  *
  *
  ******************************************************************************************************/
  public function getParentMembershipId() {
    try {

      $db = Database::getInstance();
      //$parent = (int) $db->query('SELECT COUNT(*) FROM user')->fetchColumn();
      $parent = (int) $db->query(
        'SELECT annual_membership_id
        FROM annual_membership
        ORDER BY annual_membership_id DESC
        LIMIT 1;'
      )->fetchColumn(); 
      
    } catch(PDOException $exception) {

      error_log($exception->getMessage());
      $parent = 0;//NULL?
    }

    return $parent;
  }

  /***************************************************************************************************** 
  * Retrieves the last user_id inserted from DB
  *
  *
  ******************************************************************************************************/
  public function getParentId() {
    try {

      $db = Database::getInstance();
      //$parent = (int) $db->query('SELECT COUNT(*) FROM user')->fetchColumn();
      $parent = (int) $db->query(
        'SELECT user_id
        FROM user
        ORDER BY user_id DESC
        LIMIT 1;'
      )->fetchColumn(); 
      
    } catch(PDOException $exception) {

      error_log($exception->getMessage());
      $parent = 0;//NULL?
    }

    return $parent;
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
      $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
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
      $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
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
        $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
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
    //var_dump($this->currentPage);
    

    if($this->currentPage == 'edit') {

    }

    elseif($this->currentPage == 'new_first') {

      // if ($this->firstName == '') {
      //   $this->errors['firstName'] = 'Please enter a valid first name';
      // }

      // if ($this->lastName == '') {
      //   $this->errors['lastName'] = 'Please enter a valid last name';
      // }

      if (isset($this->email1) && (string) $this->email1 !== '') {
        if (filter_var($this->email1, FILTER_VALIDATE_EMAIL) === false) {
          $this->errors['email1'] = 'Please enter a valid email address';
        }
      }

      if (isset($this->email2) && (string) $this->email2 !== '') {
        if (filter_var($this->email2, FILTER_VALIDATE_EMAIL) === false) {
          $this->errors['email2'] = 'Please enter a valid email address';
        }
      }

      // TODO: Validation for phone #s and amount paid   

      if (isset($this->password) && (string) $this->password !== '') {

        $password_error = $this->_validatePassword();

        if ($password_error !== null) {
        $this->errors['password'] = $password_error;
        }
      }

    }

    elseif($this->currentPage == 'new_second') {

    }

    elseif($this->currentPage == 'new_third') {

    }

    else {

    }
    
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

        if ($this->user_id != $user->user_id) {  // different user
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
