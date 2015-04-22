<?php
/**
 * Author: Pavel Naumenko
 * @var MetaTagContent[] $metaTagModelList
 * @var \yii\db\ActiveRecord $model
 * @var array $languageList
 * @var string $defaultLanguage
 */
use notgosu\yii2\modules\metaTag\models\MetaTagContent;
use yii\helpers\Html;

foreach ($model->metaTags as $i => $data) {
    echo Html::beginTag('div', ['class' => 'form-group']);

    echo Html::activeLabel(
        $model,
        'metaTags[' . $i . '][content]',
        ['class' => 'control-label', 'label' => $data->metaTag->name . ' [' . $data->language . ']']
    );
    echo Html::activeTextInput($model, 'metaTags[' . $i . '][content]', ['class' => 'form-control']);
    echo Html::tag('div', $data->metaTag->description . ' [' . $data->language . ']', ['class' => 'hint-block']);

    echo Html::endTag('div');
}
