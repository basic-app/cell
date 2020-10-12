<?php
/**
 * @copyright Copyright (c) 2018-2020 Basic App Dev Team
 * @link https://basic-app.com
 * @license MIT License
 */
namespace BasicApp\Cell;

abstract class BaseCell
{

    public $viewsNamespace;

    public function __construct(array $properties = [])
    {
        foreach($properties as $key => $value)
        {
            if (property_exists($this, $key))
            {
                $property->$key = $value;
            }
            else
            {
                throw new CellException('Undefined property: ' . $key);
            }
        }

        if ($this->viewNamespace === null)
        {
            $class = get_class($this);

            $segments = explode("\\", $class);

            $class = array_pop($segments);

            if (count($segments) > 0)
            {
                $this->viewsNamespace = implode("\\", $segments);
            }
        }
    }

    public function render(string $view, array $params = [], array $options = []) : string
    {
        if ($this->viewsNamespace)
        {
            $view = $this->viewsNamespace . "\\" . $view;
        }

        clearstatcache();

        if (is_file(APPPATH . "\\Views\\" . $view))
        {
            $view = "App\\Views\\" . $view;
        }

        return view($view, $params, $options);
    }

}