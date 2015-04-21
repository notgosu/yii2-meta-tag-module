<?php
/**
 * Author: Pavel Naumenko
 */
namespace notgosu\yii2\modules\metaTag\components;

use notgosu\yii2\modules\metaTag\models\MetaTag;
use notgosu\yii2\modules\metaTag\models\MetaTagContent;
use notgosu\yii2\modules\metaTag\Module;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecord;
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
     * List of application languages used (locales)
     *
     * @var array
     */
    public $languages;

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

        if ($this->languages instanceof \Closure) {
            $this->languages = call_user_func($this->languages);
        }

        if (!is_array($this->languages)) {
            throw new InvalidConfigException(Module::t('metaTag', 'MetaTagBehavior::languages have to be array.'));
        }

        if (empty($this->languages)) {
            throw new InvalidConfigException(
                Module::t('metaTag', 'MetaTagBehavior::languages have to contains at least 1 item.')
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function attach($owner)
    {
        parent::attach($owner);

        $this->seoData = $this->getMetaTagList();
    }

    /**
     * Load exist meta tags after model find
     */
    public function loadMetaTags()
    {
        $this->seoData = $this->getMetaTagList();
    }

    /**
     * Delete old meta tags and save new meta tags
     */
    public function saveMetaTags()
    {
        $this->deleteOldMetaData();

        $current = $this->getMetaTagList();
        Model::loadMultiple($current, $this->seoData, '');

        foreach ($current as $seo) {
            $seo->save(false);
        }
    }

    /**
     * @throws \yii\db\Exception
     */
    protected function deleteOldMetaData()
    {
        $model = $this->owner;
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
     * @return array
     */
    protected function getMetaTagList()
    {
        $model = $this->owner;
        $metaTagList = $this->getTagList();
        $id = $model->id;
        $modelName = (new \ReflectionClass($model))->getShortName();

        if ($model->isNewRecord) {
            $metaData = $this->createNewTags($metaTagList);
        } else {
            $existing = MetaTagContent::find()
                ->where([MetaTagContent::tableName() . '.model_name' => $modelName])
                ->andWhere([MetaTagContent::tableName() . '.model_id' => $id])
                ->joinWith(['metaTag'])
                ->orderBy([MetaTag::tableName() . '.position' => SORT_DESC])
                ->indexBy(function ($row) {
                    return $row->meta_tag_id . $row->language;
                })
                ->all();

            $metaData = $existing;

            if (!empty($existing)) {

                foreach ($metaTagList as $tag) {
                    foreach ($this->languages as $language) {
                        if (!isset($existing[$tag->id . $language])) {
                            $data = new MetaTagContent();
                            $data->model_id = $id;
                            $data->model_name = $modelName;
                            $data->meta_tag_id = $tag->id;
                            $data->meta_tag_content = $tag->default_value;
                            $data->language = $language;
                            $data->populateRelation('metaTag', $tag);

                            $metaData[$tag->id . $language] = $data;
                        }
                    }
                }

            } else {
                $metaData = $this->createNewTags($metaTagList);
            }
        }

        return $metaData;
    }

    /**
     * @param array $tagList
     *
     * @return array
     */
    protected function createNewTags(array $tagList)
    {
        $model = $this->owner;
        $list = [];
        $id = $model->id;
        $modelName = (new \ReflectionClass($model))->getShortName();
        /**
         * @var \notgosu\yii2\modules\metaTag\models\MetaTag $tag
         */
        foreach ($tagList as $tag) {
            foreach ($this->languages as $language) {
                $data = new MetaTagContent();
                $data->model_name = $modelName;
                $data->model_id = $id;
                $data->meta_tag_id = $tag->id;
                $data->meta_tag_content = $tag->default_value;
                $data->language = $language;
                $data->populateRelation('metaTag', $tag);

                $list[$tag->id . $language] = $data;
            }

        }

        return $list;
    }

    /**
     * Get list of active meta tags
     *
     * @return MetaTag[]
     */
    protected function getTagList()
    {
        return MetaTag::find()
            ->where('is_active = :is_active', [':is_active' => 1])
            ->orderBy(['position' => SORT_DESC])
            ->all();
    }
}
