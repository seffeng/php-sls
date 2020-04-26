<?php
namespace Seffeng\SLS\Clients;

use Seffeng\SLS\SLSClient;

class LogStore
{
    /**
     *
     * @var SLSClient
     */
    private $client;

    /**
     *
     * @author zxf
     * @date   2020年4月26日
     * @param SLSClient $client
     */
    public function __construct(SLSClient $client)
    {
        $this->client = $client;
    }

    /**
     *
     * @author zxf
     * @date   2020年4月26日
     * @return \Seffeng\SLS\SLSClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     *
     * @author zxf
     * @date   2020年4月26日
     * @return string[]
     */
    public function getLogStoreItems()
    {
        $httpClient = $this->client->setHttpClient();
        $headers = $this->getClient()->setSignature('/logstores', [])->getHeaders();
        $headers['Authorization'] = 'LOG '. $this->getClient()->getAccessKeyId() .':'. $this->getClient()->getSignature();
        $request = $httpClient->get('/logstores', [
            'headers' => $headers
        ]);
        print_r($request->getBody()->getContents());exit;
    }

    public function getShards()
    {
        $httpClient = $this->client->setHttpClient();
        $headers = $this->getClient()->setSignature('/logstores/log-test/shards', [])->getHeaders();
        $headers['Authorization'] = 'LOG '. $this->getClient()->getAccessKeyId() .':'. $this->getClient()->getSignature();
        $request = $httpClient->get('/logstores/log-test/shards', [
            'headers' => $headers
        ]);
        print_r($request->getBody()->getContents());exit;
    }

    public function putLogs(array $contents)
    {
        $httpClient = $this->client->setHttpClient();
        $logItem = new LogItem();
        $logItem->setContents(['aaa' => 'bbbb', '操作员' => 'ad打日']);
        $logGroup = new LogGroup();
        $logGroup->addLogItem($logItem);
        $headers = $this->getClient()->setSignature('/logstores/log-test/shards/lb', $logItem->getContents())->getHeaders();
        $headers['Authorization'] = 'LOG '. $this->getClient()->getAccessKeyId() .':'. $this->getClient()->getSignature();
        $headers['Content-MD5'] = '';
        $request = $httpClient->post('/logstores/log-test/shards/lb', [
            'headers' => $headers,
            'form_params' => [
                'Time' => time(),
                'Contents' => ['aaa' => 'bbbb', '操作员' => 'ad打日'],
            ]
        ]);
        print_r($request->getBody()->getContents());exit;
    }
}
