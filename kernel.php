<?php

declare(strict_types=1);

set_exception_handler('exceptionHandler');

require 'vendor/autoload.php';

spl_autoload_register(function($className) {
    $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
    include_once __DIR__ . '/src/' . $className . '.php';
});

use GuzzleHttp\Client;
use VersionControl\VersionControlAdapterFactory;


/**
 * Position in argv array, omitting file name at 0 index
 */
$index = 0;
$arguments = $argv;
$argumentsCount = $argc;

/**
 * Removing file name from arguments array
 */
array_shift($arguments);

const OPTION_NAME_SERVICE = 'service';
const VERSION_CONTROL_NAME_GITHUB = 'github';

const AVAILABLE_VERSION_CONTROL_SERVICES = [
  VERSION_CONTROL_NAME_GITHUB,
];

$versionControlName = VERSION_CONTROL_NAME_GITHUB;
$isServiceOption = false;
$service = getopt('', [OPTION_NAME_SERVICE . '::']);

if (!isCorrectArgumentsNumber($argumentsCount)) {
    throw new Exception(
        'Too few or too many arguments passed. Try passing parameters:'
        . PHP_EOL
        .'1: user/repo branch'
        . PHP_EOL
        . '2: service=service_name user/repo branch'
        . PHP_EOL
    );
}

/**
 * If service=xxx option passed, remove it from stack
 */
if (!empty($service)) {
    array_shift($arguments);
    $isServiceOption = true;
}

if (!$isServiceOption) {
    if (!isFirstArgumentUserRepo($arguments[$index])) {
        throw new Exception(
            'Invalid first argument passed. Try passing parameters:'
            . PHP_EOL
            .'1: user/repo branch'
            . PHP_EOL
            . '2: service=service_name user/repo branch'
            . PHP_EOL
        );
    }
}

/**
 * Splitting user/repo pattern
 */
$repoCredentials = explode('/', $arguments[$index]);

/** Removing user/repo argument */
array_shift($arguments);

if (!isValidRepoCredentials($repoCredentials)) {
    throw new Exception(
        'Invalid repo credentials format passed, correct format is user/repo'
        . PHP_EOL
    );
}

$login = $repoCredentials[0];
$repo = $repoCredentials[1];

if(empty($arguments)) {
    throw new Exception(
        'Branch has not been specified'
    );
}

$branch = end($arguments);

if ($isServiceOption) {
    $versionControlName = $service['service'];
}

if(in_array($versionControlName, AVAILABLE_VERSION_CONTROL_SERVICES)) {
  print getLatestCommitSha($versionControlName, $login, $repo, $branch);
} else {
    throw new Exception('Not found version control: ' . $versionControlName);
}


function isCorrectArgumentsNumber(int $argumentsCount): bool
{
    return ($argumentsCount < 3 || $argumentsCount > 4) ? false : true;
}

function isFirstArgumentUserRepo(string $firstArgument): bool
{
    if (false !== strpos($firstArgument, '/')) {
        return true;
    }

    return false;
}

function isValidRepoCredentials(array $repoCredentials): bool
{
    if (
        !(count($repoCredentials) > 2)
        && !(count($repoCredentials) < 2)
        && !(strlen($repoCredentials[0]) < 1)
        && !(strlen($repoCredentials[1]) < 1)
    ) {
        return true;
    }

    return false;
}


function exceptionHandler($exception): void
{
    print($exception->getMessage());
    die;
}

function getLatestCommitSha(string $versionControlName, string $login, string $repo, string $branch): string
{
     $factory  = new VersionControlAdapterFactory(new GuzzleApiClient(new Client(['exceptions' => false])), $login, $repo, $branch);
     $versionControl = $factory->createByName($versionControlName);

     return $versionControl->getLatestCommitSha();
}