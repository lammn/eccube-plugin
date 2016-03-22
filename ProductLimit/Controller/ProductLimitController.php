<?php
namespace Plugin\ProductLimit\Controller;

use Eccube\Application;
use Plugin\ProductLimit\Entity\ProductLimit;
use Symfony\Component\HttpFoundation\Request;

class ProductLimitController
{

    /**
     * ラッピング用設定画面
     *
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Application $app, Request $request)
    {

        $ProductLimit = $app['eccube.plugin.repository.productlimit']->find(1);

        if (!$ProductLimit) {
            $ProductLimit = new ProductLimit();
        }

        $form = $app['form.factory']->createBuilder('productlimit_config', $ProductLimit)->getForm();

        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $ProductLimit = $form->getData();
                // IDは1固定
                $ProductLimit->setId(1);
                $app['orm.em']->persist($ProductLimit);
                $app['orm.em']->flush($ProductLimit);
                $app->addSuccess('admin.productlimit.save.complete', 'admin');
            }
        }

        return $app->render('ProductLimit/Resource/template/admin/config.twig', array(
            'form' => $form->createView(),
            'ProductLimit' => $ProductLimit,
        ));
    }

}
