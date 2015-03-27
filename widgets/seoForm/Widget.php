<?php
/**
 * Author: Pavel Naumenko
 */
namespace notgosu\yii2\modules\metaTag\widgets\seoForm;

/**
 * Class Widget
 */
class Widget extends \yii\base\Widget
{

    /**
     * @var \yii\db\ActiveRecord
     */
    public $model;

    /**
     * @return null|string
     */
    public function run()
    {
        /**
         * @var \notgosu\yii2\modules\metaTag\components\MetaTagBehavior $behavior
         */
        $behavior = $this->model->getBehavior('seo');
        if (!$behavior) {
            return null;
        }

        $languageList = $behavior->languages;
        $defaultLanguage = $behavior->defaultLanguage;

        return $this->render('default', [
            'model' => $this->model,
            'languageList' => $languageList,
            'defaultLanguage' => $defaultLanguage
        ]);
    }
}
