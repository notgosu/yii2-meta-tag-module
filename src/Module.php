<?php

namespace notgosu\yii2\modules\metaTag;

use Yii;
use yii\base\InvalidConfigException;

/**
 * Class Module
 *
 * @package notgosu\yii2\modules\metaTag
 */
class Module extends \yii\base\Module
{

	public $controllerNamespace = 'notgosu\yii2\modules\metaTag\controllers';

	public function init()
	{
		parent::init();

		$this->registerTranslations();
	}

	/**
	 * Register translate messages for module
	 */
	public function registerTranslations()
	{
		Yii::$app->i18n->translations['metaTag'] = [
			'class' => 'yii\i18n\PhpMessageSource',
			'sourceLanguage' => 'en-US',
			'basePath' => '@notgosu/yii2/modules/metaTag/messages',
		];
	}

	/**
	 * Translate shortcut
	 *
	 * @param $category
	 * @param $message
	 * @param array $params
	 * @param null $language
	 *
	 * @return string
	 */
	public static function t($category, $message, $params = [], $language = null)
	{
		return Yii::t($category, $message, $params, $language);
	}
}
