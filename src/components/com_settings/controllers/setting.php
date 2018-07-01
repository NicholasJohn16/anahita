<?php

/**
 * System settings Controller.
 *
 * @category   Anahita
 *
 * @author     Rastin Mehr <rastin@anahitapolis.com>
 * @copyright  2008-2016 rmd Studio Inc.
 * @license    GNU GPLv3
 *
 * @link       http://www.GetAnahita.com
 */

class ComSettingsControllerSetting extends ComBaseControllerResource
{

    /**
    *  @param entity object
    */
    protected $_entity;

    /**
     * Constructor.
     *
     * @param KConfig $config An optional KConfig object with configuration options.
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->registerCallback('before.read', array($this, 'fetchEntity'));
        $this->registerCallback('before.edit', array($this, 'fetchEntity'));
    }

    /**
     * Initializes the options for the object.
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional KConfig object with configuration options.
     */
    protected function _initialize(KConfig $config)
    {
        parent::_initialize($config);

        $config->append(array(
            'toolbars' => array($this->getIdentifier()->name, 'menubar'),
        ));
    }

    /**
    *   read service
    *
    *  @param AnCommandContext $context Context Parameter
    *  @return void
    */
    protected function _actionGet(AnCommandContext $context)
    {
        $title = AnTranslator::_('COM-SETTINGS-HEADER-SYSTEM');

        $this->getToolbar('menubar')->setTitle($title);

        parent::_actionGet($context);
    }

    /**
    *   read service
    *
    *  @param AnCommandContext $context Context Parameter
    *  @return void
    */
    protected function _actionRead(AnCommandContext $context)
    {
        $this->getView()->set('setting', $this->_entity);
    }

    /**
    *   edit service
    *
    *  @param AnCommandContext $context Context Parameter
    *  @return void
    */
    protected function _actionEdit(AnCommandContext $context)
    {
        $data = $context->data;

        //don't overwrite these two
        unset($data->secret);
        unset($data->dbtype);

        $this->_entity->setData(KConfig::unbox($data));

        if ($this->_entity->save()) {
            $this->setMessage('COM-SETTINGS-PROMPT-SUCCESS', 'success');
        }
    }

    /**
    * method to fetch setting entity
    *
    *  @param AnCommandContext $context Context Parameter
    *
    *  @return ComSettingsDomainEntitySetting object
    */
    public function fetchEntity(AnCommandContext $context)
    {
        if (!$this->_entity) {
            $this->_entity = $this->getService('com:settings.domain.entity.setting')->load();
        }

        return $this->_entity;
    }
}
