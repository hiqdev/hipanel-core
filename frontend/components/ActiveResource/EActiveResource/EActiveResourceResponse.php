<?php
namespace frontend\components\ActiveResource\EActiveResource;
/**
 * @author Johannes "Haensel" Bauer
 * @since 0.3
 */

/**
 * This class encapsulates a curl response and is used to extract response headers,
 * raw data, parsed data and throws response exceptions according to the http response codes returned by the service
 */
class EActiveResourceResponse
{
    private $_data;
    private $_info;
    private $_headerString;//the raw header
    private $_header;
    private $_error;
        
    /**
     * Constructor
     * @param string $rawData The raw data returned by the service (xml,json etc.)
     * @param array $info The curl response info array
     * @param string $headerString The header string returned by the service
     */
    public function __construct($data,$info,$headerString,$error)
    {
        $this->_data=$data;
        $this->_headerString=$headerString;
        $this->_info=$info;
        $this->_error=$error;
        $this->parseHeaders();
    }
    
    /**
     * Getter for returing the parsed data as array
     * @return array the parsed data
     */
    public function getData()
    {
            return $this->_data;
    }
    
    /**
     * Getter for returning the curl info of this response
     * @return array the curl info
     */
    public function getInfo()
    {
            return $this->_info;
    }
    
    /**
     * Getter for the header
     * @return array the header
     */
    public function getHeader()
    {
            return $this->_header;
    }
        
    /**
     * Internally used to create an array out of the header string returned by the service. Use getHeader() to get the result
     */
    protected function parseHeaders()
    {
        $retVal = array();
        $fields = explode("\r\n", preg_replace('/\x0D\x0A[\x09\x20]+/', ' ', $this->_headerString));
        foreach( $fields as $field ) {
            if( preg_match('/([^:]+): (.+)/m', $field, $match) ) {
                $match[1] = preg_replace_callback('/(?<=^|[\x09\x20\x2D])./', 
                    create_function ('$matches', 'return strtoupper($matches[0]);'), 
                    strtolower(trim($match[1]))
                );
                if( isset($retVal[$match[1]]) ) {
                    $retVal[$match[1]] = array($retVal[$match[1]], $match[2]);
                } else {
                    $retVal[$match[1]] = trim($match[2]);
                }
            }
        }
        $this->_header=$retVal;
    }
    
    /**
     * Internally used to check the response codes. Throws errors if errors occured
     * @return boolean returns false if no errors occurred, throws exception if errors occured
     */
    public function hasErrors()
    {
        if(!$this->_error===false)
            return true;
        else {
            return false;
        }
    }
    
    public function throwError()
    {
        $responseCode=$this->_error['status'];
        $errorMessage=$this->_error['message'];
        
        switch($responseCode)
        {
            case 0:
                throw new EActiveResourceRequestException('No response. Service may be down');

            case 400:
                throw new EActiveResourceRequestException_BadRequest($errorMessage, $responseCode);
            case 401:
                throw new EActiveResourceRequestException_UnauthorizedAccess($errorMessage, $responseCode);


            case 403:
                throw new EActiveResourceRequestException_Forbidden($errorMessage, $responseCode);
            case 404:
                throw new EActiveResourceRequestException_NotFound($errorMessage, $responseCode);
            case 405:
                throw new EActiveResourceRequestException_MethodNotAllowed($errorMessage, $responseCode);
            case 406:
                throw new EActiveResourceRequestException_NotAcceptable($errorMessage, $responseCode);


            case 407:
                throw new EActiveResourceRequestException_ProxyAuthentication($errorMessage, $responseCode);
            case 408:
                throw new EActiveResourceRequestException_Timeout($errorMessage, $responseCode);


            default:
                throw new EActiveResourceRequestException($errorMessage, $responseCode);
        }
    }
    
    
}
?>
