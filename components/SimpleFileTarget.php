<?php
namespace app\components;

use yii\log\FileTarget;

class SimpleFileTarget extends FileTarget
{
    public function formatMessage($message)
    {
        list($text, $level, $category, $timestamp) = $message;
        $prefix = $this->getMessagePrefix($message);
        return $this->getTime($timestamp) . ' '.$prefix.' '.$_SERVER['REQUEST_URI'];
    }

}