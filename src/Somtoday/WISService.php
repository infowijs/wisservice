<?php

/**
 * Copyright 2019 Infowijs.
 * Created by Thomas Schoffelen.
 */

namespace Somtoday;

/**
 * Class WISService
 */
class WISService
{

    /**
     * Create WISService client instance.
     *
     * @param array $options
     * @return WISServiceClient
     * @throws WISServiceException
     */
    public static function create($options)
    {
        return new WISServiceClient($options);
    }
}
