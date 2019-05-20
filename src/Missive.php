<?php

namespace LBHurtado\Missive;

use Opis\Pattern\RegexBuilder;
use LBHurtado\Missive\Classes\SMSAbstract;
use LBHurtado\Missive\Repositories\SMSRepository;

class Missive
{
    /** @var \Opis\Pattern\RegexBuilder */
    protected $builder;

    /** @var array */
    protected $routes = [];

    /** @var \LBHurtado\Missive\Repositories\SMSRepository */
    protected $smss;

    /** @var  \LBHurtado\Missive\Classes\SMSAbstract */
    protected $sms;

    public function __construct(SMSRepository $smss)
    {
        $this->smss = $smss;
        $this->builder = new RegexBuilder([
            RegexBuilder::REGEX_MODIFIER => 'i'
        ]);
    }

    public function createSMS($attributes = [])
    {
        return $this->sms = $this->smss->create($attributes);
    }

    public function getSMS(): SMSAbstract
    {
        return $this->sms;
    }

    public function register(string $route, callable $action): self
    {
        $regex = $this->builder->getRegex($route);
        $this->routes[$regex] = $action;

        return $this;
    }

    public function execute(string $path = null)
    {
        $path = $path ?? $this->getSMS()->getMessage();
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
