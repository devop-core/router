<?php
namespace DevOp\Core\Router;

class Route
{

    /**
     * @var string
     */
    private $pattern;

    /**
     * @var string
     */
    private $regEx;

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @var object|callback|array|string
     */
    private $callback;

    /**
     * @param string $name
     * @param string $pattern
     * @param object|callback|array|string $callback
     */
    public function __construct($pattern, $callback)
    {
        $this->pattern = $pattern;
        $this->callback = $callback;
        
        $this->compile($pattern);
    }

    /**
     * @return string
     */
    public function getRegEx()
    {
        return $this->regEx;
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->getParameters();
    }

    /**
     * @return object|callback|array|string
     */
    public function getCallback()
    {
        return $this->callback;
    }

    public function compile($pattern)
    {
        $matches = [];
        preg_match_all('#\[(.*?)\]#s', $pattern, $matches, PREG_SET_ORDER);

        if (empty($matches)) {
            $this->regEx = "#^{$pattern}$#s";
            return;
        }

        $components = array_map(function($values) {
            return ['name' => $values[0], 'value' => $values[1]];
        }, $matches);

        $replaces = [];
        foreach ($components AS $route) {
            $placeholder = ltrim($route['value'], '/');
            $optional = substr($route['value'], 0, 1) === '/';
            if (strchr($placeholder, ':')) {
                list($parameter, $value) = explode(':', $placeholder);
                $regEx = "(?P<$parameter>{$value})";
                $this->parameters[] = $parameter;
            } else {
                $regEx = "(?P<$placeholder>[^/]++)";
                $this->parameters[] = $placeholder;
            }
            $replaces[$route['name']] = !$optional ? $regEx : "(?:/{$regEx})";
        }

        $this->regEx = sprintf("#^%s?$#s", str_replace(array_keys($replaces), array_values($replaces), $pattern));
    }
}
