<?php
use yii\widgets\LinkPager;
use yii\bootstrap\Alert;
use yii\helpers\Url;
// https://developers.google.com/chart/interactive/docs/gallery/annotationchart#example
/* @var $this yii\web\View */
/* @var $model \app\models\TaskSocial */
/* @var $stats array */
/* @var $info \app\modules\Couber\models\CoubBigJson */
$this->title = Yii::t('app', 'Info').' #'.$model->getPrimaryKey();
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Task list'), 'url' => '/dashboard/index'];
$this->params['breadcrumbs'][] = $this->title;
?>


<? if(isset($info->id)):?>
<?=$this->render('taskList', ['data' => [$model]])?>
<div class="dushboard-task-info center-block">
    <div class="table-responsive">
        <table id="task-table" class="table table-hover table-striped" width="100%">
            <tbody>
                <tr><td><?=Yii::t('app', 'Id')?></td><td><?=$info->id?></td></tr>
                <tr><td><?=Yii::t('app', 'Type')?></td><td><?=$info->type?></td></tr>
                <tr><td><?=Yii::t('app', 'Link')?></td><td>http://coub.com/view/<?=$info->permalink?></td></tr>
                <tr><td><?=Yii::t('app', 'Title')?></td><td><?=$info->title?></td> </tr>
                <tr><td><?=Yii::t('app', 'Visible')?></td><td><?=$info->visibility_type?></td> </tr>
                <tr><td><?=Yii::t('app', 'Date create')?></td><td><?=$info->created_at?></td> </tr>
                <tr><td><?=Yii::t('app', 'Date update')?></td><td><?=$info->updated_at?></td> </tr>
                <tr><td><?=Yii::t('app', 'Duration in sec')?></td><td><?=$info->duration?></td> </tr>
                <tr><td><?=Yii::t('app', 'View')?></td><td><b><?=$info->views_count?></b></td> </tr>
                <tr><td><?=Yii::t('app', 'Repost')?></td><td><b><?=$info->recoubs_count?></b></td> </tr>
                <tr><td><?=Yii::t('app', 'Like')?></td><td><b><?=$info->likes_count?></b></td> </tr>
                <tr><td><?=Yii::t('app', 'Coub of the day')?></td><td><?=($info->cotd ? 'Yes in '.$info->cotd_at : 'no')?></td> </tr>
                <tr><td><?=Yii::t('app', 'Any abuses')?></td><td><?=($info->flag ? '<b color="red">Yes</b>' : ' - ')?></td> </tr>
            </tbody>
        </table>
    </div>
</div>
<? elseif(isset($info->error)): ?>
    <p class="alert alert-danger"><?=$info->error?></p>
<? endif; ?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<? foreach($model->getEnumTypes() as $typeKey=>$typeName):?>
    <div class="taskChart" id="chart_div_<?=$typeKey?>"></div>
<? endforeach; ?>
    <script>
        onLoadArr.push(function () {

            google.charts.load('current', {packages: ['annotationchart']});
            google.charts.setOnLoadCallback(drawCurveTypes);
            function drawCurveTypes() {
                <? foreach($model->getEnumTypes() as $typeKey=>$typeName):?>
                drawCurveTypes<?=$typeKey?>();
                <? endforeach; ?>
            }

            <? foreach($model->getEnumTypes() as $typeKey=>$typeName):?>
            function drawCurveTypes<?=$typeKey?>() {
                var data = new google.visualization.DataTable();
                data.addColumn('datetime', 'Date');
                data.addColumn('number', '<?=$typeName?>');

                data.addRows([
                    <? $start = true; ?>
                    <? foreach($stats as $r):
                        $json = json_decode($r['td_stats'], true);
                    ?>
                    <? if (!$start) echo ','; else $start = false; ?>
                    [new Date(Date.parse("<?=$r['td_date']?>")), <?=$json[$typeKey]?>]
                    <? endforeach; ?>
                ]);

                var options = {
                    displayAnnotations: false,
                    dateFormat: 'yyyy-MM-d HH:mm'
                };

                var chart = new google.visualization.AnnotationChart(document.getElementById('chart_div_<?=$typeKey?>'));
                chart.draw(data, options);
            }
            <? endforeach; ?>
        });
    </script>

<button type="button" class="btn btn-info" onclick="$('#sourceInfo').toggle()">Show source info</button>
<pre id="sourceInfo" style="display: none">
    <? print_r($info) ?>
</pre>
<style>
    .taskChart {
        width: 80%;
        margin: 20px 0;
    }
</style>