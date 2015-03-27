<?php
/**
 * Author: Pavel Naumenko
 */
namespace notgosu\yii2\modules\metaTag\components;

use notgosu\yii2\modules\metaTag\models\MetaTag;
use notgosu\yii2\modules\metaTag\models\MetaTagContent;
use notgosu\yii2\modules\metaTag\models\MetaTagContentLang;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\validators\SafeValidator;

/**
 * Class MetaTagBehavior
 */
class MetaTagBehavior extends Behavior
{

    /**
     * Array of meta tag data list
     *
     * @var array
     */
    public $seoData;

    /**
     * List of application languages used
     *
     * @var array
     */
    public $languages = [];

    /**
     * Code of the default language
     *
     * @var string
     */
    public $defaultLanguage;


    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_INIT => 'attachValidator',
            ActiveRecord::EVENT_AFTER_INSERT => 'saveMetaTags',
            ActiveRecord::EVENT_AFTER_UPDATE => 'saveMetaTags',
            ActiveRecord::EVENT_AFTER_FIND => 'loadMetaTags'
        ];
    }

    public function attachValidator()
    {
        $this->owner->validators[] = new SafeValidator([
            'attributes' => ['seoData']
        ]);

    }

    public function init()
    {
        parent::init();

        if (!empty($this->languages) && empty($this->defaultLanguage)) {
            throw new InvalidConfigException('You must define default language when languages property set.');
        }
    }

    /**
     * @inheritdoc
     */
    public function attach($owner)
    {
        parent::attach($owner);

        $this->seoData = $this->getMetaTagList($owner);
    }

    /**
     * Load exist meta tags after model find
     */
    public function loadMetaTags()
    {
        $this->seoData = $this->getMetaTagList($this->owner);
    }

    /**
     * Delete old meta tags and save new meta tags
     */
    public function saveMetaTags()
    {
        $this->deleteOldMetaData($this->owner);
        $modelName = (new \ReflectionClass($this->owner))->getShortName();
        $modelId = $this->owner->id;

        foreach ($this->seoData as $seo) {
            $model = new MetaTagContent();
            $model->model_name = $modelName;
            $model->model_id = $modelId;
            $model->meta_tag_id = $seo['meta_tag_id'];
            $model->meta_tag_content = $seo['meta_tag_content'];
            if ($model->save(false)) {
                //Save data for additional languages
                if (!empty($this->languages)) {
                    foreach ($this->languages as $lang) {
                        $langModel = new MetaTagContentLang();
                        $langModel->model_id = $model->id;
                        $langModel->lang_id = $lang;
                        if ($lang != $this->defaultLanguage) {
                            $langModel->meta_tag_content = $seo['meta_tag_content_'.$lang];
                        } else {
                            $langModel->meta_tag_content = $seo['meta_tag_content'];
                        }

                        $langModel->save(false);
                    }
                }
            }
        }
    }

    /**
     * @param ActiveRecord $model
     *
     * @throws \yii\db\Exception
     */
    protected function deleteOldMetaData(ActiveRecord $model)
    {
        $modelName = (new \ReflectionClass($model))->getShortName();
        $modelId = $model->id;

        \Yii::$app->db->createCommand()
            ->delete(MetaTagContent::tableName(), 'model_id = :model_id AND model_name = :model_name', [
                ':model_id' => $modelId,
                ':model_name' => $modelName
            ])
            ->execute();
    }

    /**
     * @param ActiveRecord $model
     *
     * @return array
     */
    protected function getMetaTagList(ActiveRecord $model)
    {
        $metaTagList = $this->getTagList();

        if ($model->isNewRecord) {
            return $this->getTagListForNewModel($metaTagList);
        } else {
            $metaData = [];
            $existMetaData = MetaTagContent::find()
                ->where([MetaTagContent::tableName().'.model_name' => (new \ReflectionClass($model))->getShortName()])
                ->andWhere([MetaTagContent::tableName().'.model_id' => $model->id])
                ->joinWith(['metaTagContentLangs'])
                ->all();

            if (!empty($existMetaData)) {
                $existModelTagIds = [];

                //Fill exist data with languages to seoData property
                foreach ($existMetaData as $eData) {
                    $data['meta_tag_id'] = $eData->meta_tag_id;
                    $data['meta_tag_content'] = $eData->meta_tag_content;
                    $data['tagName'] = $eData->metaTag->meta_tag_name;
                    if (!empty($this->languages)) {
                        foreach ($this->languages as $lang) {
                            foreach ($eData->metaTagContentLangs as $metaTagLang) {
                                if ($metaTagLang->lang_id == $lang) {
                                    $data['meta_tag_content_'.$lang] = $metaTagLang->meta_tag_content;
                                }
                            }
                        }
                    }

                    $metaData[] = $data;
                    $existModelTagIds[] = $eData->meta_tag_id;
                }

                $metaData = ArrayHelper::merge($metaData, $this->addNewMetaTagToMetaTagList($metaTagList, $existModelTagIds));

                return $metaData;
            } else {
                return $this->getTagListForNewModel($metaTagList);
            }
        }

    }

    /**
     * Check if there is some new tags available to fill, but not saved with this model before
     *
     * @param $metaTagList
     * @param $existModelTagIds
     *
     * @return array
     */
    protected function addNewMetaTagToMetaTagList($metaTagList, $existModelTagIds)
    {
        $metaData = [];

        foreach ($metaTagList as $tag) {
            if (!in_array($tag->id, $existModelTagIds)) {
                $data['meta_tag_id'] = $tag->id;
                $data['meta_tag_content'] = $tag->meta_tag_default_value;
                $data['tagName'] = $tag->meta_tag_name;

                $metaData[] = $data;
            }
        }

        return $metaData;
    }

    /**
     * @param array $tagList
     *
     * @return array
     */
    protected function getTagListForNewModel(array $tagList)
    {
        $returnTagList = [];
        /**
         * @var \notgosu\yii2\modules\metaTag\models\MetaTag $tag
         */
        foreach ($tagList as $tag) {
            $data = [];
            $data['meta_tag_id'] = $tag->id;
            $data['meta_tag_content'] = $tag->meta_tag_default_value;
            $data['tagName'] = $tag->meta_tag_name;

            $returnTagList[] = $data;
        }

        return $returnTagList;
    }

    /**
     * @return mixed
     */
    protected function getTagList()
    {
        return MetaTag::find()
            ->where(['is_active' => 1])
            ->all();
    }
}
