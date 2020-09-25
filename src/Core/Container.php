<?php
namespace Swooen\Core;

class Container {

    protected $binds = [];
    /**
     * 已经创建的实例，直接返回
     */
    const OPTION_NONE = 0x0000;

    /**
     * 已经创建的实例，直接返回
     */
    const OPTION_INSTANCE = 0x0001;

    /**
     * 单例
     */
    const OPTION_SINGLETON = 0x0002;

    /**
     * 单例
     */
    const OPTION_ALIAS = 0x0004;

    public function __construct() {
        $this->instance(self::class, $this);
    }

    public function destroy() {
        unset($this->binds);
    }

    /**
     * 注册实例
     */
    public function instance($abstract, $instance) : self {
        return $this->register($abstract, $instance, static::OPTION_INSTANCE);
    }

    /**
     * 注册单例
     */
    public function singleton($abstract, $implement=null) : self {
        return $this->register($abstract, $implement?$implement:$abstract, static::OPTION_SINGLETON);
    }

    /**
     * 绑定实现，可以是类名/创建函数
     */
    public function bind($abstract, $implement) : self {
        return $this->register($abstract, $implement, static::OPTION_NONE);
    }

    /**
     * 别名
     */
    public function alias($abstract, $alias) : self {
        return $this->register($alias, $abstract, static::OPTION_ALIAS);
    }

    /**
     * 注册Providers
     */
    public function provider($provider) : self {
        if (is_string($provider)) {
            $provider = $this->make($provider);
        } else if (is_callable($provider)) {
            $provider = $this->call($provider, [], [$this, 'make']);
        }
        $provider->register($this);
        if (method_exists($provider, 'boot')) {
            // 调用Boot
            $this->call([$provider, 'boot'], []);
        }
        return $this;
    }

    protected function register($abstract, $implement, $option) : self {
        $this->binds[$abstract] = [$implement, $option];
        return $this;
    }

    public function bound($abstract) {
        return isset($this->binds[$abstract]);
    }

    public function unbind($abstract) : self {
        if ($this->bound($abstract)) {
            unset($this->binds[$abstract]);
        }
        return $this;
    }

    /**
     * 创建实例，并注入依赖
     */
    public function make($abstract, array $parameters=[]) {
        if (isset($this->binds[$abstract])) {
            // 注册过
            list($implement, $option) = $this->binds[$abstract];
            if (static::OPTION_INSTANCE === ($option & static::OPTION_INSTANCE)) {
                // 已经创建好
                return $implement;
            } else if (static::OPTION_SINGLETON === ($option & static::OPTION_SINGLETON)) {
                // 创建单例
                return $this->makeSingleton($abstract, $implement, $parameters);
            } else if (static::OPTION_ALIAS === ($option & static::OPTION_ALIAS)) {
                // 创建别名
                return $this->make($implement, $parameters);
            } else if (is_callable($implement)) {
                return $this->call($implement, $parameters);
            } else {
                return static::createInstance($implement, $parameters, [$this, 'make']);
            }
        } else {
            return static::createInstance($abstract, $parameters, [$this, 'make']);
        }
    }

    /**
     * 创建单例
     */
    protected function makeSingleton($abstract, $implement, array $parameters) {
        if (is_callable($implement)) {
            $instance = $this->call($implement, $parameters);
        } else if (is_string($implement)) {
            $instance = static::createInstance($implement, $parameters, [$this, 'make']);
        } else {
            throw new \Exception('test');
        }
        $this->instance($abstract, $instance);
        return $instance;
    }

	/**
	 * 
	 * @param callable $call
	 * @param array $paramters 参数字典
	 * @return mixed
	 */
	public function call(callable $call, array $paramters) {
        $dependencies = self::getMethodDependencies($call, $paramters, [$this, 'make']);
		return $call(...$dependencies);
	}
	
	/**
	 * 创建类的实例，自动注入依赖
	 * @param string $class
	 * @param array $args 参数字典，如果不是字典，会按照从前往后的顺序传递到最终调用函数
	 * @return object
	 */
	public static function createInstance(string $class, array $args, callable $makeFunc) {
        if (!class_exists($class)) {
            throw new \Exception("class[{$class}] not exists");
        }
	    $paramters = self::getClassDependencies($class, $args, $makeFunc);
	    $reflect = new \ReflectionClass($class);
	    return $reflect->newInstanceArgs($paramters);
	}
	
	/**
	 * 获取形参列表
	 * @param callable $callback
	 * @return array
	 */
	public static function getCallParameters(callable $callback) {
	    $reflect = is_array($callback) ? new \ReflectionMethod($callback[0], $callback[1]) : new \ReflectionFunction($callback);
	    return $reflect->getParameters();
	}
	
	/**
	 * 获取构造函数的形参列表
	 * @param string $class
	 * @return array
	 */
	public static function getConstructorParameters($class) {
	    $reflect = (new \ReflectionClass($class))->getConstructor();
	    return $reflect?$reflect->getParameters():[];
	}

	/**
	 * 生成依赖
	 * @param array $dependParams
	 * @param array $parameters 参数字典，如果不是字典，会按照从前往后的顺序组装成字典
     * @param callable $makeFunc 依赖查找函数
	 * @return mixed[]
	 */
	public static function getDependencies(array $dependParams, array $parameters, callable $makeFunc) {
	    $dependencies = [];
	    foreach ($dependParams as $parameter) {
			if (array_key_exists($parameter->name, $parameters)) {
				$dependencies[] = $parameters[$parameter->name];
				unset($parameters[$parameter->name]);
			} elseif ($parameter->getClass()) {
				$dependencies[] = $makeFunc($parameter->getClass()->name);
			} elseif ($parameter->isDefaultValueAvailable()) {
				$dependencies[] = $parameter->getDefaultValue();
			}
		}
		return $dependencies;
	}

	/**
	 * 获取构造函数的依赖
	 * @param string $class
	 * @param array $parameters
	 * @return mixed[]
	 */
	public static function getClassDependencies($class, array $parameters, callable $makeFunc) {
		return self::getDependencies(self::getConstructorParameters($class), $parameters, $makeFunc);
	}

	/**
	 * 获取函数的依赖
	 * @param callable $callback
	 * @param array $parameters
	 * @return mixed[]
	 */
	public static function getMethodDependencies(callable $callback, array $parameters, callable $makeFunc) {
		return self::getDependencies(self::getCallParameters($callback), $parameters, $makeFunc);
    }
}
