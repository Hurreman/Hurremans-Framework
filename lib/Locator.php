<?php
class Locator
{
    private $objects = array();
    private $args = array();

    // Empty construct
    public function __construct()
    {
    }

    // Add module to array
    public function set($handle, $class)
    {
        $this->objects[$handle] = $class;
    }

    // Set arguments
    public function setArgs($handle)
    {
        $args = array();
        if(func_num_args()>1)
        {
            for($i=1;$i<func_num_args();$i++)
            {
                $args[$i-1] = func_get_arg($i);
            }
        }
        $this->args[$handle] = $args;
    }

    // Fetch arguments
    public function getArgs($handle)
    {
        return $this->args[$handle];
    }

    // Fetch module from array
    public function get($handle)
    {
        if(!isset($this->objects[$handle]))
        {
            if(!class_exists($handle))
            {
                @include_once($handle . '.php');
            }
            if(class_exists($handle))
            {
                // If the module has any arguments, pass them as well
                if(isset($this->args[$handle]))
                {
                    if(count($this->args[$handle])==1)
                    {
                        $arg = $this->args[$handle][0];
                        $this->objects[$handle] = new $handle($arg);
                    }
                    else
                    {
                        $this->objects[$handle] = new $handle($this->args[$handle]);
                    }
                }
                else
                {
                    $this->objects[$handle] = new $handle();
                }
            }
        }
        return $this->objects[$handle];
    }
}
?>
