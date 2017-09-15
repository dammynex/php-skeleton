<?php

namespace Core\Modules;

/**
* Curl helper
*/
class Curl
{

    /**
    * data encoding data type
    * application/json
    */
    protected const DATA_ENCODE_JSON = 100;

    /**
    * data encoding data type
    * form/www-url-encode
    */
    protected const DATA_ENCODE_FORM = 101;

    /**
    * curl resource
    */
    protected $_ch;

    /**
    * curl headers
    *
    */
    protected $_headers = [];

    /**
    * curl options
    *
    */
    protected $_opts = [];

    /**
    * curl response
    *
    */
    protected $response;

    /**
    * Initiate curl request
    *
    */
    public function __constructor($url)
    {
        $this->_ch = curl_init();

        $this->setOpts([
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER, true
        ]);
    }

    /**
    * Add a valid request header
    *
    * @param {string} $name HTTP header name
    * @param {any} $value HTTP header value
    */
    public function addHeader($name, $value)
    {
        curl_setopt($this->_ch, CURLOPT_HTTPHEADER, "{$name}: {$value}");
        return $this;
    }

    /**
    * Add multiple headers
    *
    * @param {array} $header HTTP headers
    */
    public function addHeaders(array $headers)
    {
        curl_setopt($this->_ch, CURLOPT_HTTPHEADER, $headers);
        return $this;
    }

    /**
    * send get request
    *
    */
    public function get()
    {
        $this->response = curl_exec($this->_ch);
        curl_close($this->_ch);
        return $this;
    }

    /**
    * send post request
    *
    * @param {array} $data
    * @param {int} $encoding
    */
    public function post(array $data, int $encoding  = self::DATA_ENCODE_FORM)
    {
        
        if($encoding === self::DATA_ENCODE_JSON) $data = json_encode($data);

        $this->setOpts([
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data
        ]);

        $this->response = curl_exec($this->_ch);
        curl_close($this->_ch);
    }

    /**
    * return response
    */
    public function getResponse()
    {
        return $this->response;
    }

    /**
    * set auth bearer
    *
    * @param {string} $bearer Auth bearer
    */
    public function setAuthBearer($token)
    {
        $this->addHeader('Authorization: Bearer', $token);
        return $this;
    }

    /**
    * set basic authentication
    *
    * @param {string} $username Authentication Username
    * @param {string} $password Authentication Password
    */
    public function setBasicAuth($username, $password)
    {
        $this->setOpt(CURLOPT_USERPWD, $value);
        return $this;
    }

    /**
    * set options
    *
    * @param {int} curlopt
    * @param {any} curlopt value
    */ 
    public function setOpt($name, $value)
    {
        curl_setopt($this->_ch, $name, $value);
        return $this;
    }

    /**
    * set multiple options
    *
    * @param {array} $option Curl optios
    */
    public function setOpts(array $options)
    {
        curl_setopt_array($this->_ch, $options);
        return $this;
    }
}