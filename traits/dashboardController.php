<?php
namespace app\traits;

use \Yii;
/**
 * Created by PhpStorm.
 * User: xakki
 * Date: 10.01.16
 * Time: 12:17
 */


trait dashboardController {

    public function renderFlushMessage() {
        $html = '<div class="flashMess">';
        $closeBtn = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>';
        $flash = Yii::$app->session->getAllFlashes(true);

        if (count($flash)) {
            foreach ($flash as $type=>$mess) {
                if (!is_array($mess)) {
                    $mess = [$mess];
                }
                foreach($mess as $item) {
                    if (is_array($item)) {
                        $item = implode('<br/>', $item);
                    }
                    $html .= '<div class="alert alert-' . $type . ' alert-dismissible fade in" role="alert">' . $closeBtn . $item . '</div>';
                }
            }
        }

        $html .= '</div>';
        return $html;
    }
}