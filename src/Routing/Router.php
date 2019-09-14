<?php

namespace LBHurtado\Missive\Routing;

use DB;
use Closure;
use Exception;
use Opis\Pattern\RegexBuilder;
use LBHurtado\Missive\Missive;
use Psr\Container\ContainerInterface;
use LBHurtado\Missive\Classes\SMSAbstract;
use LBHurtado\Missive\Exceptions\MissiveRouterException;

class Router
{
    /** @var \Opis\Pattern\RegexBuilder */
    protected $builder;

    /** @var array */
    protected $routes = [];

    /** @var \LBHurtado\Missive\Missive */
    public $missive;

    /** @var ContainerInterface */
    protected $container;

    /**
     * Router constructor.
     * Capture the Missive instance from the container.
     * Instantiate RegexBuilder that ignores casing.
     *
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
     * @param string|callable $action
     * @return Router
     */
    public function register(string $route, $action): self
    {
        $regex = $this->builder->getRegex($route);
        $callable = $this->getCallable($action);
        $this->routes[$regex] = $callable;

        return $this;
    }

    /**
     * Sets the SMS property of Missive before
     * executing the contents of them sms
     *
     * @param SMSAbstract $sms
     * @return bool
     * @throws Exception
     */
    public function process(SMSAbstract $sms)
    {
        $this->missive->setSMS($sms);

        return $this->execute($this->missive->getSMS()->getMessage());
    }


    /**
     * Return true if handled.
     * Otherwise, send to next route.
     *
     * @param string $path
     * @return bool
     * @throws Exception
     */
    public function execute(string $path)
    {
        $ordered_routes = array_reverse($this->routes, true);
        foreach ($ordered_routes as $regex => $action) {
            if ($this->builder->matches($regex, $path)) {
                $values = $this->builder->getValues($regex, $path);
                $data = $this->do($action, $path, $values);
                if ($data === false) {
                    continue;
                }

                return $data;
            }
        }

        return false;
    }

    /**
     * Make sure to rollback the database if an error occurs.
     *
     * @param $action
     * @param $path
     * @param $values
     * @return bool
     * @throws Exception
     */
    protected function do($action, $path, $values)
    {
        $data = false;

        DB::beginTransaction();
        try {
            $data = $action($path, $values);
        }
        catch (Exception $e) {
            DB::rollBack();

            throw $e;
//            throw new MissiveRouterException('Error in executing action!');
        }
        DB::commit();

        return $data;
    }

    /**
     * Make an action for an invokable controller.
     *
     * @param string $action
     * @return string
     * @throws UnexpectedValueException
     */
    protected function makeInvokableAction($action)
    {
        if (! method_exists($action, '__invoke')) {
            throw new UnexpectedValueException(sprintf(
                'Invalid hears action: [%s]', $action
            ));
        }

        return $action.'@__invoke';
    }

    /**
     * @param $callback
     * @return array|string|Closure
     * @throws UnexpectedValueException
     * @throws NotFoundExceptionInterface
     */
    protected function getCallable($callback)
    {
        if ($callback instanceof Closure) {
            return $callback;
        }

        if (\is_array($callback)) {
            return $callback;
        }

        if (\is_object($callback)) {
            return $callback;
        }

        if (strpos($callback, '@') === false) {
            $callback = $this->makeInvokableAction($callback);
        }

        list($class, $method) = explode('@', $callback);

        $command = $this->container ? $this->container->get($class) : new $class($this);

        return [$command, $method];
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
