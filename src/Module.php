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

	/**
	 * @var array list of application languages used (locales)
	 */
	public $languages;

	public function init()
	{
		parent::init();

		if ($this->languages instanceof \Closure) {
			$this->languages = call_user_func($this->languages);
		}

		if (!is_array($this->languages)) {
			throw new InvalidConfigException(Module::t('metaTag', 'MetaTag module [languages] have to be array.'));
		}

		if (empty($this->languages)) {
			throw new InvalidConfigException(
				Module::t('metaTag', 'MetaTag module [languages] have to contains at least 1 item.')
			);
		}

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

	/**
	 * Generate route to tag controller
	 *
	 * @return array
	 * @throws InvalidConfigException
	 */
	public static function getTagRoute()
	{
		$module = static::getInstance();

		if (!$module || !($module instanceof Module)) {
			throw new InvalidConfigException(Module::t('metaTag', 'You need configure MetaTag module first!'));
		}

		$id = $module->id;

		return ["/{$id}/tag/index"];
	}

	public static function getLanguages()
	{
		$module = static::getInstance();

		if (!$module || !($module instanceof Module)) {
			throw new InvalidConfigException(Module::t('metaTag', 'You need configure MetaTag module first!'));
		}

		return $module->languages;
	}
}
