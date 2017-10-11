<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-search-autocomplete
 * @version   1.1.17
 * @copyright Copyright (C) 2017 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\SearchAutocomplete\Plugin;

use Magento\Framework\App\FrontControllerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\Http as ResponseHttp;
use Mirasvit\SearchAutocomplete\Model\Config;
use Mirasvit\SearchAutocomplete\Model\Result;
use Magento\Framework\Config\CacheInterface;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\PageCache\Model\Config as PageCacheConfig;

/**
 * Plugin for processing ajax requests
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ResponsePlugin
{
    /**
     * @var Result
     */
    private $result;

    /**
     * @var ResponseHttp
     */
    private $response;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var EventManagerInterface
     */
    private $eventManager;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var PageCacheConfig
     */
    private $pageCacheConfig;

    /**
     * @var Config
     */
    private $config;

    public function __construct(
        Result $result,
        ResponseHttp $response,
        CacheInterface $cache,
        EventManagerInterface $eventManager,
        StoreManagerInterface $storeManager,
        PageCacheConfig $pageCacheConfig,
        Config $config
    ) {
        $this->result = $result;
        $this->response = $response;
        $this->cache = $cache;
        $this->eventManager = $eventManager;
        $this->storeManager = $storeManager;
        $this->pageCacheConfig = $pageCacheConfig;
        $this->config = $config;
    }

    /**
     * Call method around dispatch frontend action
     *
     * @param FrontControllerInterface $subject
     * @param \Closure $proceed
     * @param RequestInterface $request
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD)
     */
    public function aroundDispatch(FrontControllerInterface $subject, \Closure $proceed, RequestInterface $request)
    {
        $startTime = microtime(true);
        if (isset($_SERVER['REQUEST_TIME_FLOAT'])) {
            $startTime = $_SERVER['REQUEST_TIME_FLOAT'];
        }

        /** @var \Magento\Framework\App\Request\Http $request */

        if (strpos($request->getOriginalPathInfo(), 'searchautocomplete/ajax/suggest') !== false) {
            $this->result->init();

            if (!$this->config->isFastMode()) {
                $proceed($request); #require for init translations
                $request->setControllerModule('Magento_CatalogSearch');
                $request->setDispatched(true);
            }

            $identifier = 'QUERY_' . $this->storeManager->getStore()->getId() . '_' . md5($request->getParam('q'));

            if ($this->pageCacheConfig->isEnabled() && $result = $this->cache->load($identifier)) {
                $result = \Zend_Json::decode($result);
                $result['time'] = round(microtime(true) - $startTime, 4);
                $result['cache'] = true;

                $data = \Zend_Json::encode($result);

            } else {
                // mirasvit core event
                $this->eventManager->dispatch('core_register_urlrewrite');

                $result = $this->result->toArray();

                $result['success'] = true;
                $result['time'] = round(microtime(true) - $startTime, 4);
                $result['cache'] = false;

                $data = \Zend_Json::encode($result);

                $this->cache->save(
                    $data,
                    $identifier,
                    [
                        'SEARCHAUTOCOMPLETE',
                        \Magento\PageCache\Model\Cache\Type::CACHE_TAG,
                        \Magento\Catalog\Model\Product::CACHE_TAG,
                    ]
                );
            }

            $this->response->setPublicHeaders(3600);

            return $this->response->setBody($data);
        } else {
            return $proceed($request);
        }
    }
}
