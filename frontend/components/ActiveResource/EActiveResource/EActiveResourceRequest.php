<?php
namespace frontend\components\ActiveResource\EActiveResource;
/**
 * @author Johannes "Haensel" Bauer, heavily influenced by Igor Ivanović who created the cUrl extension for Yii
 * @since 0.1
 */

/**
 * This class is used to send and receive cURL requests to the service used by an ActiveResource.
 * It is heavily influenced by Igor Ivanović's cURL extension for Yii although a bit modified to allow sending PUT and DELETE
 * requests and different content/accept types etc.
 * @link: http://www.yiiframework.com/extension/curl
 */
class EActiveResourceRequest
{
        protected $ch;
        
        private $_contentType;
        private $_acceptType;
        private $_timeout=30;
        
        private $_uri;
        private $_method;
        private $_data;
        private $_headers=array(); //an array of header fields that will get merged with the standard header
        private $_header;
        private $_customHeader;       
        
        private $_formattedData;
                
        private $_headerString="";
        
        private $_info;
        private $_response;
        
        protected $supportedFormats = array(
                'xml'               => 'application/xml',
                'json'              => 'application/json',
        );

        protected $autoDetectFormats = array(
                'application/xml' 	=> 'xml',
                'text/xml' 		    => 'xml',
                'application/json' 	=> 'json',
                'text/json'         => 'json',
        );

        const APPLICATION_JSON  ='application/json';
        const APPLICATION_XML   ='application/xml';
        const APPLICATION_FORM_URL_ENCODED  ='application/x-www-form-urlencoded';

        const METHOD_GET    = 'GET';
        const METHOD_POST   = 'POST';
        const METHOD_PUT    = 'PUT';
        const METHOD_DELETE = 'DELETE';
    
        public function setCurlHandle($ch)
        {
            $this->ch=$ch;
        }
                
         /**
        * Setter
        * @set the option
        */
        protected function setOption($key,$value)
        {
            curl_setopt($this->ch,$key, $value);
        }

	 /*
	* Set Url Cookie
	*/
        public function setCookies($values)
        {
            if (!is_array($values))
                throw new EActiveResourceRequestException_Curl(Yii::t('EActiveResource', 'Options must be an array'));
            else
                $params = $this->cleanPost($values);

            $this->setOption(CURLOPT_COOKIE, $params);
        }

	/*
	@LOGIN REQUEST
	sets login option
	If is not setted , return false
	*/
        public function setHttpLogin($username = '', $password = '',$type='any')
        {
            $this->setOption(CURLOPT_HTTPAUTH, constant('CURLAUTH_' . strtoupper($type)));
            $this->setOption(CURLOPT_USERPWD, $username.':'.$password);
        }
        /*
	@PROXY SETINGS
	sets proxy settings withouth username

	*/

        public function setProxy($url,$port = 80)
        {
            $this->setOption(CURLOPT_HTTPPROXYTUNNEL, TRUE);
            $this->setOption(CURLOPT_PROXY, $uri.':'.$port);
	}
        
        public function setApiKey($key, $name = 'X-API-KEY')
        {
            $this->setHeaderField($name, $key);
        }

	/*
	@PROXY LOGIN SETINGS
	sets proxy login settings calls onley if is proxy setted
	*/
	public function setProxyLogin($username = '', $password = '')
        {
            $this->setOption(CURLOPT_PROXYUSERPWD, $username.':'.$password);
        }

        /*
	@DEFAULTS
	*/
        protected function setDefaults()
        {
            $this->setOption(CURLOPT_TIMEOUT,$this->getTimeOut());
            $this->setOption(CURLOPT_HEADER,FALSE);
            $this->setOption(CURLOPT_RETURNTRANSFER,TRUE);
	    $this->setOption(CURLOPT_FOLLOWLOCATION,TRUE);
            $this->setOption(CURLOPT_FAILONERROR,FALSE);
        }
        
        /**
         * The callback function for curlopt_HEADERFUNCTION adding each headerline to $this->_headerString
         * @param curl handle $ch the curl handle needed for the callback
         * @param string $header a single header line
         * @return int strlen of the line
         */
        protected function addHeaderLine($ch,$header)
        {
            $this->_headerString.=$header;
            return strlen($header);
        }
        
        /**
         * Set the timeout of this request in seconds
         * @param int $timeout  The timeout in seconds
         */
        public function setTimeOut($timeout)
        {
            $this->_timeout=$timeout;
        }
        
