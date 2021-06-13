<?php

ini_set("display_errors", "on");

require_once dirname(__DIR__) . '/msg_sdk/vendor/autoload.php';

require_once __DIR__ . '/lib/TokenGetterForAlicom.php';
require_once __DIR__ . '/lib/TokenForAlicom.php';

use Aliyun\Core\Config;
use AliyunMNS\Exception\MnsException;
use AliyunMNS\Requests\BatchReceiveMessageRequest; // 批量拉取请求

// 加载区域结点配置
Config::load();

/**
 * Class MsgDemo
 */
class MsgDemo
{

    /**
     * @var TokenGetterForAlicom
     */
    static $tokenGetter = null;

    public static function getTokenGetter() {

        $accountId = "1943695596114318"; // 此处不需要替换修改!

        // TODO 此处需要替换成开发者自己的AK (https://ak-console.aliyun.com/)

        $accessKeyId = "yourAccessKeyId"; // AccessKeyId

        $accessKeySecret = "yourAccessKeySecret"; // AccessKeySecret

        if(static::$tokenGetter == null) {
            static::$tokenGetter = new TokenGetterForAlicom(
                $accountId,
                $accessKeyId,
                $accessKeySecret);
        }
        return static::$tokenGetter;
    }

    /**
     * 获取消息
     *
     * @param string $messageType 消息类型
     * @param string $queueName 在云通信页面开通相应业务消息后，就能在页面上获得对应的queueName<br/>(e.g. Alicom-Queue-xxxxxx-xxxxxReport)
     * @param callable $callback <p>
     * 回调仅接受一个消息参数;
     * <br/>回调返回true，则工具类自动删除已拉取的消息;
     * <br/>回调返回false,消息不删除可以下次获取.
     * <br/>(e.g. function ($message) { return true; }
     * </p>
     */
    public static function receiveMsg($messageType, $queueName, callable $callback)
    {
        $i = 0;
        // 取回执消息失败3次则停止循环拉取
        while ( $i < 3)
        {
            try
            {
                // 取临时token
                $tokenForAlicom = static::getTokenGetter()->getTokenByMessageType($messageType, $queueName);

                // 使用MNSClient得到Queue
                $queue = $tokenForAlicom->getClient()->getQueueRef($queueName);

                // ------------------------------------------------------------------
                // 1. 单次接收消息，并根据实际情况设置超时时间
                $message = $queue->receiveMessage(2);

                // 计算消息体的摘要用作校验
                $bodyMD5 = strtoupper(md5(base64_encode($message->getMessageBody())));

                // 比对摘要，防止消息被截断或发生错误
                if ($bodyMD5 == $message->getMessageBodyMD5())
                {
                    // 执行回调
                    if(call_user_func($callback, json_decode($message->getMessageBody())))
                    {
                        // 当回调返回真值时，删除已接收的信息
                        $receiptHandle = $message->getReceiptHandle();
                        $queue->deleteMessage($receiptHandle);
                    }
                }
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                // 2. 批量接收消息
                // $res = $queue->batchReceiveMessage(new BatchReceiveMessageRequest(10, 5)); // 每次拉取10条，超时等待时间5秒

                // /* @var \AliyunMNS\Model\Message[] $messages */
                // $messages = $res->getMessages();

                // foreach($messages as $message) {
                //     // 计算消息体的摘要用作校验
                //     $bodyMD5 = strtoupper(md5(base64_encode($message->getMessageBody())));

                //     // 比对摘要，防止消息被截断或发生错误
                //     if ($bodyMD5 == $message->getMessageBodyMD5())
                //     {
                //         // 执行回调
                //         if(call_user_func($callback, json_decode($message->getMessageBody())))
                //         {
                //             // 当回调返回真值时，删除已接收的信息
                //             $receiptHandle = $message->getReceiptHandle();
                //             $queue->deleteMessage($receiptHandle);
                //         }
                //     }
                // }
                // ------------------------------------------------------------------

                return; // 整个取回执消息流程完成后退出
            }
            catch (MnsException $e)
            {
                $i++;
                echo "ex:{$e->getMnsErrorCode()}\n";
                echo "ReceiveMessage Failed: {$e}\n";
            }
        }
    }
}

// 调用示例：

header('Content-Type: text/plain; charset=utf-8');

echo "消息接口查阅短信状态报告返回结果:\n";
MsgDemo::receiveMsg(
    // 消息类型，SmsReport: 短信状态报告
    "SmsReport",

    // 在云通信页面开通相应业务消息后，就能在页面上获得对应的queueName
    "Alicom-Queue-xxxxxxxx-SmsReport",

    /**
     * 回调
     * @param stdClass $message 消息数据
     * @return bool 返回true，则工具类自动删除已拉取的消息。返回false，消息不删除可以下次获取
     */
    function ($message) {
        print_r($message);
        return false;
    }
);


echo "消息接口查阅短信服务上行返回结果:\n";
MsgDemo::receiveMsg(
    // 消息类型，SmsUp: 短信服务上行
    "SmsUp",

    // 在云通信页面开通相应业务消息后，就能在页面上获得对应的queueName
    "Alicom-Queue-xxxxxxxx-SmsUp",

    /**
     * 回调
     * @param stdClass $message 消息数据
     * @return bool 返回true，则工具类自动删除已拉取的消息。返回false，消息不删除可以下次获取
     */
    function ($message) {
        print_r($message);
        return false;
    }
);