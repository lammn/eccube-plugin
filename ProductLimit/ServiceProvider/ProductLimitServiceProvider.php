<?php

namespace Plugin\ProductLimit\ServiceProvider;

use Eccube\Application;
use Plugin\ProductLimit\Form\Type\ProductLimitConfigType;
use Silex\Application as BaseApplication;
use Silex\ServiceProviderInterface;

class ProductLimitServiceProvider implements ServiceProviderInterface
{
    public function register(BaseApplication $app)
    {
        // 管理画面
        $app->match('/' . $app['config']['admin_route'] . '/plugin/productlimit/config', 'Plugin\ProductLimit\Controller\ProductLimitController::index')->bind('plugin_ProductLimit_config');

        //$app->match('/plugin/productlimit/checkout', 'Plugin\ProductLimit\Controller\ProductLimitController::index')->bind('plugin_productlimit_index');

        // Repository
        $app['eccube.plugin.repository.productlimit'] = $app->share(function () use ($app) {
            return $app['orm.em']->getRepository('Plugin\ProductLimit\Entity\ProductLimit');
        });

        // Form
        $app['form.types'] = $app->share($app->extend('form.types', function ($types) use ($app) {
            $types[] = new ProductLimitConfigType($app);
            return $types;
        }));

        // メッセージ登録
        $app['translator'] = $app->share($app->extend('translator', function ($translator, \Silex\Application $app) {
            $translator->addLoader('yaml', new \Symfony\Component\Translation\Loader\YamlFileLoader());
            $file = __DIR__ . '/../Resource/locale/message.' . $app['locale'] . '.yml';
            if (file_exists($file)) {
                $translator->addResource('yaml', $file, $app['locale']);
            }
            return $translator;
        }));
    }

    public function boot(BaseApplication $app)
    {
    }
}
