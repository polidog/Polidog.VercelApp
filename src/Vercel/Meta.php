<?php

namespace Polidog\VercelApp\Vercel;

use BEAR\AppMeta\AbstractAppMeta;
use BEAR\AppMeta\Exception\AppNameException;
use BEAR\AppMeta\Exception\NotWritableException;

final class Meta extends AbstractAppMeta
{
    public function __construct(string $name, string $context = 'app', string $appDir = '')
    {
        $this->name = $name;
        $this->appDir = $appDir ?: $this->getAppDir($name);
        $this->tmpDir = $this->appDir . '/api/tmp/' . $context;
        if (! file_exists($this->tmpDir) && ! @mkdir($this->tmpDir, 0755, true) && ! is_dir($this->tmpDir)) {
            throw new NotWritableException($this->tmpDir);
        }

        $this->logDir = $this->tmpDir . '/log/' . $context;
        if (! file_exists($this->logDir) && ! @mkdir($this->logDir, 0755, true) && ! is_dir($this->logDir)) {
            throw new NotWritableException($this->logDir); // @codeCoverageIgnore
        }
    }

    private function getAppDir(string $name): string
    {
        $module = $name . '\Module\AppModule';
        if (! class_exists($module)) {
            throw new AppNameException($name);
        }

        return dirname((string) (new \ReflectionClass($module))->getFileName(), 3);
    }
}