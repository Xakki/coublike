<?php

namespace app\controllers;

use Yii;
use app\modules\Support\models\Messages;
use app\models\User;
use app\models\TaskSocial;
use yii\helpers\Url;
use yii\base\Exception;

class DashboardController extends Controller
{
    const LIMIT_PAGE = 10;
    const PAGE_TASK_LIST = '/dashboard/index';
    const PAGE_FREE_LIST = '/dashboard/free-likes';
    public $layout = 'dashboard';

    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            $this->redirect('/');
            return false;
        }
        if (!Yii::$app->user->identity->hasAccount('coub')) {
            return $this->needCoubConection();
        }

        return parent::beforeAction($action);
    }

    public function checkTokenData() {
        if (!Yii::$app->user->identity->getCoubApi()) {
            return $this->needCoubConection();
        }
        return true;
    }

    public function actionIndex()
    {
        $render = TaskSocial::getTaskList(self::LIMIT_PAGE);
        return $this->render('taskList', $render);
//        return $this->render('index');
    }

    public function actionBuy()
    {
        return $this->render('buy');
    }

    /**
     * @param $id
     * @return string|\app\models\TaskSocial
     */
    private function defaultActionTask($id, $method = 'default')
    {
        $query = TaskSocial::find();
        $query->andFilterWhere(['user_id' => Yii::$app->user->id, 'id' => $id]);
        /** @var \app\models\TaskSocial $data */
        $data = $query->one();
        if (!$data || $data->isDel()) {
            $mess = Yii::t('app', 'Task dose not exists');
        }
        elseif ($method == 'del') {
            if ($data->isRun()) {
                $mess = Yii::t('app', 'For delete, need set pause this task!');
            }
            else {
                return $data;
            }
        }
        elseif ($method == 'default') {
            $countdown = $data->getTimeOut();
            if ($countdown) {
                $mess = Yii::t('app', 'Need wait for action with this task {0} sec.', [$countdown]);
            }
            elseif ($data->isBlocked()) {
                $mess = Yii::t('app', 'This task was blocked by reason').': '.$data->reason;
            }
            else {
                return $data;
            }
        }
        else {
            return $data;
        }
        return $mess;
    }


    public function actionTaskAdd()
    {
        $model = new TaskSocial(['scenario' => 'insert']);

        try {

            $model->social = TaskSocial::SOCIAL_COUB;
            $model->type = TaskSocial::TYPE_VIEW;
            if ($model->load(Yii::$app->request->post())) {

                // form inputs are valid, do something here
                $model->setIsNewRecord(true);
                if ($model->save()) {
                    Yii::$app->session->setFlash(FLASH_OK, Yii::t('app', 'Success create task'));
                    return $this->redirect(Url::to(self::PAGE_TASK_LIST));
                }
                else {
                    //Yii::$app->session->setFlash(FLASH_ERROR, 'Error insert data');
                }

            } else {
//                Yii::$app->session->setFlash(FLASH_AHTUNG, Yii::t('app', ''));
            }
        }
        catch (Exception $e) {
            Yii::$app->session->setFlash(FLASH_ERROR, $e->getMessage());
        }

        return $this->render('taskForm', [
            'model' => $model,
        ]);
    }

    public function actionTaskEdit($id)
    {
        $model =  $this->defaultActionTask($id);
        if ($model instanceof TaskSocial) {
            if ($model->isRun()) {
                $model = Yii::t('app', 'For edit, need set pause this task!');
            }
            else {
                try {
                    $model->setScenario('update');
                    if ($model->load(Yii::$app->request->post())) {
                        if ($model->validate()) {
                            if ($model->save()) {
                                Yii::$app->session->setFlash(FLASH_OK, Yii::t('app', 'Success update task'));
                                return $this->redirect(Url::to(self::PAGE_TASK_LIST));
                            } else {
                                //Yii::$app->session->setFlash(FLASH_ERROR, 'Error save data');
                            }
                        }
                    }
                }
                catch (Exception $e) {
                    Yii::$app->session->setFlash(FLASH_ERROR, $e->getMessage());
                }
                return $this->render('taskForm', [
                    'model' => $model,
                ]);
            }
        }
        $result = ['status'=> self::STATUS_ERROR, 'mess' => $model];
        return $this->render('', $result);
    }


    public function actionTaskInfo($id)
    {
        $model =  $this->defaultActionTask($id, 'info');
        if ($model instanceof TaskSocial) {
            $info = null;
            try {
                $info = $model->getSocialInfo();
            } catch (Exception $e) {
                Yii::error('getSocialInfo - ['.$e->getCode().']'.$e->getMessage());

                if (strpos($e->getMessage(), 'Coub not found')!==false) {
                    $oldLikes = $model->likes;
                    if($model->userDelete()) {
                        Yii::$app->session->setFlash(FLASH_OK, Yii::t('app', 'This coub is unavailable and was deleted. The {0} likes has been returned to your account.', [$oldLikes]));
                    } else {
                        Yii::$app->session->setFlash(FLASH_ERROR, Yii::t('app', 'This coub is unavailable. And there was an error in the removal process.'));
                    }
                    return $this->redirect(Url::to(self::PAGE_TASK_LIST));
                }
            }

            return $this->render('taskInfo', [
                'model' => $model,
                'info' => $info,
                'stats' => $model->getStats()
            ]);

        }
        $result = ['status'=> self::STATUS_ERROR, 'mess' => $model];
        return $this->render('', $result);
    }


    public function actionTaskDelete($id)
    {
        $result = ['status'=> self::STATUS_ERROR, 'mess' => ''];
        $model =  $this->defaultActionTask($id, 'del');
        if ($model instanceof TaskSocial) {
            try {
                $res = $model->userDelete();
            }
            catch (Exception $e) {
                $res = null;
                $result['mess'] = Yii::t('app', 'Error delete task').' : '.$e->getMessage();
            }

            if ($res) {
                $result['mess'] = Yii::t('app', 'Success delete task');
                $result['status'] = self::STATUS_OK;
            }
            elseif ($model->hasErrors()) {
                $result['mess'] = $model->getErrors();
            }
        }
        else {
            $result['mess'] = $model;
        }

        return $this->render('', $result);
    }

    public function actionTaskReset($id)
    {
        $result = ['status'=> self::STATUS_ERROR, 'mess' => ''];
        $model =  $this->defaultActionTask($id, 'reset');
        if ($model instanceof TaskSocial) {
            try {
                $res = $model->userReset();
            }
            catch (Exception $e) {
                $res = null;
                $result['mess'] = Yii::t('app', 'Error reset task').' : '.$e->getMessage();
            }

            if ($res) {
                $result['mess'] = Yii::t('app', 'Success reset task');
                $result['status'] = self::STATUS_OK;
            }
            elseif ($model->hasErrors()) {
                $result['mess'] = $model->getErrors();
            }
        }
        else {
            $result['mess'] = $model;
        }

        return $this->render('', $result);
    }

    public function actionTaskDelCompleted()
    {
        $result = ['status'=> self::STATUS_ERROR, 'mess' => ''];
        $query = TaskSocial::find();
        $query->andFilterWhere(['user_id' => Yii::$app->user->id, 'status' => [TaskSocial::STATUS_BLOCK, TaskSocial::STATUS_COMPLETE]]);
        /** @var \app\models\TaskSocial[] $dataAll */
        $dataAll = $query->all();

        if (!count($dataAll)) {
            $result['mess'] = Yii::t('app', 'Not exists task for delete');
        }
        else {
            foreach ($dataAll as $model) {
                if (!$model->userDelete()) {
                    $result['mess'] = Yii::t('app', 'Error task delete');
                    return $this->render('', $result);
                }
            }
            $result['mess'] = Yii::t('app', 'Success delete {0} task', [count($dataAll)]);
            $result['status'] = self::STATUS_OK;
        }


        return $this->render('', $result);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionTaskChangeStatus($id)
    {
        $result = ['status'=> self::STATUS_ERROR, 'mess' => ''];
        $model =  $this->defaultActionTask($id);
        if ($model instanceof TaskSocial) {
            if (!$model->isRun() && !$model->isPause()) {
                $result['mess'] = Yii::t('app', 'Unavailable change status');
            }
            else {
                if ($model->isRun())
                    $result['mess'] = Yii::t('app', 'Task was stop successful');
                else
                    $result['mess'] = Yii::t('app', 'Task was run successful');
                if ($model->toggleTaskStatus()) {
                    $result['status'] = self::STATUS_OK;
                }
            }
        }
        else {
            $result['mess'] = $model;
        }

        return $this->render('', $result);
    }

    public function actionFreeLikes()
    {
        if (empty($_GET['type']) || !TaskSocial::hasType($_GET['type'])) {
            $_GET['type'] = TaskSocial::TYPE_VIEW;
        }
        $idList = [];
        if (!empty($_GET['idList']) && is_array($_GET['idList'])) {
            $idList = array_map('intval',$_GET['idList']);
        }
        if(!$this->checkTokenData()) {
            return false;
        }
        if (!empty($_COOKIE['view']) && $_COOKIE['view'] == 'small') {
            $limit = 8;
        } else {
            $limit = 6;
        }
        $render = TaskSocial::getFreeList($limit, $idList);
        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('freeLikesItems', $render);
        }
        $render['tabCount'] = [];
        $originType = $_GET['type'];
        foreach(TaskSocial::getEnumType() as $type=>$name) {
            if ($type == $originType) {
                $render['tabCount'][$type] = $render['totalCount'];
            } else {
                $_GET['type'] = $type;
                $render['tabCount'][$type] = TaskSocial::getAvailableFreeLikeQuery()->count();
            }
        }
        $_GET['type'] = $originType;
        return $this->render('freeLikes', $render);
    }

    public function actionSetCompleteTask($id)
    {
        $this->checkTokenData();

        $result = [
            'status' => self::STATUS_ERROR,
            'mess' => '',
        ];
        $query = TaskSocial::getFreeLikeQueryById($id);
        /** @var \app\models\TaskSocial $model */
        $model = $query->one();
        if ($model) {
            $res = $model->setCompleteTask();
            if ($res===true) {
                $result['status'] = self::STATUS_OK;
                $result['action_cost'] = $model->action_cost;
                $result['mess'] = Yii::t('app','You get free likes: {0}', [$model->action_cost]);
            }
            else {
                $result['mess'] = $res;
            }
        }
        else {
            $result['mess'] = Yii::t('app', 'Task finished. Like unavailable.');
        }
        return $this->render('', $result);
    }

    public function actionSetIgnore($id)
    {
        $result = [
            'status' => self::STATUS_ERROR,
            'mess' => '',
        ];
        $query = TaskSocial::getFreeLikeQueryById($id);
        /** @var \app\models\TaskSocial $model */
        $model = $query->one();

        if ($model) {
            if ($model->addTaskActionIgnore()) {
                $result['status'] = self::STATUS_OK;
                $result['mess'] = Yii::t('app', 'Success delete task');
            }
            else {
                $result['mess'] = Yii::t('app', 'Error delete task');
            }
        }
        else {
            $result['status'] = self::STATUS_OK;
//            $result['mess'] = $model;
            $result['mess'] = Yii::t('app', 'Success delete task');
        }

        return $this->render('', $result);
    }

    public function actionReferrals()
    {
        $result = [];
        $query = User::find();
        $query->andFilterWhere(['referral_id' => Yii::$app->user->id]);
        $result['referrals'] = $query->all();
        return $this->render('referrals', $result);
    }

    public function actionStatistics()
    {
        return $this->render('statistics');
    }

}
