<?php
/**
 * @author Johannes "Haensel" Bauer (thehaensel@gmail.com)
 * @since version 0.4
 */
namespace frontend\components\ActiveResource\EActiveResource;
use yii\base\Object;
/**
 * The EActiveResourceConnection component is used to define basic parameters used by all 
 * requests using this connection such as content type,
 * accept type, authorization, ssl, baseUri (site) and query caching options
 */
class EActiveResourceConnection extends Object
{
    public $contentType;
    public $acceptType;
    public $site;
    public $auth;
    public $ssl;
    public $apiKey;
    public $allowNullValues=true;
    
    public $queryCachingDuration=0;
    public $queryCachingDependency;
    public $queryCachingCount=0;
    public $queryCacheID='cache';
    
    private $_ch; //the curl handle used for reusable connections
    
    /**
     * Initialize the extension
     * check to see if CURL is enabled and the format used is a valid one
     */
    public function init()
    {
        if(!function_exists('curl_init') )
            throw new EActiveResourceRequestException_Curl(Yii::t('EActiveResource', 'You must have PHP curl enabled in order to use this extension.') );

        $this->connect();
    }
    
    public function __sleep()
    {
        $this->disconnect();
        return array_keys(get_object_vars($this));
    }
    
    protected function connect()
    {
        if(!isset($this->_ch))
            $this->_ch=curl_init();
    }
    
    protected function disconnect()
    {
        if(!isset($this->_ch))
            $this->_ch->close();
    }
    
    /**
     * Used for caching responses
     * @param integer $duration The caching duration in seconds
     * @param CCacheDependency $dependency The dependency used for caching (Note: CDbCacheDependency won't work with for obvious reasons)
     * @param integer $queryCount The number of queries that will be cached. Defaults to 1 meaning only the first response will be cached
     * @return EActiveResourceConnection
     */
    public function cache($duration, $dependency=null, $queryCount=1)
    {
        $this->queryCachingDuration=$duration;
        $this->queryCachingDependency=$dependency;
        $this->queryCachingCount=$queryCount;
        return $this;
    }
    
    /**
     * Sends a query to the service. By default all GET requests
     * are treated as queries which allows caching of responses.
     * @param EActiveResourceRequest $request The request object
     * @return EActiveResourceResponse The response object
     */
    public function query(EActiveResourceRequest $request)
    {
        //set connection component specific options
        $request->setCurlHandle($this->_ch);
        $request->setContentType($this->contentType);
        $request->setAcceptType($this->acceptType);
        
        //AUTH STUFF
        if(isset($this->auth))
        {
            $request->setHttpLogin($this->auth['username'], $this->auth['password'], $this->auth['type']);
        }

        //API KEY STUFF
        if(isset($this->apiKey))
        {
            $request->setApiKey($this->apiKey['value'], $this->apiKey['name']);
        }

        //SSL STUFF
        if(isset($this->ssl))
        {
            $request->setSSL($this->ssl['verifyPeer'], $this->ssl['verifyHost'], $this->ssl['pathToCert']);
        }
        
        ///LOOK FOR CACHED RESPONSES FIRST
        if($this->queryCachingCount>0
                        && $this->queryCachingDuration>0
                        && $this->queryCacheID!==false
                        && ($cache=Yii::app()->getComponent($this->queryCacheID))!==null)
        {
            $this->queryCachingCount--;
            $cacheKey='yii:eactiveresourcerequest:'.$request->getUri().':'.$request->getMethod().':'.$this->recursiveImplode('-',$request->getHeader());
            $cacheKey.=':'.$this->recursiveImplode('#',$request->getData());
            if(($result=$cache->get($cacheKey))!==false)
            {
                    Yii::trace('Response found in cache','ext.EActiveResource.EActiveResourceConnection');
                    return $result;
            }
        }

        $response=$request->run();

        //CACHE RESULT IF CACHE IS SET
        if(isset($cache,$cacheKey))
            $cache->set($cacheKey, $response, $this->queryCachingDuration, $this->queryCachingDependency);
        
        if($response->hasErrors())
            $response->throwError();
        
        return $response;
    }
    
    /**
     * Manipulate data with a request. execute requests like DELETE, PUT, POST requests can't be cached.
     * @param EActiveResourceRequest $request The request object
     * @return EActiveResourceResponse The response object
     */
    public function execute(EActiveResourceRequest $request)
    {
        //set connection component specific options
        $request->setCurlHandle($this->_ch);
        $request->setContentType($this->contentType);
        $request->setAcceptType($this->acceptType);
        
        //AUTH STUFF
        if(isset($this->auth))
        {
            $request->setHttpLogin($this->auth['username'], $this->auth['password'], $this->auth['type']);
        }

        //API KEY STUFF
        if(isset($this->apiKey))
        {
            $request->setApiKey($this->apiKey['value'], $this->apiKey['name']);
        }

        //SSL STUFF
        if(isset($this->ssl))
        {
            $request->setSSL($this->ssl['verifyPeer'], $this->ssl['verifyHost'], $this->ssl['pathToCert']);
        }
        
        $response=$request->run();
         
        if($response->hasErrors())
            $response->throwError();
        
        return $response;
    }
        
    /**
     * Implodes a multidimensional array to a simple string (used to create unique cache keys)
     * @return string The array formatted as a string
     */
    protected function recursiveImplode($glue, $pieces)
    {
        $return = "";
        
        if($pieces===null)
            return;

        if(!is_array($glue))
            $glue = array($glue);
        
        $currentGlue = array_shift($glue);

        if(!count($glue))
            $glue = array($currentGlue);
        
        if(!is_array($pieces))
            return (string) $pieces;
        
        foreach($pieces as $sub)
            $return .= $this->recursiveImplode($glue, $sub) . $currentGlue;

        if(count($pieces))
            $return=substr($return,0,strlen($return)-strlen($currentGlue));

        return $return;
    }
        
}
?>
