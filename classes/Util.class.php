<?php
 
/**
 * Utilities class
 */

class Util
{

  /***************************************************************************
   * Redirect to a different page
   *
   * @param string $url  The relative URL
   * @return void
   ****************************************************************************/
  public static function redirect($url) {
    header('Location: http://' . $_SERVER['HTTP_HOST'] . $url);
    exit;
  }


  /*****************************************************************************
   * MAYBE JUST DISABLE <A> TAGS (GREY OUT) 
   * Deny access by sending an HTTP 403 header and outputting a message
   *
   * @return void
  **************************************************************************/
    public static function denyAccess() {
      header('HTTP/1.0 403 Forbidden');
      echo '403 Forbidden';
      exit;
    }


    /***************************************************************************
   * Show not found page and send an HTTP 404 header
   *
   * @return void
   ****************************************************************************/
  public static function showNotFound() {
    header('HTTP/1.0 404 Not Found');
    echo '404 Not Found';
    exit;
  }

  /***************************************************************************
   * Reports queries
   * 
   * 
   ************************************************************************/

  /**
   * 
   */
  public static function getMailingList() {
    
    $data = [];

    try {

      $db = Database::getInstance();

      $data['users'] = $db->query("SELECT first_name, last_name, d.company, d.line_1, d.line_2, d.city, d.state, d.zip FROM user u
      JOIN user_data d
      ON u.user_id = d.user_id
      JOIN annual_membership m
      ON d.user_id = m.user_id
      JOIN invoice i
      ON i.annual_membership_id = m.annual_membership_id
      WHERE i.date_paid IS NOT NULL && d.line_1 != '' && m.annum = YEAR(CURDATE()) && m.annum = YEAR(CURDATE() - 1)
      ORDER BY last_name")->fetchAll();

    } catch(PDOException $exception) {

      error_log($exception->getMessage());

      $data['users'] = [];
    }

    return $data;
    
  }

}
