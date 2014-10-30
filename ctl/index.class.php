<?

namespace ctl;

class index {

  public function __construct() {
    return $this->index();
  }

  public function index() {
    \lib\jade::c('index');
  }

}

