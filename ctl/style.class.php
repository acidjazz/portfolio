<?

namespace ctl;

class style {

  public function __call($function, $arguments) {

    $this->index();

  }

  public function index($file='main') {

    global $cfg;

    if (is_file($cfg['path'].'sty/'.$file.'.styl')) {

      if (!$css = \lib\stylus::c($file, true)) {
        Header('Content-type: text/html');
        echo $css;
      } else {
        Header('Content-type: text/css', true);
        Header('X-Content-Type-Options: nosniff');
        echo $css;
      }

    }

  }

}
