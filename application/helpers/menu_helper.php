<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (! function_exists('activate_link')) {
    function activate_link($controller)
    {
        // Getting the class instance.
        $ci = get_instance();

        // Getting the router class to actived it.
        $class = $ci->router->fetch_class();

        return ($class == $controller) ? 'underline bg-red' : '';
    }
}

if (!  function_exists("show_current_class")) {
    function show_current_class($args = "", $class = "active")
    {
        $route_obj = new CI_Router;
        $get_page = $route_obj->method;

        if ($get_page == $args) {
            echo $class;
        }
    }
}