        /**
         * Getter for the currently set timeout for this request
         * @return int timeout in seconds 
         */
        public function getTimeOut()
        {
            return $this->_timeout;
        }
        
        /**
         * Getter for the currently set header used by curl
         * @return array The header to be sent to the service
         */
        public function getHeader()
        {
            if(isset($this->_header))
                    return $this->_header;
            
            $customHeader=$this->getCustomHeader();
            if(!empty($customHeader))
                return $customHeader;
            else
                return CMap::mergeArray($this->getStandardHeader(),$this->_headers);
        }
        
        /**
         * Used to set a single header field like "accept-type" etc.
         * @param string $headerField The name of the field
         * @param string $content The content of the field
         */
        public function setHeaderField($headerField, $content = NULL)
	{
		$this->_headers[] = $content ? $headerField . ': ' . $content : $headerField;
	}
        
        /**
         * Getter for the "standard header". This method simply checks if there is data to be sent by this request and if so
         * sets the content type, content length and the accept type to create a basic header
         * @return array the standard header 
         */
        public function getStandardHeader()
        {
            
            //set standard headers
            if(!is_null($this->getFormattedData()))
            {
                $header=array(
                    'Content-Length: '  .strlen($this->getFormattedData()),
                    'Content-Type: '    .$this->getContentType(),
                    'Accept: '          .$this->getAcceptType(),
                );
            }
            else {
                $header=array(
                    'Accept: '          .$this->getAcceptType(),
                );
            }
            
            return $header;
        }

        /**
         * Sets the uri for this request
         * @param string $uri The uri
         */
        public function setUri($uri)
        {
            if(!preg_match('!^\w+://! i', $uri))
            {
                $url = 'http://'.$uri;
            }
            $this->_uri = $uri;
        }
        
        /**
         * Get the current uri for this request
         * @return string the uri 
         */
        public function getUri()
        {
            if(isset($this->_uri))
                    return $this->_uri;
            else
                return null;
        }
        
        public function setSSL($verifyPeer=true,$verifyHost=2,$pathToCert=null)
	{
            if ($verifyPeer)
            {
                $this->setOption(CURLOPT_SSL_VERIFYPEER, true);
                $this->setOption(CURLOPT_SSL_VERIFYHOST, $verifyHost);
                $this->setOption(CURLOPT_CAINFO, $pathToCert);
            }
            else
            {
                $this->setOption(CURLOPT_SSL_VERIFYPEER, false);
            }
	}
        
        /**
         * Getter for the data set for this request
         * @return array the data set for this request (a PHP array)
         */
        public function getData()
        {
            if(isset($this->_data))
                    return $this->_data;
            else
                return null;
        }
        
        /**
         * Sets the data for this request
         * @param array $data The data to be sent as PHP array
         */
        public function setData($data)
        {
            $this->_data=$data;
        }
        
        /**
         * Parsed the data according to the content type to build a valid request (JSON or XML)
         * @return string the JSON or XML encoded string, null if no data is set 
         */
        public function getFormattedData()
        {
            if(isset($this->_formattedData))
                    return $this->_formattedData;
            
            if(!is_null($this->getData()))
            {
                switch($this->getContentType())
                {
                    case self::APPLICATION_JSON:
                        $formattedData=EActiveResourceParser::arrayToJSON($this->getData());
                        break;
                    case self::APPLICATION_XML:
                        $formattedData=EActiveResourceParser::arrayToXML($this->getData());
                        break;
                    default:
                        throw new CException('Content Type '.$this->getContentType().' not implemented!');
                }
                
                return $this->_formattedData=$formattedData;
            }
            
            return null;
        }
        
        protected function getFormattedResponse($response)
        {
            if (array_key_exists($this->getAcceptType(), $this->autoDetectFormats))
            {
                switch($this->supportedFormats[$this->autoDetectFormats[$this->getAcceptType()]])
                {
                    case self::APPLICATION_JSON:
                        $response=EActiveResourceParser::JSONtoArray($response);
                        break;
                    case self::APPLICATION_XML:
                        $response=EActiveResourceParser::XMLToArray($response);
                        break;
                    default:
                        throw new CException('Accept Type '.$this->getAcceptType().' not implemented!');
                }
                
            }

            return $response;
        }
        
        /**
         * Get the currently used method for this request (GET,PUT,POST,DELETE)
         * @return string The method used
         */
        public function getMethod()
        {
            if(isset($this->_method))
                    return $this->_method;
            else
                return self::METHOD_GET;
        }
        
