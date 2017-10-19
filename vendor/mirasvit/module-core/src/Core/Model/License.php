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
 * @package   mirasvit/module-core
 * @version   1.2.34
 * @copyright Copyright (C) 2017 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\Core\Model;

use function GuzzleHttp\Psr7\build_query;
use Magento\Framework\FlagFactory;
use Magento\Framework\HTTP\Adapter\CurlFactory;
use Magento\Framework\Module\ModuleListInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\ProductMetadata;
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\Config\ConfigOptionsListConstants;
use Magento\Framework\Module\Dir\Reader as DirReader;

/**
 * @SuppressWarnings(PHPMD)
 */
class License
{
    const EDITION_EE = 'EE';
    const EDITION_CE = 'CE';

    const STATUS_ACTIVE = 'active';
    const STATUS_LOCKED = 'locked';
    const STATUS_INVALID = 'invalid';

    /**
     * @var UrlInterface
     */
    protected $urlManager;

    /**
     * @var ModuleListInterface
     */
    protected $moduleList;

    /**
     * @var CurlFactory
     */
    protected $curlFactory;

    /**
     * @var FlagFactory
     */
    protected $flagFactory;

    /**
     * @var ProductMetadata
     */
    protected $productMetadata;

    /**
     * @var DeploymentConfig
     */
    protected $deploymentConfig;

    /**
     * @var DirReader
     */
    protected $dirReader;

    /**
     * @var string
     */
    private $license;

    /**
     * @var string
     */
    private $key;

    public function __construct(
        UrlInterface $urlManager,
        ModuleListInterface $moduleList,
        CurlFactory $curlFactory,
        FlagFactory $flagFactory,
        ProductMetadata $productMetadata,
        DeploymentConfig $deploymentConfig,
        DirReader $dirReader
    ) {
        $this->urlManager = $urlManager;
        $this->moduleList = $moduleList;
        $this->curlFactory = $curlFactory;
        $this->flagFactory = $flagFactory;
        $this->productMetadata = $productMetadata;
        $this->deploymentConfig = $deploymentConfig;
        $this->dirReader = $dirReader;
    }

    /**
     * @param string $className
     * @return bool|string
     */
    public function load($className = '')
    {
        $module = $this->getModuleByClass($className);

        if ($module == 'Mirasvit_Blog' || $module == 'Mirasvit_Demo') {
            return true;
        }

        $moduleDir = $this->getModuleDirByClass($className);
        if (!$moduleDir) {
            return true;
        }

        if (file_exists("$moduleDir/license")) {
            $license = explode(":", @file_get_contents("$moduleDir/license"));
            if (count($license) == 2) {
                $this->license = $license[0];
                $this->key = $license[1];

                return $this->license;
            }
        }

        return false;
    }

    /**
     * License status
     *
     * @param string $className
     *
     * @return true|string
     */
    public function getStatus($className = '')
    {
        if ($this->load($className) === true) {
            return true;
        }

        if (strpos($this->getDomain(), 'mirasvit.com') > 0) {
            return true;
        }

        if (!$this->license) {
            return "License not found";
        }

        if ($this->isNeedUpdate()) {
            $this->request();
            $data = $this->getFlagData($this->license);

            if (isset($data['status']) && $data['status'] === self::STATUS_ACTIVE) {
                return true;
            }
            if (isset($data['message'])) {
                return $data['message'];
            }
        }

        return true;
    }

    /**
     * @param string $className
     * @return false|string
     */
    private function getModuleDirByClass($className)
    {
        $module = $this->getModuleByClass($className);

        if ($module) {
            return $this->dirReader->getModuleDir("", $module);
        }

        return false;
    }

    /**
     * @param string $className
     * @return false|string
     */
    private function getModuleByClass($className)
    {
        $class = explode('\\', $className);
        if (isset($class[1])) {
            $module = 'Mirasvit_' . $class[1];

            return $module;
        }

        return false;
    }

    /**
     * Send request with all required data
     *
     * @return $this
     */
    private function request()
    {
        $params = [];
        $params['v'] = 3;
        $params['d'] = $this->getDomain();
        $params['ip'] = $this->getIP();
        $params['mv'] = $this->getVersion();
        $params['me'] = $this->getEdition();
        $params['l'] = $this->license;
        $params['k'] = $this->key;
        $params['uid'] = $this->getUID();

        $result = $this->sendRequest('https://lc.mirasvit.com/lc/check/', $params);

        $result['time'] = time();
        $this->saveFlagData($this->license, $result);

        return $this;
    }

