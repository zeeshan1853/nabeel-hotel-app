<?php

namespace api\helpers;

use BadMethodCallException;
use Exception;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;
use OutOfBoundsException;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Jwt
 * 
 * @link https://stackoverflow.com/a/29946630/5798881
 * 
 * @author zeeshan
 */
class Jwt {

    /**
     *
     */
    const CLAIM_ISSUER = 'Zeeshan';

    /**
     *
     */
    const CLAIM_VALID_FROM = 2; # In seconds
    /**
     *
     */
    const CLAIM_EXPIRATION = 259200; # In seconds
    /**
     *
     */
    const CLAIM_JWT_ID = '51E35164B1851A49KVK';

    /**
     *
     */
    const SIGNATURE_KEY = '3pMgGi32LQ9DA9KsPolLKAvMAluJ67LoKVK';

    /**
     *
     */
    const PRIVATE_CLAIM_ID = 'token';

    /**
     * @var
     */
    private $signature;

    /**
     * @var
     */
    private static $instance;

    /**
     * Jwt constructor.
     */
    private function __construct() {
        $this->signature = new Sha256();
    }

    /**
     * @return Jwt
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param $id
     * @return string
     * @throws BadMethodCallException
     */
    public function token($id) {
        
        $token = (new Builder())
                ->setIssuer(self::CLAIM_ISSUER)
                ->setAudience(self::CLAIM_ISSUER)
                ->setId(self::CLAIM_JWT_ID, true)
                ->setIssuedAt(time())
                ->setNotBefore(time() + self::CLAIM_VALID_FROM)
                ->setExpiration(time() + self::CLAIM_EXPIRATION)
                ->set(self::PRIVATE_CLAIM_ID, $id)
                ->sign($this->signature, self::SIGNATURE_KEY);

        return (string) $token->getToken();
    }

    /**
     * @param $token
     * @return bool
     * @throws BadMethodCallException
     */
    public function isValid($token) {
        if (!is_string($token)) {
            $token = (string) $token;
        }
        $validator = new ValidationData();

        $validator->setIssuer(self::CLAIM_ISSUER);
        $validator->setAudience(self::CLAIM_ISSUER);
        $validator->setId(self::CLAIM_JWT_ID);

        try {
            $token = (new Parser())->parse((string) $token);
            return $token->validate($validator) && $token->verify($this->signature, self::SIGNATURE_KEY);
        } catch (Exception $runtimeException) {
            return false;
        }
    }

    /**
     * @param $token
     * @return string|null
     * @throws BadMethodCallException
     * @throws OutOfBoundsException
     */
    public function getPrivateClaim($token) {
        if (!$this->isValid($token)) {
            return null;
        }
        try {
            $token = (new Parser())->parse((string) $token);

            return $token->getClaim(self::PRIVATE_CLAIM_ID);
        } catch (Exception $exception) {
            return null;
        }
    }

}