        /**
         * Set the method to be used by this request (GET,PUT,POST,DELETE)
         * @param string $method The method. Defaults to GET 
         */
        public function setMethod($method=self::METHOD_GET)
        {
            $this->_method=$method;
        }
        
        /**
         * Get the custom header set for this request
         * @return array the custom header array 
         */
        public function getCustomHeader()
        {
            if(isset($this->_customHeader))
                    return $this->_customHeader;
            else
                return array();
        }
        
        /**
         * Sets a custom header for this request. This will OVERRIDE the standard header,
         * so you'll have to set every field on your own.
         * @param array $customHeader the custom header array
         */
        public function setCustomHeader($customHeader)
        {
            $this->_customHeader=$customHeader;
        }
                
        /**
         * Sets the content type
         * @param string $contentType 
         */
        public function setContentType($contentType)
        {
            $this->_contentType=$contentType;
        }
        
        /**
         * Get the currently used content type
         * @return string the content type
         */
        public function getContentType()
        {
            if(isset($this->_contentType))
                    return $this->_contentType;
        }
        
        /**
         * Sets the accept type
         * @param string $acceptType 
         */
        public function setAcceptType($acceptType)
        {
            $this->_acceptType=$acceptType;
        }
        
        /**
         * Get the currently used accept type
         * @return string the accept type
         */
        public function getAcceptType()
        {
            if(isset($this->_acceptType))
                    return $this->_acceptType;
        }
        
	/**
         * Sends the request and returns the response object.
         * @return EActiveResourceResponse The response object 
         */
	public function run()
        {            
                //if($this->ch===null)
                    //$this->setCurlHandle(curl_init());

                if(is_null($this->getUri()))
                    throw new EActiveResourceRequestException_Curl(Yii::t('EActiveResource', 'No uri set') );
                                                                
                //Setting cURL to get, and then using CustomRequest fixes issues with calling Post then Get in the same session
                $this->setOption(CURLOPT_HTTPGET, true);
                $this->setOption(CURLOPT_URL,$this->getUri());
                $this->setOption(CURLOPT_CUSTOMREQUEST,$this->getMethod());
                $this->setOption(CURLOPT_HTTPHEADER,$this->getHeader());
                $this->setOption(CURLOPT_HEADERFUNCTION,array($this,'addHeaderLine')); //the headerfunction is used to build a string out of the returned header line by line
                $this->setOption(CURLINFO_HEADER_OUT,true);
                                
                if(!is_null($this->getFormattedData()))
                    $this->setOption(CURLOPT_POSTFIELDS, $this->getFormattedData());
                                
                $this->setDefaults();
                                   
                $this->_response=curl_exec($this->ch);
                $this->_info=curl_getinfo($this->ch);
                
                if($this->_response===false)
                    throw new EActiveResourceRequestException_Curl(curl_error($this->ch),  curl_errno($this->ch));
                else
                {
                    Yii::trace(
                        "### Sent request #### \n".
                        "--------HEADER-------- \n".
                        curl_getinfo($this->ch,CURLINFO_HEADER_OUT)."\n".
                        "---------DATA--------- \n".
                        (is_null($this->getFormattedData()) ? "none" : $this->getFormattedData()),'ext.EActiveResource.request');
                
                    Yii::trace(
                        "### Received response #### \n".
                        "-------CURLINFO------- \n".
                        "Uri: ".$this->_info['url']."\n".
                        "Total time: ".$this->_info['total_time']."\n".
                        "--------HEADER-------- \n".
                        $this->_headerString."\n".
                        "---------DATA--------- \n".
                        $this->_response,'ext.EActiveResource.response');
                }
                                
                $formattedResponse=$this->getFormattedResponse($this->_response);
                
                $response=new EActiveResourceResponse($formattedResponse,$this->_info,$this->_headerString,$this->checkErrors());
                                                
                return $response;
      }
      
    /**
     * Internally used to check the response codes. Throws errors if errors occured
     * @return boolean returns false if no errors occurred, throws exception if errors occured
     */
    public function checkErrors()
    {
        $responseInfo=$this->_info;
        $responseUri=$responseInfo['url'];
        $responseCode=$responseInfo['http_code'];

        if($responseCode && $responseCode<400)
            return false;
        else
        {
            if(YII_DEBUG)
                $errorMessage="Error $responseCode \n\n".$this->_response;
            else
                $errorMessage="Error $responseCode";

            return array('status'=>$responseCode,'message'=>$errorMessage);            
        }
    }
      
}
?>
