<?php
namespace Seffeng\SLS\Clients;

class LogGroup
{
    /**
     *
     * @var LogItem
     */
    private $logItem;

    /**
     *
     * @var LogTag
     */
    private $logTags;

    /**
     *
     * @var string
     */
    private $topic;

    /**
     *
     * @var string
     */
    private $source;

    /**
     *
     * @author zxf
     * @date   2020年4月26日
     * @param string $source
     * @return \Seffeng\SLS\Clients\LogGroup
     */
    public function setSource(string $source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2020年4月26日
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     *
     * @author zxf
     * @date   2020年4月26日
     * @param string $topic
     * @return \Seffeng\SLS\Clients\LogGroup
     */
    public function setTopic(string $topic)
    {
        $this->topic = $topic;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2020年4月26日
     * @return string
     */
    public function getTopic()
    {
        return $this->topic;
    }


    /**
     *
     * @author zxf
     * @date   2020年4月26日
     * @return string
     */
    public function addLogItem(LogItem $logItem)
    {
        $this->logItem[] = $logItem;
    }
}
