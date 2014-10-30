<?


global $cfg;

if (isset($_REQUEST['file']) && !empty($_REQUEST['file']) && is_file($cfg['PATH'].'sty/'.$_REQUEST['file'].'.styl')) {

  if (!$css = stylus::c($_REQUEST['file'], true)) {
    Header('Content-type: text/html');
    echo $css;
  } else {
    Header('Content-type: text/css');
    file_put_contents($cfg['PATH'].'css/'.$_REQUEST['file'].'.css', $css);
    echo $css;
  }
} else {
  Header('Content-type: text/html');
  trigger_error('Invalid file');
}

