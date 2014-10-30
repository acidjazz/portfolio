<?

namespace lib;

class api {

  public $microtime = false;

  public $errors = [];

  public function __construct() {

    $this->microtime = microtime(true);

    Header('Content-type: application/json');
    define('KDB_JSON', true);

  }

  protected function required($fields) {

    $data = [];

    foreach ($fields as $field) {

      if (!isset($_REQUEST[$field]) || empty($_REQUEST[$field])) {
        $this->error($field, "Missing Field $field");
      } else {
        $data[$field] = $_REQUEST[$field];
      }

    }

    if (count($this->errors) > 0) {
      $this->result(false);
      return false;
    }

    return $data;

  }

  protected function error($type, $error) {

    $this->errors[] = [
      'type' => $type,
      'error' => $error
    ];

    return false;

  }

  protected function result($success, $result=null, $data=[], $errors=[]) {

    // 1st catch and report any internal errors
    if (isset(\klib\kdb::$errors) && count(\klib\kdb::$errors) > 0) {
      $success = false;
      $this->error('internal', ['data' => \klib\kdb::errors()]);
    }

    $status = 200;
    if (!$success) {
      $status = 400;
    }

    http_response_code($status);

    $return = [
      'status' => $status,
      'result' => $result,
      'benchmark' => microtime(true)-$this->microtime,
      'data' => $data,
      'errors' => $this->errors
    ]; 
    
    echo json_encode($return, JSON_PRETTY_PRINT);

    return true;

  }

}
