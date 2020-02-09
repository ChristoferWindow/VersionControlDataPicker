<?php

/**
 * Position in argv array, omitting file name at 0 index
 */
$index = 1;
$arguments = $argv;
$argumentsCount = $argc;

const VERSION_CONTROL_NAME_GITHUB = 'github';

const AVAILABLE_VERSION_CONTROL_SERVICES = [
  VERSION_CONTROL_NAME_GITHUB,
];

$versionControlName = VERSION_CONTROL_NAME_GITHUB;
$isServiceOption = false;
$service = getopt('', ['service::']);

/**
 * If service=xxx option passed, increase index and omit 1 position
 */
if (!empty($service)) {
    $index++;
    $isServiceOption = true;
}

/**
 * Splitting user/repo pattern
 */
$repoCredentials = explode('/', $arguments[$index]);

$errorMessages = validateInput($argumentsCount, $arguments, $index, $isServiceOption, $repoCredentials);
$branch = $arguments[$index + 1];

if(!empty($errorMessages)) {
    displayErrors($errorMessages);
    die;
}

$login = $repoCredentials[0];
$repo = $repoCredentials[1];

if ($isServiceOption) {
    $versionControlName = $service[0];
}

if(in_array($versionControlName, AVAILABLE_VERSION_CONTROL_SERVICES)) {
    $adapter = versionControlAdapterFactory($versionControlName,$login, $repo, $branch);
    print($adapter->getLatestCommitSha());
}


function isCorrectArgumentsNumber(int $argumentsCount): bool
{
    return ($argumentsCount === 2 || $argumentsCount === 3) ? true : false;
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

function versionControlAdapterFactory(
    string $versionControlName,
    string $login,
    string $repo,
    string $branch
): VersionControlAdapter {
    switch($versionControlName) {
        case VERSION_CONTROL_NAME_GITHUB:
            return new GithubVersionControlAdapter(new \GuzzleHttp\Client(), $login, $repo, $branch);
            break;
    }
}

function validateInput(int $argumentsCount, array $arguments, int $index, bool  $isServiceOption, array $repoCredentials
): array {
    $errorMessageBag = [];

    /** If no arguments passed, quit validation*/
    if($argumentsCount === 1) {
        $errorMessageBag ['no_arguments'] = 'No arguments passed';
        return $errorMessageBag;
    }
    if (!isCorrectArgumentsNumber($argumentsCount)) {
        $errorMessageBag['arguments_count'] =
            'Too few or too many arguments passed. Try passing parameters:'
            . PHP_EOL
            .'1: user/repo branch'
            . PHP_EOL
            . '2: service=service_name user/repo branch'
            . PHP_EOL
        ;
    };

    if (!$isServiceOption) {
        if (!isFirstArgumentUserRepo($arguments[$index])) {
            $errorMessageBag['first_argument'] =
                'Invalid first argument passed. Try passing parameters:'
                . PHP_EOL
                .'1: user/repo branch'
                . PHP_EOL
                . '2: service=service_name user/repo branch'
                . PHP_EOL
            ;
        }
    }

    if (!isValidRepoCredentials($repoCredentials)) {
        $errorMessageBag['repo_format'] =
            'Invalid repo credenitals format passed, correct format is user/repo'
            . PHP_EOL
        ;
    }

    return $errorMessageBag;
}

function displayErrors(array $errorMessages): void
{
    foreach ($errorMessages as $errorMessage) {
        print($errorMessage);
    }
}