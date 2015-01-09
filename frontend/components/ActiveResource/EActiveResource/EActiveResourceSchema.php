<?php
namespace frontend\components\ActiveResource\EActiveResource;
/**
 * EActiveResourceSchema class file.
 *
 * @author Johannes "Haensel" Bauer <thehaensel@gmail.com>
 */

/**
 * EActiveResourceSchema is the base class for representing the metadata of an active resource.
 * 
 * EActiveResourceSchema provides the following information about a resource:
 * <ul>
 * <li>{@link name}</li>
 * <li>{@link properties}</li>
 * </ul>
 *
 * @author Johannes "Haensel" Bauer <thehaensel@gmail.com>
 * @since 0.5
 */
class EActiveResourceSchema
{
        /**
         * @var string name of this resource.
         */
        public $name;

        public $properties=array();
                
        public $site;
        public $resource;
        public $container;
        public $multiContainer;
        public $embedded;
        public $idProperty;
        public $contentType;
        public $acceptType;
        public $allowNullValues;

        public $auth;
        public $ssl;
        
        
        public function __construct($config,$properties)
        {
            foreach($config as $key=>$value)
            {
                if(property_exists(get_class($this), $key))
                        $this->$key=$value;
                else
                    throw new EActiveResourceException('EActiveResourceSchema does not have a property named '.$key);
            }
            
            foreach($properties as $name=>$config)
            {
                $this->setProperty($name, $config);
            }
        }
        /**
         * Gets the named property metadata.
         * This is a convenient method for retrieving a named property even if it does not exist.
         * @param string $name property name
         * @return EActiveResourceProperty metadata of the named property. Null if the named property does not exist.
         */
        public function getProperty($name)
        {
                return isset($this->properties[$name]) ? $this->properties[$name] : null;
        }
        
        public function setProperty($name,$config)
        {
                $this->properties[$name]=new EActiveResourceProperty($config);;
        }

        /**
         * @return array list of property names
         */
        public function getPropertyNames()
        {
                return array_keys($this->properties);
        }
}
?>