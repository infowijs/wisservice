<?php

/**
 * Copyright 2019 Infowijs.
 * Created by Thomas Schoffelen.
 */

namespace Somtoday;

/**
 * Simple SOAP interface for Somtoday WISService.
 *
 * @method getLeerling(array $args)
 * @method getMedewerkerMetPasfoto(array $args)
 * @method getVerzorger(array $args)
 * @method saveVerzorger(array $args)
 * @method getExamenOpdracht(array $args)
 * @method getCijferOverzicht(array $args)
 * @method getPreCacheVerzorgers(array $args)
 * @method getDocentVestigingen(array $args)
 * @method getStamgroepenlijst(array $args)
 * @method getLeerlingPlaatsing(array $args)
 * @method getVerzorgerLeerlingen(array $args)
 * @method getOpleidingLestijden(array $args)
 * @method getMedewerkers(array $args)
 * @method getLeerlingAbsentieConstateringen(array $args)
 * @method getVestigingen(array $args)
 * @method schrijfInVoorKeuzeWerkTijd(array $args)
 * @method getExamenDossier(array $args)
 * @method getOpleidingVakken(array $args)
 * @method getLeerlingRelaties(array $args)
 * @method getLeerlingAbsentieMeldingen(array $args)
 * @method getMedewerkerRooster(array $args)
 * @method saveMedewerker(array $args)
 * @method getLesgroepenlijst(array $args)
 * @method getVestigingDocenten(array $args)
 * @method getKlasLeerlingenBijStamgroep(array $args)
 * @method getLeerlingRooster(array $args)
 * @method getBevoegdGezag(array $args)
 * @method getVestigingLestijden(array $args)
 * @method getMedewerkerByUsername(array $args)
 * @method getLeerlingVakkenpakket(array $args)
 * @method getBetrokkenDocentenBijLeerling(array $args)
 * @method getLeerlingMentoren(array $args)
 * @method getMedewerkersJarigInMaand(array $args)
 * @method saveToestemmingLeerling(array $args)
 * @method getKlasLeerlingenBijLesgroep(array $args)
 * @method getOpleidingen(array $args)
 * @method getInstelling(array $args)
 * @method getMedewerker(array $args)
 * @method schrijfUitVoorKeuzeWerkTijd(array $args)
 * @method saveLeerling(array $args)
 * @method getVestigingVakken(array $args)
 * @method getLeerlingMetPasfoto(array $args)
 * @method getLeerlingAbsentieTotalen(array $args)
 */
class WISServiceClient extends WSSoapClient
{

    /**
     * Basic SOM URL
     *
     * @var string
     */
    private static $url_pattern = 'https://{portal}-oop.somtoday.nl/';

    /**
     * WISService WSDL path
     *
     * @var string
     */
    private static $wsdl_path = 'services/WISService?wsdl';

    /**
     * Username
     *
     * @var string
     */
    private $username = '';

    /**
     * Password
     *
     * @var string
     */
    private $password = '';

    /**
     * Portal name
     *
     * @var string
     */
    private $portalname = '';

    /**
     * BRIN number
     *
     * @var string
     */
    private $brin = '';

    /**
     * Constructor.
     *
     * @param array $options
     * @throws WISServiceException
     */
    public function __construct($options)
    {
        if(empty($options) || !is_array($options)) {
            throw new WISServiceInvalidOptionsException('Options parameter should be array.');
        }

        if(count(array_diff(array_keys($options), ['portal', 'username', 'password', 'brin']))) {
            throw new WISServiceInvalidOptionsException('Options should be an array with exactly four ' .
                'keys: portal, brin, username, password.');
        }

        $this->username = $options['username'];
        $this->password = $options['password'];
        $this->portalname = $options['portal'];
        $this->brin = $options['brin'];

        try {
            parent::__construct(self::getURL($this->portalname), [
                'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_BOTH,
                'user_agent' => 'Mozilla/5.0 (compatible; ScholicaSOMtodayModule; nl; Scholica 1.0;  http://www.scholica.com/'
            ]);
        } catch(\SoapFault $exception) {
            throw new WISServiceException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception->getPrevious());
        }
    }

    /**
     * Get the URL of a Somtoday installation.
     *
     * @param string $portalName
     * @return string $url
     */
    public static function getURL($portalName)
    {
        return str_replace('{portal}', $portalName, self::$url_pattern) . self::$wsdl_path;
    }

    /**
     * Call SOAP method.
     *
     * @param string $method
     * @param array $arguments
     * @return mixed
     * @throws WISServiceException
     */
    public function __call($method, $arguments)
    {
        try {
            parent::__setUsernameToken($this->username, $this->password, 'PasswordText');

            $header = new \SoapHeader('http://wis.koppelingen.iridium.topicus.nl/', 'Brinnummer', $this->brin);
            parent::__setCustomSoapHeaders([$header]);

            return parent::__call($method, $arguments);
        } catch(\SoapFault $exception) {
            throw new WISServiceException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception->getPrevious()
            );
        }
    }

}
