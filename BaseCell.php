<?php
/**
 * @copyright Copyright (c) 2018-2020 Basic App Dev Team
 * @link https://basic-app.com
 * @license MIT License
 */
namespace BasicApp\Cell;

abstract class BaseCell implements CellInterface
{

    public $viewsNamespace;

    public function __construct(array $properties = [])
    {
        $this->setProperties($properties);

        if ($this->viewsNamespace === null)
        {
            $this->viewsNamespace = $this->getViewsNamespace();
        }
    }

    public function getViewsNamespace()
    {
        $class = get_class($this);

        $segments = explode("\\", $class);

        $class = array_pop($segments);

        if (count($segments) > 0)
        {
            return implode("\\", $segments);
        }

        return null;
    }

    public function setProperties(array $properties = [])
    {
        foreach($properties as $key => $value)
        {
            if (property_exists($this, $key))
            {
                $this->$key = $value;
            }
            else
            {
                throw new CellException('Undefined property: ' . $key);
            }
        }
    }

    public function view(string $view, array $params = []) : string
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

        $params['owner'] = $this;

        return view($view, $params, ['saveData' => false]);
    }

    public abstract function render(array $params) : string;

}