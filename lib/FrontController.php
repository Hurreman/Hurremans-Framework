<?php
/**
 * FrontController, handles page requests and file loading.
 *
 * Changelog:
 * -------------------------------------------------------------------------------
 * 26 Aug 2011:
 * Added method_param to enable overriding the default method from execute to
 * something else, so that an action can contain multiple methods, which saves
 * creating multiple actions for related methods.
 * -------------------------------------------------------------------------------
*/
class FrontController
{
    private $action_dir;
    private $default_action;
    private $error_action;
    private $action_param;
    private $method_param;
    private $action = '';

    /**
     * Construct
     *
     * @param string $action_dir The directory where actions/controllers are found, defaults to 'actions'
     * @param string $default_action The default action/controller to be executed, defaults to 'home'
     * @param string $error_action The error handler/controller to be used, defaults to 'error'
     * @param string $action_param The querystring param where action/controller names are stored, defaults to 'action'
     * @param string $method_param The querystring param where the method to be called is stored, defaults to 'method'
     */
    public function __construct($action_dir = 'actions', $default_action = 'home', $error_action = 'error', $action_param = 'action', $method_param = 'method')
    {
        $this->action_dir = $action_dir;
        $this->default_action = $default_action;
        $this->error_action = $error_action;
        $this->action_param = $action_param;
        $this->method_param = $method_param;
    }


    /**
     * Command factory
     *
     * @param $action The action/controller file to load.
     * @return object An instance of the called action/controller.
     */
    public function commandFactory($action)
    {        
        $obj = null;
        $filename = $this->action_dir . $action . '.php';
        
        // Make sure that the file exists
        if (file_exists($filename))
        {
            include($filename);
            if (class_exists($action))
            {
                  $obj = new $action();
            }
        }
        // If it doesn't exist, try to execute the error action
        else if (file_exists($this->action_dir . $this->error_action))
        {
            include($filename);
            if (class_exists($this->error_action))
            {
                $obj = new $this->error_action();
            }
        }
        else
        {
            die('An error occured while loading the controller.');
        }
        
        return $obj;
    }

    
    /**
     * Creates an instance of a controller and executes the chosen method.
     * Without any extra parameters, it calls home->execute();
     */
    public function execute()
    {
        // Check if there's a chosen controller
        if (isset($_GET[$this->action_param]))
        {
             $this->action = preg_replace('/[^a-zZ-Z0-9\_\-]/', '', $_GET[$this->action_param]);
        }
        // Otherwise, fetch the default controller (Home)
        else
        {
             $this->action = $this->default_action;
        }
        
        // Attempt to create an instance of the controller.
        // If this fails, try calling the error controller.
        if($obj = $this->commandFactory($this->action))
        {
            // Do we have a custom method to call, and does it exist?
            if(isset($_GET[$this->method_param]) && method_exists($obj, $_GET[$this->method_param]))
            {
                // Call the method!
                call_user_func(array($obj, $_GET[$this->method_param]));
            }
            // Either we have no method, or it doesn't exist. Call the default execute();
            else
            {
                $obj->execute();
            }
        }
        // Could not create a controller, attempt calling the error controller
        elseif($obj = $this->commandFactory($this->error_action))
        {
            $obj->execute();
        }
        // The error controller could not be called, call an error!
        else
        {
            die('An error occured when loading the controller.');
        }

    }
}
?>