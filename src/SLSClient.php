<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2020 seffeng
 */
namespace Seffeng\SLS;

use Seffeng\SLS\Exceptions\SLSException;
use GuzzleHttp\Client as HttpClient;
use Seffeng\SLS\Helpers\ArrayHelper;
use GuzzleHttp\Exception\RequestException;
use Seffeng\SLS\Clients\Error;
use Seffeng\SLS\Clients\LogStore;
use Seffeng\SLS\Clients\LogUtil;

class SLSClient
{
    /**
     *
     * @var string
     */
    private $scheme = 'https://';

    /**
     *
     * @var string
     */
    private $host;

    /**
     *
     * @var integer
     */
    private $port;

    /**
     *
     * @var string
     */
    private $endpoint;

    /**
     *
     * @var string
     */
    private $accessKeyId;

    /**
     *
     * @var string
     */
    private $accessSecret;

    /**
     *
     * @var string
     */
    private $source;

    /**
     *
     * @var string
     */
    private $project;

    /**
     *
     * @var string
     */
    private $logStore;

    /**
     *
     * @var string
     */
    private $method;

    /**
     *
     * @var string
     */
    private $signatureMethod = 'hmac-sha1';

    /**
     *
     * @var string
     */
    private $version = '0.6.0';

    /**
     *
     * @var string
     */
    private $timestamp;

    /**
     *
     * @var string
     */
    private $signature;

    /**
     *
     * @var string
     */
    private $compresstype = 'deflate';

    /**
     *
     * @var HttpClient
     */
    private $httpClient;

    /**
     *
     * @author zxf
     * @date   2020年4月22日
     * @param string $accessKeyId
     * @param string $accessSecret
     */
    public function __construct(string $accessKeyId, string $accessSecret, string $endpoint)
    {
        $this->accessKeyId = $accessKeyId;
        $this->accessSecret = $accessSecret;
        $this->endpoint = $endpoint;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月27日
     * @return boolean
     */
    public function getIsHttps()
    {
        return isset($_SERVER['HTTPS']) ? ((empty($_SERVER['HTTPS']) || strtolower($_SERVER['HTTPS']) === 'off') ? false : true) : false;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @return string
     */
    public function getscheme()
    {
        return $this->getIsHttps() ? 'https://' : 'http://';
    }

    /**
     *
     * @author zxf
     * @date   2020年4月26日
     * @return string
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     *
     * @author zxf
     * @date   2020年4月26日
     * @param string $project
     * @return \Seffeng\SLS\SLSClient
     */
    public function setProject(string $project)
    {
        $this->project = $project;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2020年4月26日
     * @return string
     */
    public function getLogStore()
    {
        return $this->logStore;
    }

    /**
     *
     * @author zxf
     * @date   2020年4月26日
     * @param string $logStore
     * @return \Seffeng\SLS\SLSClient
     */
    public function setLogStore(string $logStore)
    {
        $this->logStore = $logStore;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2020年4月26日
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     *
     * @author zxf
     * @date   2020年4月26日
     * @param string $method
     * @return \Seffeng\SLS\SLSClient
     */
    public function setMethod(string $method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2020年4月26日
     * @return \GuzzleHttp\Client
     */
    public function getHttpClient()
    {
        if (is_null($this->httpClient)) {
            $this->setHttpClient();
        }
        return $this->httpClient;
    }

    /**
     *
     * @author zxf
     * @date   2020年4月26日
     * @return \GuzzleHttp\Client
     */
    public function setHttpClient()
    {
        $this->httpClient = new HttpClient(['base_uri' => $this->getscheme() . $this->getHost()]);
        return $this->httpClient;
    }

    /**
     *
     * @author zxf
     * @date   2020年4月26日
     * @return string
     */
    public function getHost()
    {
        if (is_null($this->getProject())) {
            return $this->getEndpoint();
        } else {
            return $this->getProject() .'.'. $this->getEndpoint();
        }
    }

    /**
     *
     * @author zxf
     * @date   2020年4月26日
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     *
     * @author zxf
     * @date   2020年4月26日
     * @param string $endpoint
     * @return \Seffeng\SLS\SLSClient
     */
    public function setEndpoint(string $endpoint)
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @param  string $accessKeyId
     * @return SLSClient
     */
    public function setAccessKeyId(string $accessKeyId)
    {
        $this->accessKeyId = $accessKeyId;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @return string
     */
    public function getAccessKeyId()
    {
        return $this->accessKeyId;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @param  string $accessSecret
     * @return SLSClient
     */
    public function setAccessSecret(string $accessSecret)
    {
        $this->accessSecret = $accessSecret;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @return string
     */
    public function getAccessSecret()
    {
        return $this->accessSecret;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月21日
     * @param  string|array $phone
     * @param  array $content
     * @throws SLSException
     * @return boolean
     */
    public function putLogs(array $content)
    {
        return (new LogStore($this))->putLogs($content);
    }

    public function getLogStoreItems()
    {
        return (new LogStore($this))->getLogStoreItems();
    }

    public function getShards()
    {
        return (new LogStore($this))->getShards();
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @return array
     */
    public function getHeaders()
    {
        return [
            'Content-Length' => 0,
            'x-log-bodyrawsize' => 0,
            'Content-MD5' => '',
            'Content-Type' => 'application/x-protobuf',
            'x-log-apiversion' => $this->getVersion(),
            'x-log-signaturemethod' => $this->getSignatureMethod(),
            'Host' => $this->getHost(),
            'Date' => $this->getDateTime(),
        ];
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @param  string|array $phone
     * @param  array $content
     * @return array
     */
    public function getBody(array $content)
    {
        return [
        ];
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @param string $resource
     * @param array $body
     * @return SLSClient
     */
    public function setSignature(string $resource, array $body = [])
    {
        $headers = $this->getHeaders();
        $content = "GET\n";
        if (isset($headers['Content-MD5'])) {
            $content .= $headers['Content-MD5'];
            //$content .= md5('aaa=aaaa&bbbb=bbbb');
        }
        $content .= "\n";
        if (isset($headers['Content-Type'])) {
            $content .= $headers['Content-Type'];
            $content .= "\n";
            $content .= $headers['Date'] . "\n";
            $content .= LogUtil::canonicalizedHeaders($headers) . "\n";
            $content .= LogUtil::canonicalizedResource($resource, $body);
        }
        $this->signature = base64_encode(hash_hmac('sha1', $content, $this->getAccessSecret(), true));
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @param  string $version
     * @return SLSClient
     */
    public function setVersion(string $version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @return string
     */
    public function getDateTime()
    {
        return gmdate('D, d M Y H:i:s', $this->getTimestamp()). ' GMT';
    }

    /**
     *
     * @author zxf
     * @date    2019年11月22日
     * @return SLSClient
     */
    public function setTimestamp()
    {
        $this->timestamp = time();
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月22日
     * @return number
     */
    public function getTimestamp()
    {
        if (is_null($this->timestamp)) {
            $this->setTimestamp();
        }
        return $this->timestamp;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @param  string $signatureMethod
     * @return SLSClient
     */
    private function setSignatureMethod(string $signatureMethod)
    {
        $this->signatureMethod = $signatureMethod;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @return string
     */
    private function getSignatureMethod()
    {
        return $this->signatureMethod;
    }

    /**
     *
     * @author zxf
     * @date   2020年4月26日
     * @return string
     */
    private function getCompresstype()
    {
        return $this->compresstype;
    }
}
