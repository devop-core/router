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
     * @var mixed
     */
    private $callback;

    /**
     * @param string $name
     * @param string $pattern
     * @param mixed $callback
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
        return $this->parameters;
    }

    /**
     * @return mixed
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @param string $pattern
     * @return string
     */
    public function compile($pattern)
    {
        $matches = [];
        preg_match_all('#\{(.*?)\}#s', $pattern, $matches, PREG_SET_ORDER);

        if (empty($matches)) {
            $this->regEx = "{$pattern}";
            return;
        }
        
        $components = array_map(function($values) {
            return ['name' => $values[0], 'value' => $values[1]];
        }, $matches);

        $replaces = $this->build($components);

        $keys = array_keys($replaces);
        $values = array_values($replaces);

        $this->regEx = sprintf("%s", str_replace($keys, $values, $pattern));
    }

    /**
     * @param array $components
     * @return array
     */
    private function build($components)
    {

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

        return $replaces;
    }
}
