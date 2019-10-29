<?php
/**
 * shopify plugin for Craft CMS 3.x
 *
 * test
 *
 * @link      https://maier-niklas.de/
 * @copyright Copyright (c) 2018 niklas
 */

namespace shopify;

use Craft;
use craft\base\Plugin;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Fields;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\twig\variables\CraftVariable;

use shopify\services\ShopifyService;
use shopify\fieldtypes\ProductFieldType;
use shopify\variables\ShopifyVariables;

use yii\base\Event;

/**
 * Craft plugins are very much like little applications in and of themselves. We’ve made
 * it as simple as we can, but the training wheels are off. A little prior knowledge is
 * going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL,
 * as well as some semi-advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://craftcms.com/docs/plugins/introduction
 *
 * @author    niklas
 * @package   Shopify
 * @since     1.0.6
 *
 */
class Shopify extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * Shopify::$plugin
     *
     * @var Shopify
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * To execute your plugin’s migrations, you’ll need to increase its schema version.
     *
     * @var string
     */
    public $schemaVersion = '1.0.6';


    /**
     * defines whether the plugin has a settings-page or not
     * @var bool
     */
    public $hasCpSettings = true;



    // Public Methods
    // =========================================================================

    /**
     * Set our $plugin static property to this class so that it can be accessed via
     * Shopify::$plugin
     *
     * Called after the plugin class is instantiated; do any one-time initialization
     * here such as hooks and events.
     *
     * If you have a '/vendor/autoload.php' file, it will be loaded for you automatically;
     * you do not need to load it in your init() method.
     *
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;


        // Register to init-fields-event to add products-field to the list
        Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_TYPES, function(RegisterComponentTypesEvent $event) {
            $event->types[] = ProductFieldType::class;
        });


        // Register services
        $this->setComponents([
            'service' => ShopifyService::class
        ]);


        // Register plugin variables/functions
        Event::on(CraftVariable::class, CraftVariable::EVENT_INIT, function(Event $event) {
            /** @var CraftVariable $variable */
            $variable = $event->sender;
            $variable->set('shopify', ShopifyVariables::class);
        });



        if (class_exists('\verbb\feedme\services\Fields')) {
            // Register fieldtype with Feed Me
            Event::on(
                \verbb\feedme\services\Fields::class,
                \verbb\feedme\services\Fields::EVENT_REGISTER_FEED_ME_FIELDS,
                function(\verbb\feedme\events\RegisterFeedMeFieldsEvent $event) {
                    $event->fields[] = utilities\feedme\Shopify::class;
                }
            );
        }


        /**
         * Logging in Craft involves using one of the following methods:
         *
         * Craft::trace(): record a message to trace how a piece of code runs. This is mainly for development use.
         * Craft::info(): record a message that conveys some useful information.
         * Craft::warning(): record a warning message that indicates something unexpected has happened.
         * Craft::error(): record a fatal error that should be investigated as soon as possible.
         *
         * Unless `devMode` is on, only Craft::warning() & Craft::error() will log to `craft/storage/logs/web.log`
         *
         * It's recommended that you pass in the magic constant `__METHOD__` as the second parameter, which sets
         * the category to the method (prefixed with the fully qualified class name) where the constant appears.
         *
         * To enable the Yii debug toolbar, go to your user account in the AdminCP and check the
         * [] Show the debug toolbar on the front end & [] Show the debug toolbar on the Control Panel
         *
         * http://www.yiiframework.com/doc-2.0/guide-runtime-logging.html
         */
        Craft::info(
            Craft::t(
                'shopify',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }


    // Protected Methods
    // =========================================================================

    /**
     * @return \craft\base\Model|null|models\Settings
     */
    protected function createSettingsModel()
    {
        return new models\Settings();
    }

    /**
     * @return null|string
     * @throws \Twig_Error_Loader
     * @throws \yii\base\Exception
     */
    protected function settingsHtml()
    {
        return \Craft::$app->getView()->renderTemplate('shopify/settings', [
            'settings' => $this->getSettings()
        ]);
    }

}
