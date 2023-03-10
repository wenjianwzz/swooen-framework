<?php
namespace Swooen\Config;

class PHPFileConfigLoader implements ConfigLoader {

    protected $files = [];

    public function __construct(string $name, string $path) {
        $this->addConfigFile($name, $path);
    }

    public function addConfigFile(string $name, string $path): self {
        $this->files[] = [$name, $path];
        return $this;
    }

    public function load(ConfigRepository $configRepository) {
        foreach ($this->files as list($name, $path)) {
            $configRepository->set($name, require $path);
        }
    }
}