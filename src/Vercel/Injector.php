<?php

namespace Polidog\VercelApp\Vercel;

use BEAR\Package\Injector\PackageInjector;
use Ray\Di\AbstractModule;
use Ray\Di\InjectorInterface;
use Ray\PsrCacheModule\LocalCacheProvider;

final class Injector
{
    /** @codeCoverageIgnore */
    private function __construct()
    {
    }

    public static function getInstance(string $context): InjectorInterface
    {
        $appName = 'Polidog\VercelApp';
        $appDir = dirname(__DIR__, 2);
        $meta = new Meta($appName, $context, $appDir);

        $cacheNamespace = str_replace('/', '_', $appDir) . $context;
        $cache ??= (new LocalCacheProvider($meta->tmpDir . '/injector', $cacheNamespace))->get();

        return PackageInjector::getInstance($meta, $context, $cache);
    }

    public static function getOverrideInstance(string $context, AbstractModule $overrideModule): InjectorInterface
    {
        $appName = __NAMESPACE__;
        $appDir = dirname(__DIR__);

        return PackageInjector::factory(new Meta($appName, $context, $appDir), $context, $overrideModule);
    }
}
