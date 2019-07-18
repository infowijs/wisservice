<?php

/**
 * Copyright 2019 Infowijs.
 * Created by Thomas Schoffelen.
 */

namespace Somtoday;

use SoapHeader;

/**
 * This class can add WSSecurity authentication support to SOAP clients
 * implemented with the PHP 5 SOAP extension.
 *
 * It extends the PHP 5 SOAP client support to add the necessary XML tags to
 * the SOAP client requests in order to authenticate on behalf of a given
 * user with a given password.
 */
class WSSoapClient extends \SoapClient
{

    /**
     * Signing nonce
     *
     * @var string
     */
    protected $nonce;

    /**
     * Signing timestamp
     *
     * @var string
     */
    protected $timestamp;

    /**
     * Security namespace
     *
     * @var string
     */
    private $OASIS = 'http://docs.oasis-open.org/wss/2004/01';

    /**
     * WS-Security Username
     *
     * @var string
     */
    private $username;

    /**
     * WS-Security Password
     *
     * @var string
     */
    private $password;

    /**
     * WS-Security PasswordType
     *
     * @var string
     */
    private $passwordType;

    /**
     * Custom SOAP headers
     *
     * @var array
     */
    private $customHeaders = [];

    /**
     * Set WS-Security credentials.
     *
     * @param string $username
     * @param string $password
     * @param string $passwordType
     */
    public function __setUsernameToken($username, $password, $passwordType)
    {
        $this->username = $username;
        $this->password = $password;
        $this->passwordType = $passwordType;
    }

    /**
     * Set custom SOAP headers by WISServiceClient.
     *
     * @param $cs
     */
    public function __setCustomSoapHeaders($cs)
    {
        $this->customHeaders = $cs;
    }

    /**
     * Overwrites the original method adding the security header.
     * As you can see, if you want to add more headers, the method needs to be modified.
     *
     * @param string $function_name
     * @param mixed $arguments
     * @return mixed
     */
    public function __call($function_name, $arguments)
    {
        $security_header = $this->generateWSSecurityHeader();
        $headers = $security_header
            ? array_merge($this->customHeaders, [$security_header])
            : $this->customHeaders;

        $this->__setSoapHeaders($headers);

        $result = parent::__call($function_name, $arguments);
        if(is_object($result) && isset($result->return)) {
            return $result->return;
        }

        return $result;
    }

    /**
     * Generate password digest.
     *
     * Using the password directly may work also, but it's not secure to transmit it without encryption.
     * And anyway, at least with axis+wss4j, the nonce and timestamp are mandatory anyway.
     *
     * @return string Base64 encoded password digest
     */
    private function generatePasswordDigest()
    {
        $this->nonce = mt_rand();
        $this->timestamp = gmdate('Y-m-d\TH:i:s\Z');

        $packed_nonce = pack('H*', $this->nonce);
        $packed_timestamp = pack('a*', $this->timestamp);
        $packed_password = pack('a*', $this->password);

        $hash = sha1($packed_nonce . $packed_timestamp . $packed_password);
        $packed_hash = pack('H*', $hash);

        return base64_encode($packed_hash);
    }

    /**
     * Generates WS-Security headers
     *
     * @return SoapHeader
     */
    private function generateWSSecurityHeader()
    {
        if($this->passwordType === 'PasswordDigest') {
            $password = $this->generatePasswordDigest();
            $nonce = sha1($this->nonce);
        } elseif($this->passwordType === 'PasswordText') {
            $password = $this->password;
            $nonce = sha1(mt_rand());
        } else {
            return null;
        }

        $xml = '<wsse:Security SOAP-ENV:mustUnderstand="1" xmlns:wsse="' . $this->OASIS .
            '/oasis-200401-wss-wssecurity-secext-1.0.xsd"><wsse:UsernameToken>' .
            '<wsse:Username>' . $this->username . '</wsse:Username>' .
            '<wsse:Password Type="' . $this->OASIS . '/oasis-200401-wss-username-token-profile-1.0#' .
            $this->passwordType . '">' . $password . '</wsse:Password>' .
            '<wsse:Nonce EncodingType="' . $this->OASIS .
            '/oasis-200401-wss-soap-message-security-1.0#Base64Binary">' . $nonce . '</wsse:Nonce>';

        if($this->passwordType === 'PasswordDigest') {
            $xml .= '<wsu:Created xmlns:wsu="' . $this->OASIS .
                '/oasis-200401-wss-wssecurity-utility-1.0.xsd">' .
                $this->timestamp . '</wsu:Created>';
        }

        $xml .= '</wsse:UsernameToken></wsse:Security>';

        return new SoapHeader($this->OASIS . '/oasis-200401-wss-wssecurity-secext-1.0.xsd',
            'Security', new \SoapVar($xml, XSD_ANYXML), true);
    }
}
