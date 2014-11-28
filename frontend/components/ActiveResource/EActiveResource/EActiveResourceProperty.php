<?php
namespace frontend\components\ActiveResource\EActiveResource;
/**
 * EActiveResourcePropertySchema class file.
 *
 * @author Johannes "Haensel" Bauer <thehaensel@gmail.com>
 */

/**
 * EActiveResourcePropertySchema class describes the property meta data of an resource.
 *
 * @author Johannes "Haensel" Bauer <thehaensel@gmail.com>
 * @since 0.4
 */
class EActiveResourceProperty
{
        /**
         * @var string name of this property (without quotes).
         */
        public $name;

        /**
         * @var boolean whether this column can be null.
         */
        public $allowNull;

        /**
         * @var string the PHP type of this resource property.
         */
        public $type;
        /**
         * @var mixed default value of this resource property
         */
        public $defaultValue;
        /**
         * @var integer size of the resource property.
         */
        public $size;
        /**
         * @var integer precision of the resource property data, if it is numeric.
         */
        public $precision;
        /**
         * @var integer scale of the resource property data, if it is numeric.
         */
        public $scale;

        public function __construct($config)
        {
            foreach($config as $key=>$value)
            {
                if(property_exists(get_class($this), $key))
                        $this->$key=$value;
                else
                    throw new EActiveResourceException('EActiveResourceProperty does not have a property named '.$key);
            }
        }
        
        /**
         * Extracts the default value for the column.
         * The value is typecasted to correct PHP type.
         * @param mixed $defaultValue the default value obtained from metadata
         */
        protected function init()
        {
                $this->defaultValue=$this->typecast($this->defaultValue);
        }

        /**
         * Converts the input value to the type that this property is of.
         * @param mixed $value input value
         * @return mixed converted value
         */
        public function typecast($value)
        {
                if(gettype($value)===$this->type || $value===null)
                        return $value;
                if($value==='')
                        return $this->type==='string' ? '' : null;
                switch($this->type)
                {
                        case 'string': return (string)$value;
                        case 'integer': return (integer)$value;
                        case 'boolean': return (boolean)$value;
                        case 'double': return (double)$value;
                        default: return $value;
                }
        }
}
?>
