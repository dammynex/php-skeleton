<?php

    namespace Core\Helpers;

    use Core\Helpers\Session;
    use Core\Misc\Functions;

    class Csrf
    {
        
        /**
        * Session name for csrf
        *
        */
        const SESSION_NAME = '__csrf_token';
        
        /**
        * Generate new token
        *
        */
        public static function GenerateToken() {
            
            $token = base64_encode(openssl_random_pseudo_bytes(32));
            
            $token_time = Functions\getnow();
            $token_data = ['token' =>$token, 'token_time' => $token_time];
            
            Session::Set(self::SESSION_NAME, $token_data);
            
            return $token_data;
            
        }
        
        /**
        * Create new token
        * Or send existing token
        *
        */
        public static function Token() {
            
            if(Session::Exists(self::SESSION_NAME)) {
                
                return Session::Get(self::SESSION_NAME)['token'];
            }
            
            return self::GenerateToken()['token'];
        }
        
        /**
        * Validate CSRF Token
        *
        **/
        public static function Validate($token) {
            
            return ($token == self::Token());
        }
    }