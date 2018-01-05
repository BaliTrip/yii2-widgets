<?php

namespace balitrip\widgets; 

use Yii;

class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'balitrip\widgets\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['widgets'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-GB',
            'basePath' => '@balitrip/widgets/messages',
        ];
    }
}
