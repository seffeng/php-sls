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
    private $host = 'dysmsapi.aliyuncs.com';

    /**
     *
     * @var string
     */
    private $action = 'SendSms';

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
    private $format ='json';

    /**
     *
     * @var string
     */
    private $signName;

    /**
     *
     * @var string
     */
    private $signature;

    /**
     *
     * @var string
     */
    private $signatureMethod = 'HMAC-SHA1';

    /**
     *
     * @var string
     */
    private $signatureNonce;

    /**
     *
     * @var string
     */
    private $signatureVersion = '1.0';

    /**
     *
     * @var string
     */
    private $regionId = 'cn-hangzhou';

    /**
     *
     * @var string
     */
    private $templateCode;

    /**
     *
     * @var string
     */
    private $version = '2017-05-25';

    /**
     *
     * @var string
     */
    private $timestamp;

    /**
     *
     * @var HttpClient
     */
    private $client;

    /**
     *
     * @author zxf
     * @date   2020年4月22日
     * @param string $accessKeyId
     * @param string $accessSecret
     */
    public function __construct(string $accessKeyId, string $accessSecret)
    {
        $this->accessKeyId = $accessKeyId;
        $this->accessSecret = $accessSecret;
        $this->client = new HttpClient(['base_uri' => $this->getscheme() . $this->getHost()]);
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
     * @date    2019年11月22日
     * @return string
     */
    public function getHost()
    {
        return $this->host;
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
    public function putLog(array $content)
    {
        try {
            print_r($content);exit;
            $params = $this->getParams();
            $headers = $this->getHeaders();
            $query = array_merge($headers, $params);
            $this->setSignature($query);
            $query['Signature'] = $this->getSignature();

            $request = $this->client->get('/', ['query' => $query])->getBody()->getContents();
            $content = json_decode($request, true);
            $errorCode = ArrayHelper::getValue($content, 'Code');

            if ($errorCode && $errorCode === 'OK') {
                return true;
            }

            $errorItem = new Error($errorCode);
            $message = $errorItem->getName();
            $message === '' && $message = ArrayHelper::getValue($content, 'Message', '短信发送失败！') .'['. ArrayHelper::getValue($content, 'Code') .']';
            throw new SLSException($message);
        } catch (RequestException $e) {
            $message = $e->getResponse()->getBody()->getContents();
            if (!$message) {
                $message = $e->getMessage();
            }
            throw new SLSException($message);
        } catch (\Exception $e) {
            throw $e;
        }
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
            'AccessKeyId' => $this->getAccessKeyId(),
            'Action' => $this->getAction(),
            'RegionId' => $this->getRegionId(),
            'SignatureMethod' => $this->getSignatureMethod(),
            'SignatureNonce' => $this->getSignatureNonce(),
            'SignatureVersion' => $this->getSignatureVersion(),
            'Timestamp' => $this->getDateTime(),
            'Version' => $this->getVersion(),
            'Format' => $this->getFormat(),
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
    public function getParams($phone, array $content)
    {
        is_array($phone) && $phone = implode(',', $phone);
        return [
            'PhoneNumbers' => $phone,
            'TemplateCode' => $this->getTemplateCode(),
            'SignName' => $this->getSignName(),
            'TemplateParam' => json_encode($content),
        ];
    }

    /**
     *
     * @author zxf
     * @date    2019年11月21日
     * @param string $signName
     * @return SLSClient
     */
    public function setSignName(string $signName)
    {
        $this->signName = $signName;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月21日
     * @return string
     */
    public function getSignName()
    {
        return $this->signName;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月21日
     * @param string $templateCode
     * @return SLSClient
     */
    public function setTemplateCode(string $templateCode)
    {
        $this->templateCode = $templateCode;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月21日
     * @return string
     */
    public function getTemplateCode()
    {
        return $this->templateCode;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @param  string $value
     * @return mixed
     */
    public function specialUrlEncode(string $value)
    {
        $value = urlencode($value);
        $value = str_replace('+', '%20', $value);
        $value = str_replace('*', '%2A', $value);
        $value = str_replace('%7E', '~', $value);
        return $value;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @param array $params
     * @return SLSClient
     */
    public function setSignature(array $params)
    {
        ksort($params);
        $string = '';
        foreach ($params as $key => $value) {
            $string .= '&'. $this->specialUrlEncode($key) .'='. $this->specialUrlEncode($value);
        }
        $string = ltrim($string, '&');
        $this->signature = base64_encode(hash_hmac('sha1', 'GET&'. $this->specialUrlEncode('/') .'&'. $this->specialUrlEncode($string), $this->getAccessSecret() .'&', true));
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
     * @param  string $action
     * @return SLSClient
     */
    public function setAction(string $action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @param  string $regionId
     * @return SLSClient
     */
    public function setRegionId(string $regionId)
    {
        $this->regionId = $regionId;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @return string
     */
    public function getRegionId()
    {
        return $this->regionId;
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
        return gmdate('Y-m-d\TH:i:s\Z', $this->getTimestamp());
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
     * @date    2019年11月25日
     * @param  string $signatureNonce
     * @return SLSClient
     */
    private function setSignatureNonce()
    {
        $this->signatureNonce = md5($this->getTimestamp() . rand(10000, 99999));
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @return string
     */
    private function getSignatureNonce()
    {
        if (is_null($this->signatureNonce)) {
            $this->setSignatureNonce();
        }
        return $this->signatureNonce;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @param  string $signatureVersion
     * @return SLSClient
     */
    private function setSignatureVersion(string $signatureVersion)
    {
        $this->signatureVersion = $signatureVersion;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @return string
     */
    private function getSignatureVersion()
    {
        return $this->signatureVersion;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @param  string $format
     * @return SLSClient
     */
    private function setFormat(string $format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @return string
     */
    private function getFormat()
    {
        return $this->format;
    }
}
