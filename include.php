<?

date_default_timezone_set('UTC');

spl_autoload_extensions(".class.php");
spl_autoload_register('\spl_autoload', false);
register_shutdown_function(['\klib\kdb', 'shutdown']);
set_error_handler(['\klib\kdb', 'handler'], E_ALL);

\klib\kdb::def(json_decode(file_get_contents('cfg/config.json'), true)['cfg']);
function hpr() { return call_user_func_array(['\klib\kdb', 'hpr'], func_get_args()); }

