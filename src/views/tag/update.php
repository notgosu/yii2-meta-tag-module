<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \notgosu\yii2\modules\metaTag\models\MetaTag */

$this->title = Yii::t('metaTag', 'Update {modelClass}: ', [
    'modelClass' => 'Meta Tag',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('metaTag', 'Meta Tags'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('metaTag', 'Update');
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title"><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="panel-body">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>
