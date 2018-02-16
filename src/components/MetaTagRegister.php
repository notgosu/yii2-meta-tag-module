<?php
/**
 * Author: Pavel Naumenko
 */

namespace notgosu\yii2\modules\metaTag\components;

use notgosu\yii2\modules\metaTag\models\MetaTag;
use notgosu\yii2\modules\metaTag\models\MetaTagContent;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * class MetaTagRegister
 */
class MetaTagRegister
{
    /**
     * @var string
     */
    private static $seoText = '';

    /**
     * @param ActiveRecord $model
     * @param null|string $language
     * @param array|null $metaTagsToFetch
     */
    public static function register(ActiveRecord $model, $language = null, $metaTagsToFetch = null)
    {
        $metaTags = MetaTagContent::find()
            ->where([MetaTagContent::tableName() . '.model_id' => $model->id])
            ->andWhere([MetaTagContent::tableName() . '.model_name' => $model->formName()])
            ->joinWith(['metaTag']);

        if (is_array($metaTagsToFetch)) {
            $metaTags->andWhere([MetaTag::tableName() . '.name' => $metaTagsToFetch]);
        } else {
            $metaTags->andWhere([MetaTag::tableName() . '.is_active' => 1]);
        }

        if (!is_string($language)) {
            $language = Yii::$app->language;
        }
        $metaTags->andWhere([MetaTagContent::tableName() . '.language' => $language]);

        /** @var MetaTagContent[] $metaTags */
        $metaTags = $metaTags->all();

        foreach ($metaTags as $metaTag) {
            $content = $metaTag->getMetaTagContent();

            foreach ($model->attributes as $name => $value) {
                $content = str_replace("[$name]", $value, $content);
            }

            if (!empty($content)) {
                if (strtolower($metaTag->metaTag->name) === MetaTag::META_TITLE_NAME) {
                    Yii::$app->getView()->title = $content;
                } elseif (strtolower($metaTag->metaTag->name) === MetaTag::META_SEO_TEXT) {
                    self::$seoText .= $content;
                } elseif (strtolower($metaTag->metaTag->name) === MetaTag::META_ROBOTS) {
                    if ($content) {
                        Yii::$app->view->registerMetaTag(
                            [
                                'name' => 'robots',
                                'content' => 'noindex, FOLLOW',
                            ],
                            'robots'
                        );
                    }
                } else {
                    if ($metaTag->metaTag->name) {
                        Yii::$app->view->registerMetaTag(
                            [
                                ($metaTag->metaTag->is_http_equiv ? 'http-equiv' : 'name') => $metaTag->metaTag->name,
                                'content' => $content,
                            ],
                            $metaTag->metaTag->name
                        );
                    }
                }
            }
        }
    }

    /**
     * @return string
     */
    public static function getSeoText()
    {
        return self::$seoText;
    }
}
