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
    public $metaTags = [];

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
            ActiveRecord::EVENT_AFTER_FIND => 'loadMetaTags',
            ActiveRecord::EVENT_AFTER_DELETE => 'deleteExistingMetaTags'
        ];
    }

    public function attach($owner)
    {
        parent::attach($owner);
        $this->metaTags = $this->getExistingMetaTags();
    }


    public function attachValidator()
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;
        $model->validators[] = new SafeValidator([
            'attributes' => ['metaTags']
        ]);
    }

    public function init()
    {
        parent::init();

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
     * Load exist meta tags after model find
     */
    public function loadMetaTags()
    {
        $this->metaTags = $this->getExistingMetaTags();
    }

    /**
     * Delete old meta tags and save new meta tags
     */
    public function saveMetaTags()
    {
        $existing = $this->getExistingMetaTags();
        Model::loadMultiple($existing, $this->metaTags, '');

        foreach ($existing as $tags) {
            $tags->save(false);
        }
    }

    /**
     * Delete existing meta tags
     */
    protected function deleteExistingMetaTags()
    {
        /** @var ActiveRecord $model */
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
     * @return MetaTagContent[]
     */
    protected function getExistingMetaTags()
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;
        $metaTagList = $this->getTagList();
        $id = $model->id;
        $modelName = (new \ReflectionClass($model))->getShortName();
        $metaTags = [];
        if (!$model->isNewRecord) {
            $metaTags = MetaTagContent::find()
                ->where([MetaTagContent::tableName() . '.model_name' => $modelName])
                ->andWhere([MetaTagContent::tableName() . '.model_id' => $id])
                ->joinWith(['metaTag'])
                ->orderBy([MetaTag::tableName() . '.position' => SORT_DESC])
                ->indexBy(function ($row) {
                    return $row->meta_tag_id . $row->language;
                })
                ->all();
        }

        foreach ($metaTagList as $tag) {
            foreach ($this->languages as $language) {
                if (!isset($metaTags[$tag->id . $language])) {
                    $data = new MetaTagContent();
                    $data->model_id = $id;
                    $data->model_name = $modelName;
                    $data->meta_tag_id = $tag->id;
                    $data->content = $tag->default_value;
                    $data->language = $language;
                    $data->populateRelation('metaTag', $tag);

                    $metaTags[$tag->id . $language] = $data;
                }
            }
        }

        return $metaTags;
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
