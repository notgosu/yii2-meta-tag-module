<?php
/**
 * Author: Pavel Naumenko
 */

namespace notgosu\yii2\modules\metaTag\components;

use notgosu\yii2\modules\metaTag\models\MetaTag;
use notgosu\yii2\modules\metaTag\models\MetaTagContent;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class MetaTagRegistrator
 */
class MetaTagRegistrator
{
    /**
     * @param ActiveRecord $model
     * @param string $langCode
     * @param array $metaTagsToFetch
     */
    public static function register(ActiveRecord $model, $langCode = '', $metaTagsToFetch = [])
    {
        $metaTagsForModel = MetaTagContent::find()
            ->where([MetaTagContent::tableName().'.model_id' => $model->id])
            ->andWhere([MetaTagContent::tableName().'.model_name' => (new \ReflectionClass($model))->getShortName()])
            ->joinWith(['metaTag', 'metaTagContentLangs']);

        if (!empty($metaTagsToFetch)) {
            $metaTagsForModel->andWhere(['meta_tag_name' => $metaTagsToFetch]);
        } else {
            $metaTagsForModel->andWhere([MetaTag::tableName().'.is_active' => 1]);
        }

        $metaTagsForModel = $metaTagsForModel->all();

        if (!empty($metaTagsForModel)) {
            foreach ($metaTagsForModel as $metaTag) {
                if (!empty($langCode)) {
                    $langValues = ArrayHelper::map($metaTag->metaTagContentLangs, 'lang_id', 'meta_tag_content');
                    $content = isset($langValues[$langCode]) ? $langValues[$langCode] : '';
                } else {
                    $content = $metaTag->meta_tag_content;
                }
                if (!empty($content)) {
                    if (strtolower($metaTag->metaTag->meta_tag_name) === 'title') {
                        \Yii::$app->getView()->title = $content;
                    } else {
                        \Yii::$app->view->registerMetaTag(
                            [
                                'name' => $metaTag->metaTag->meta_tag_name,
                                'content' => $content
                            ],
                            $metaTag->metaTag->meta_tag_name
                        );
                    }
                }
            }
        }
    }
}
