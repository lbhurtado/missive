<?php

namespace LBHurtado\Missive\Routing;

use Opis\Pattern\RegexBuilder;
use LBHurtado\Missive\Missive;
use LBHurtado\Missive\Classes\SMSAbstract;

class Router
{
    /** @var \Opis\Pattern\RegexBuilder */
    protected $builder;

    /** @var array */
    protected $routes = [];

    /** @var \LBHurtado\Missive\Missive */
    public $missive;

    /**
     * Router constructor.
     * Capture the Missive instance from the container.
     * Instantiate RegexBuilder that ignores casing.
     * @param Missive $missive
     */
    public function __construct(Missive $missive)
    {
        $this->missive = $missive;

        $this->builder = new RegexBuilder([
            RegexBuilder::REGEX_MODIFIER => 'i'
        ]);
    }

    /**
     * @param string $route
     * @param callable $action
     * @return Router
     */
    public function register(string $route, callable $action): self
    {
        $regex = $this->builder->getRegex($route);
        $this->routes[$regex] = $action;

        return $this;
    }

    /**
     * Sets the SMS property of Missive before
     * executing the contents of them sms
     * @param SMSAbstract $sms
     * @return mixed
     */
    public function process(SMSAbstract $sms)
    {
        $this->missive->setSMS($sms);

        return $this->execute($this->missive->getSMS()->getMessage());
    }

    /**
     * @param string $path
     * @return mixed
     */
    public function execute(string $path)
    {
        $ordered_routes = array_reverse($this->routes, true);
        foreach ($ordered_routes as $regex => $action) {
            if ($this->builder->matches($regex, $path)) {
                $values = $this->builder->getValues($regex, $path);
                $data = $action($path, $values);
                if ($data === false) {
                    continue;
                }

                return $data;
            }
        }

        return false;
    }
}
