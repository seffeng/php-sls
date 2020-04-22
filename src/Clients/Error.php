<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2019 seffeng
 */
namespace Seffeng\SLS\Clients;

use Seffeng\SLS\Helpers\ArrayHelper;

class Error
{
    /**
     *
     * @var string
     */
    private $errorCode;

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @param  string $errorCode
     */
    public function __construct(string $errorCode)
    {
        $this->errorCode = $errorCode;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @return string
     */
    public function getValue()
    {
        return $this->errorCode;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @return array|string
     */
    public function getName()
    {
        return ArrayHelper::getValue(self::fetchNameItems(), $this->getValue(), '');
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @return array
     */
    public static function fetchItems()
    {
        return array_keys(self::fetchNameItems());
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @return array
     */
    public static function fetchNameItems()
    {
        return [
            'isv.SMS_SIGNATURE_SCENE_ILLEGAL' => '短信所使用签名场景非法！',
            'isv.EXTEND_CODE_ERROR' => '扩展码使用错误，相同的扩展码不可用于多个签名！',
            'isv.DOMESTIC_NUMBER_NOT_SUPPORTED' => '国际/港澳台消息模板不支持发送境内号码！',
            'isv.DENY_IP_RANGE' => '源IP地址所在的地区被禁用！',
            'isv.DAY_LIMIT_CONTROL' => '触发日发送限额！',
            'isv.SMS_CONTENT_ILLEGAL' => '短信内容包含禁止发送内容！',
            'isv.SMS_SIGN_ILLEGAL' => '签名禁止使用！',
            'isp.RAM_PERMISSION_DENY' => 'RAM权限DENY！',
            'isv.OUT_OF_SERVICE' => '业务停机！',
            'isv.PRODUCT_UN_SUBSCRIPT' => '未开通云通信产品的阿里云客户！',
            'isv.PRODUCT_UNSUBSCRIBE' => '产品未开通！',
            'isv.ACCOUNT_NOT_EXISTS' => '账户不存在！',
            'isv.ACCOUNT_ABNORMAL' => '账户异常！',
            'isv.SMS_TEMPLATE_ILLEGAL' => '短信模版不合法！',
            'isv.SMS_SIGNATURE_ILLEGAL' => '短信签名不合法！',
            'isv.INVALID_PARAMETERS' => '参数异常！',
            'isp.SYSTEM_ERROR' => 'isp.SYSTEM_ERROR！',
            'isv.MOBILE_NUMBER_ILLEGAL' => '非法手机号！',
            'isv.MOBILE_COUNT_OVER_LIMIT' => '手机号码数量超过限制！',
            'isv.TEMPLATE_MISSING_PARAMETERS' => '模版缺少变量！',
            'isv.BUSINESS_LIMIT_CONTROL' => '业务限流！',
            'isv.INVALID_JSON_PARAM' => 'JSON参数不合法，只接受字符串值！',
            'isv.BLACK_KEY_CONTROL_LIMIT' => '黑名单管控！',
            'isv.PARAM_LENGTH_LIMIT' => '参数超出长度限制！',
            'isv.PARAM_NOT_SUPPORT_URL' => '不支持URL！',
            'isv.AMOUNT_NOT_ENOUGH' => '账户余额不足！',
            'isv.TEMPLATE_PARAMS_ILLEGAL' => '模版变量里包含非法关键字！',
            'SignatureDoesNotMatch' => '签名错误！',
            'InvalidTimeStamp.Expired' => '指定的时间戳或日期值已过期！',
            'SignatureNonceUsed' => '指定的签名随机数已被使用！',
            'InvalidVersion' => '指定的参数版本无效！',
            'InvalidAction.NotFound' => '找不到指定的api，请检查您的网址和方法！',
            'isv.SIGN_COUNT_OVER_LIMIT' => '一个自然日中申请签名数量超过限制！',
            'isv.TEMPLATE_COUNT_OVER_LIMIT' => '一个自然日中申请模板数量超过限制！',
            'isv.SIGN_NAME_ILLEGAL' => '签名名称不符合规范！',
            'isv.SIGN_FILE_LIMIT' => '签名认证材料附件大小超过限制！',
            'isv.SIGN_OVER_LIMIT' => '签名字符数量超过限制！',
            'isv.TEMPLATE_OVER_LIMIT' => '签名字符数量超过限制！',
        ];
    }
}