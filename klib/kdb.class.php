<?

namespace klib;

class kdb {

  public static $errors = [];
  public static $html = false;
  public static $window = 8;

	private static $_etypes = [
		E_ERROR							=> 'Error',
		E_WARNING						=> 'Warning',
		E_PARSE							=> 'Parsing Error',
		E_NOTICE        	  => 'Notice',
		E_CORE_ERROR				=> 'Core Error',
		E_CORE_WARNING			=> 'Core Warning',
		E_COMPILE_ERROR			=> 'Compile Error',
		E_COMPILE_WARNING		=> 'Compile Warning',
		E_USER_ERROR				=> 'User Error',
		E_USER_WARNING			=> 'User Warning',
		E_USER_NOTICE  		  => 'User Notice',
		E_STRICT        	  => 'Runtime Notice',
		E_RECOVERABLE_ERROR => 'Recoverable Error',
		E_DEPRECATED				=> 'Deprecated',
		E_USER_DEPRECATED		=> 'User Deprecated',
		420									=> 'KDB'
	];

  public static function shutdown() {

    if (is_null($error = error_get_last()) === false) {
      self::handler($error['type'], $error['message'], $error['file'], $error['line']);
    }

    global $cfg;

    if (count(self::$errors) > 0) {
      require_once $cfg['path'].'lib/jade.class.php';
      require_once $cfg['path'].'lib/node.class.php';
      self::$html = \lib\jade::c('kdb', ['errors' => self::$errors], true);

      if (!defined(KDB_JSON)) {
        echo self::$html;
      } else {
        return self::$html;
      }

    }

  }

  public static function handler($eno, $string, $file, $line) {

    // other type of logging here at some point
    $lines = explode('<br />', highlight_file($file, true));

    $code = [];

	  for ($i = (($line-self::$window < 1) ? 1 : $line-self::$window); $i != $line+self::$window; $i++) {

      if (isset($lines[$i])) {
        $code[$i+1] = $lines[$i];
      }

    }

    self::$errors[] = [
      'type' => self::$_etypes[$eno],
      'name' => $string,
      'file' => $file,
      'line' => $line,
      'code' => $code
    ];

  }

  public static function errors() {

    $errors = [];

    foreach (self::$errors as $error) {
      unset($error['code']);
      $errors[] = $error;
    }

    return $errors;

  }

  // define configuration variables from a JSON file
  public static function def($arr, $lead='') {
    $GLOBALS['cfg'] = $arr;
    /*
    foreach ($arr as $key=>$value) {
      if (is_array($value)) {
        self::def($value, ($lead != '' ? $lead.'_' : '').$key);
      } else {
        define(strtoupper($lead.( ($lead != '') ? '_' : '').$key), $value);
      }
    }
     */

  }

  public static function hpr($obj, $return=false) {

    if (defined('KDB_JSON')) {
      echo json_encode($obj, true);
      exit();
      return true;
    }

    $output = '';

    if (PHP_SAPI != 'cli') {

    $output = <<<HTML
    <pre style="
      font-size: 13px;
      font-family: 'lucida grande', tahoma, verdana, arial, sans-serif;
      color: #333;
      border: 1px solid #d0d0d0; 
      background-color: #efefef;
      border-radius: 5px;
      margin: 5px; 
      padding: 5px;
    ">
HTML;

  }

    ob_start();
    var_dump($obj);
    $output .= ob_get_contents();
    ob_end_clean();
    
    if (PHP_SAPI != 'cli') {
      $output .= '</pre>';
    }

    if ($return == true) {
      return $output;
    }

    echo $output;

  }


}
