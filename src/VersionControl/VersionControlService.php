<?php

declare(strict_types=1);

namespace src\VersionControl;

class VersionControlService
{

    /**
    * Position in argv array
    */
    private $index = 1;
    const VERSION_CONTROL_NAME_GITHUB = 'github';
    const AVAILABLE_VERSION_CONTROL_SERVICES = [
            self::VERSION_CONTROL_NAME_GITHUB,
        ];

    /**
     * @var string
     */
    private $versionControlName = VERSION_CONTROL_NAME_GITHUB;
    /**
     * @var bool $isServiceOption
     */
    private $isServiceOption = false;

    /**
     * @var $arguments
     */
    private $arguments;

    /**
     * @var
     */
    private $argumentsCount;
    private $service;
}