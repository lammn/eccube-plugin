<?php
namespace Plugin\ProductLimit;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Eccube\Event\EventArgs;
class ProductLimitEvent
{

    /** @var  \Eccube\Application $app */
    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function onProductDetailComplete(FilterResponseEvent $event)
    {
        try{
            $request = $event->getRequest();
            $response = $event->getResponse();

            $html = $this->getHtmlProductLimit($request, $response);
            $response->setContent($html);
            $event->setResponse($response);
        }catch(\Exception $e){
            throw  $e;
        }

    }

    /**
     *
     * @param Request $request
     * @param Response $response
     * @return string
     */
    private function getHtmlProductLimit(Request $request, Response $response)
    {

        $ProductLimit = $this->app['eccube.plugin.repository.productlimit']->find(1);
        $dbQuantity = $ProductLimit->getNum();
        $formQuantity = $_REQUEST['quantity'];
        $crawler = new Crawler($response->getContent());

        $html = $this->getHtml($crawler);

       if($formQuantity > $dbQuantity){
           $part = '残り在庫数 ; ' . $dbQuantity . '残りあとわずか';
       }


        try {
//            $oldHtml = $crawler->filter('#confirm_main')->last()->html();
//            $newHtml = $oldHtml . $part; // 変更箇所
//            $html = str_replace($oldHtml, $newHtml, $html);

        } catch (\InvalidArgumentException $e) {
        }

        return html_entity_decode($html, ENT_NOQUOTES, 'UTF-8');
    }

    /**
     * 解析用HTMLを取得
     *
     * @param Crawler $crawler
     * @return string
     */
    private function getHtml(Crawler $crawler)
    {
        $html = '';
        foreach ($crawler as $domElement) {
            $domElement->ownerDocument->formatOutput = true;
            $html .= $domElement->ownerDocument->saveHTML();
        }
        return html_entity_decode($html, ENT_NOQUOTES, 'UTF-8');
    }



}
