<?php

    namespace Core\Helpers;

    use Core\Helpers\Csrf;

    class RequestHandler
    {
        
        /**
        * Return GET request value
        *
        * @Param {string} $key Index
        *
        **/
        public static function Get($key) {
            
            return $_GET[$key] ?? false;
        }
        
        /**
        * Fetch request token
        *
        **/
        public static function GetToken() {
            
            return self::Post('csrf_token');
        }

        /**
        * Fetch request token
        *
        **/
        public static function GetRequestToken() {
            
            return self::Get('csrf_token');
        }
        
        /**
        * Return Post request value
        *
        * @Param {string} $key Index
        *
        **/
        public static function Post($key) {
            
            return $_POST[$key] ?? false;
        }
        
        /**
        * Load request as error
        *
        * @Param {string} $content Request content
        *
        **/
        public static function Error($content) {
            
            return array('status' => 'error', 'content' => $content);
        }
        
        /**
        * Get request method
        *
        **/
        public static function Method() {
            
            return strtoupper($_SERVER['REQUEST_METHOD']);
        }
        
        /**
        * Validate a request token
        *
        * @Param {string} $token
        *
        **/
        public static function IsValid($token) {
            
            return Csrf::Validate($token);
        }
        
        /**
        * Load request as success
        *
        * @Param {string} $content Request content
        *
        **/
        public static function Success($content) {
            
            return array('status' => 'success', 'content' => $content);
        }
        
        /**
        * Render request content {error/success}
        *
        * @Param {array} $response Response
        *
        **/
        public static function Render($response = null) {
            
            $response = $response ?? self::Error('Invalid request');
            
            header('Content-Type: application/json');
            echo json_encode($response);
        }   
    }