    /**
     * @return string
     */
    public function getRequestUrl()
    {
        $params = [];
        $params['v'] = 3;
        $params['d'] = $this->getDomain();
        $params['ip'] = $this->getIP();
        $params['mv'] = $this->getVersion();
        $params['me'] = $this->getEdition();
        $params['l'] = $this->license;
        $params['k'] = $this->key;
        $params['uid'] = $this->getUID();

        $query = http_build_query($params);

        return 'https://lc.mirasvit.com/lc/check/?' . $query;
    }

    /**
     * @return bool
     */
    public function isNeedUpdate()
    {
        $data = $this->getFlagData($this->license);

        if (!$data) {
            return true;
        }

        if ($data && isset($data['status'])) {
            if ($data['status'] === self::STATUS_ACTIVE) {
                if (abs(time() - $data['time']) > 24 * 60 * 60) {
                    return true;
                }
            } else {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * Save request result to flag
     *
     * @param string $license
     * @param array $data
     * @return $this
     */
    private function saveFlagData($license, $data)
    {
        $flag = $this->flagFactory->create(['data' => ['flag_code' => "m" . $license]])
            ->loadSelf();

        $flag->setFlagData(base64_encode(serialize($data)))
            ->save();

        return $this;
    }

    /**
     * Return last request result
     *
     * @param string $license
     * @return array
     */
    private function getFlagData($license)
    {
        $flag = $this->flagFactory->create(['data' => ['flag_code' => "m" . $license]])
            ->loadSelf();

        if ($flag->getFlagData()) {
            $data = @unserialize(@base64_decode($flag->getFlagData()));

            if (is_array($data)) {
                return $data;
            }
        }

        return [];
    }

    /**
     * Remove flag data
     *
     * @return void
     */
    public function clear()
    {
        $flag = $this->flagFactory->create(['data' => ['flag_code' => "m" . $this->license]])
            ->loadSelf();

        $flag->delete();
    }

    /**
     * Send http request
     *
     * @param string $endpoint
     * @param array $params
     * @return array
     */
    private function sendRequest($endpoint, $params)
    {
        $curl = $this->curlFactory->create();
        $config = ['timeout' => 10];

        $curl->setConfig($config);
        $curl->write(
            \Zend_Http_Client::POST,
            $endpoint,
            '1.1',
            [],
            http_build_query($params, '', '&')
        );
        $response = $curl->read();

        $response = preg_split('/^\r?$/m', $response, 2);
        $response = trim($response[1]);

        $response = @unserialize($response);

        if (is_array($response)) {
            return $response;
        }

        return [];
    }

    /**
     * Backend domain
     *
     * @return string
     */
    private function getDomain()
    {
        return $this->urlManager->getCurrentUrl();
    }

    /**
     * Server IP
     *
     * @return string|bool
     */
    private function getIP()
    {
        return array_key_exists('SERVER_ADDR', $_SERVER)
            ? $_SERVER['SERVER_ADDR']
            : (array_key_exists('LOCAL_ADDR', $_SERVER) ? $_SERVER['LOCAL_ADDR'] : false);
    }

    /**
     * Magento edition
     *
     * @return string
     */
    private function getEdition()
    {
        if ($this->moduleList->has('Magento_Enterprise') || $this->moduleList->has('Magento_CustomerSegment')) {
            return 'EE';
        }

        return 'CE';
    }

    /**
     * Magento version
     *
     * @return string
     */
    private function getVersion()
    {
        return $this->productMetadata->getVersion();
    }

    /**
     * Unique installation key
     *
     * @return string
     */
    private function getUID()
    {
        $db = $this->deploymentConfig->get(
            ConfigOptionsListConstants::CONFIG_PATH_DB_CONNECTION_DEFAULT
            . '/' . ConfigOptionsListConstants::KEY_NAME);
        $host = $this->deploymentConfig->get(
            ConfigOptionsListConstants::CONFIG_PATH_DB_CONNECTION_DEFAULT
            . '/' . ConfigOptionsListConstants::KEY_HOST);

        return md5($db . $host);
    }
}
