<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Widget
{
//    function __construct() {
//        $ci = get_instance();
//        $ci->load->library('Redis');
//    }

    function run($name)
    {
        if (!file_exists(APPPATH . 'widgets/' . $name . 'Widget' . EXT))
            return '<span style="color: red;">WIDGET ERROR: Cannot find `' . APPPATH . 'widgets/' . $name . 'Widget' . EXT . '`</span>';

        $args = func_get_args();

        require_once APPPATH . 'widgets/' . $name . 'Widget' . EXT;
        $name = ucfirst($name);

        $widget = new $name();
        return call_user_func_array(array($widget, 'run'), array_slice($args, 1));
    }

    function render($viewName, $data = array())
    {
        if (!file_exists(APPPATH . 'widgets/views/' . $viewName . EXT))
            return '<span style="color: red;">WIDGET ERROR: Cannot find `' . APPPATH . 'widgets/views/' . $viewName . EXT . '`</span>';

        extract($data);
        include APPPATH . 'widgets/views/' . $viewName . EXT;
    }

    function load($object)
    {
        $this->$object =& load_class(ucfirst($object));
    }

    function __get($var)
    {
        static $ci;
        isset($ci) OR $ci =& get_instance();
        return $ci->$var;
    }
}

/* End of file widget_helper.php */
/* Location: ./application/helpers/widget_helper.php */