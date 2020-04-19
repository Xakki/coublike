<?php

namespace app\components;
use yii\i18n\MissingTranslationEvent;

class TranslationEventHandler
{
    public static $tt = [];
    public static function handleMissingTranslation(MissingTranslationEvent $event) {

        $file = \Yii::getAlias('@app/messages').'/'.$event->language . '/' . $event->category.'.php';
        self::$tt[$file][$event->message] = '*'.$event->message;

        //register_shutdown_function(array($this, 'onShutdownHandler'));
        \Yii::$app->response->on(\yii\web\Response::EVENT_AFTER_SEND, function ($event) {
            foreach (\app\components\TranslationEventHandler::$tt as $file=>$dataNew) {
                if (file_exists($file)) {
                    $data = include($file);
                    $data += $dataNew;
                }
                else {
                    $data = [];
                }
                \app\components\TranslationEventHandler::saveArray2Php($file, $data);
            }
        });
    }

    static function saveArray2Php($file, $data, $comment = '') {
        $res = file_put_contents($file, '<?php'.PHP_EOL.
            '// create '.date('Y-m-d H:i:s').PHP_EOL.
            ($comment ? $comment.PHP_EOL : '').
            'return '.var_export($data, true).';');
        return $res;
    }
}