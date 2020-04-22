<?php  declare(strict_types=1);

namespace Seffeng\SLS\Tests;

use PHPUnit\Framework\TestCase;
use Seffeng\SLS\Exceptions\SLSException;
use Seffeng\SLS\SLSClient;

class SLSTest extends TestCase
{
    public function testPut()
    {
        try {
            $appSecretId = '';          // 阿里云 AccessKeyId
            $appSecretKey = '';         // 阿里云 AccessKeySecret

            $client = new SLSClient($appSecretId, $appSecretKey);
            $result = $client->putLog(['aaa']);
            var_dump($appSecretId, $appSecretKey);exit;
            if ($result) {
                echo '发送成功！';
            } else {
                echo '发送失败！';
            }
        } catch (SLSException $e) {
            echo $e->getMessage();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
