<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \notgosu\yii2\modules\metaTag\models\MetaTag */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('metaTag', 'Meta Tags'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title"><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="panel-body">
        <p>
            <?= Html::a(Yii::t('metaTag', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('metaTag', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('metaTag', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'name',
                'http_equiv',
                'default_value:ntext',
                'description',
                'is_active:boolean',
                'position',
            ],
        ]) ?>
    </div>

</div>

