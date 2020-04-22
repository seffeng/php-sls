## 阿里云日志服务[SLS]

# 暂不可用











### 安装

```
$ composer require seffeng/sls
```

### 目录说明

```

```

### 示例

```php

```

```php

```

### 备注

1、阿里云  AccessKeyId 和  AccessKeySecret 为[子账号 AccessKey](https://help.aliyun.com/document_detail/53045.html) ；

2、本地 http 请求错误：(cURL error 60: SSL certificate problem: unable to get local issuer certificate.)。

2.1 下载 cacert.pem （[https://curl.haxx.se/docs/caextract.html](https://curl.haxx.se/docs/caextract.html)）；

2.2 修改 php.ini 修改 curl.cainfo 文件路径（绝对路径）：

```
[curl]
curl.cainfo = "/cacert.pem"
```

