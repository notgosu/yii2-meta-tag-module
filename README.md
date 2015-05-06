# Yii2 meta tag module
This module allow to create dynamic meta tags and fulfil them at any model.

## Install

The preferred way to install this module is through [composer](http://getcomposer.org/download/).

Either run

```bash
$ php composer.phar require --prefer-dist notgosu/yii2-meta-tag-module "dev-master"
```

or add

```json
"notgosu/yii2-meta-tag-module": "dev-master"
```

to the require section of your `composer.json` file.

Apply module migrations:

```php
./yii migrate --migrationPath=@vendor/notgosu/yii2-meta-tag-module/src/migrations
```


## Usage

### Connect behavior to required model:

```php
     public function behaviors()
     {
         return [
             //some other behaviors
             'seo' => [
                 'class' => \notgosu\yii2\modules\metaTag\components\MetaTagBehavior::className(),
                 'languages' => ['en', 'ua', 'ru'],
             ]
         ];
     }
```

### Use widget to fulfil meta tags:

```php
echo \notgosu\yii2\modules\metaTag\widgets\metaTagForm\Widget::widget(['model' => $model])
```

where ```$model``` is instance of your AR model.

### To add new tags or edit existing add module to your ```modules``` section of your main.php:

```php
 'modules' => [
        //Some other modules
        'seo' => [
            'class' => \notgosu\yii2\modules\metaTag\Module::className()
        ]
    ],
```

and go to http://your-website.dev/seo/tag/index.


### To register meta tags for certain AR model, simply call:
```php
 \notgosu\yii2\modules\metaTag\components\MetaTagRegister::register($model, Yii::$app->language);
```

from certain view or controller.

## Contributing

If you find any bug/issue, please submit new issue or pull-request. Any advices are welcome!
