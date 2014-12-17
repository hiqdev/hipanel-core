<?php
namespace frontend\components\ActiveResource\EActiveResource;
/**
 * @author Johannes "Haensel" Bauer
 * @since version 0.1
 */
/**
 * This is the ActiveResource version of the CActiveMetaData class. It is used by ActiveResources to define
 * vital parameters for a RESTful communication between Yii and the service.
 */
class EActiveResourceMetaData
{

    public $properties;     //The properties of the resource according to the schema configuration
    public $relations=array();
    
    public $attributeDefaults=array();
    
    public $schema;

    private $_model;

    public function __construct($model)
    {
            $this->_model=$model;

            if(($resourceConfig=$model->rest())===null)
                    throw new EActiveResourceException(Yii::t('ext.EActiveResource','The resource "{resource}" configuration could not be found in the activeresource configuration.',array('{resource}'=>get_class($model))));
           
            $this->schema=new EActiveResourceSchema($resourceConfig,$model->properties());
                                                
            $this->properties=$this->schema->properties;

            foreach($this->properties as $name=>$property)
            {
                    if($property->defaultValue!==null)
                            $this->attributeDefaults[$name]=$property->defaultValue;
            }
    }
    
    public function getSchema()
    {
        return $this->schema;
    }
}

?>
