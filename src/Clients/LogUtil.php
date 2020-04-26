<?php

namespace Seffeng\SLS\Clients;

class LogUtil
{
    /**
     *
     * @author zxf
     * @date   2020年4月26日
     * @param  array $header
     * @return string
     */
    public static function canonicalizedHeaders(array $headers)
    {
        ksort($headers);
        $content = '';
        $first = true;
        foreach ($headers as $key => $value ) {
            if (strpos($key, 'x-log-') === 0 || strpos($key, 'x-acs-') === 0) {
                if ($first) {
                    $content .= $key . ':' . $value;
                    $first = false;
                } else {
                    $content .= "\n" . $key . ':' . $value;
                }
            }
        }
        return $content;
    }

    /**
     *
     * @author zxf
     * @date   2020年4月26日
     * @param string $resource
     * @param array $params
     * @return string
     */
    public static function canonicalizedResource(string $resource, array $params = []) {
        if ($params) {
            ksort($params);
            $urlString = '';
            $first = true;
            foreach ($params as $key => $value) {
                if ($first) {
                    $first = false;
                    $urlString = $key . '=' . $value;
                } else {
                    $urlString .= '&' . $key . '=' . $value;
                }
            }
            return $resource . '?' . $urlString;
        }
        return $resource;
    }

    public static function toBytes(LogGroup $logGroup)
    {
        $mem = fopen("php://memory", "rwb");
        $logGroup->write($mem);
        rewind($mem);
        $bytes = '';

        if (feof($mem) === false) {
            $bytes = fread($mem, 10 * 1024 * 1024);
        }
        fclose($mem);
        return $bytes;
    }
}