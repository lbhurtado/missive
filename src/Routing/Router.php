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

    /** @var \LBHurtado\Missive\Classes\SMSAbstract */
    protected $sms;

    /**
     * Router constructor.
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
     * @param SMSAbstract $sms
     * @return $this
     */
    protected function setSMS(SMSAbstract $sms)
    {
        $this->sms = $sms;

        return $this;
    }

    /**
     * @return SMSAbstract
     */
    public function getSMS(): SMSAbstract
    {
        return $this->sms;
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
     * @param SMSAbstract $sms
     * @return mixed
     */
    public function process(SMSAbstract $sms)
    {
        return $this->setSMS($sms)->execute(
            $this->getSMS()->getMessage()
        );
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
