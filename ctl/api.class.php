<?

namespace ctl;

class api extends \lib\api {

  public function __construct() {
    parent::__construct();
  }

  public function __call($method, $args) {

    if (isset($args[0]) && !empty($args[0])) {
      $method = $method.ucfirst($args[0]);
    }

    switch ($method) {

      // methods that require an admin role
      case 'public' :
        call_user_func_array([$this, '_'.$method], []);
        break;

      // methods that require a logged in user
      case 'method' :

        if ($this->user == false) {
          $this->error('role', 'Requires a session token');
          return $this->result(false);
        }

        call_user_func_array([$this, '_'.$method], []);
        break;

      default :
        $this->error('endpoint', 'Endpoint not found or unavailable: ' . $method);
        $this->result(false);
        break;

    }

  }

  private function _deviceList() {

    $dlib = new \lib\device();

    if (false === ($devices = $dlib->browse($_REQUEST))) {
      $this->error($dlib->errors['type'], $dlib->errors['error']);
      return $this->result(false); 
    }

    $html = \lib\jade::c('_devicelist', ['devices' => $devices, 'options' => $dlib->options], true);

    return $this->result(true, null, ['options' => $dlib->options, 'devices' => $devices, 'html' => $html]); 

  }
}

