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

    echo Html::activeLabel($model, 'metaTags[' . $i . '][meta_tag_content]', ['class' => 'control-label', 'label' => $data->metaTag->name . '[' . $data->language . ']']);
    echo Html::activeTextInput($model, 'metaTags[' . $i . '][meta_tag_content]', ['class' => 'form-control']);

    echo Html::endTag('div');
}
