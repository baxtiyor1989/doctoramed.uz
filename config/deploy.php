<?php

return [
    'enabled' => env('DEPLOY_ENABLED', true),
    'allowed_hosts' => array_values(array_filter(array_map(
        'trim',
        explode(',', env('DEPLOY_ALLOWED_HOSTS', 'doctoramed.uz,www.doctoramed.uz'))
    ))),
    'repository' => env('DEPLOY_GIT_REPOSITORY', 'https://github.com/baxtiyor1989/doctoramed.uz.git'),
    'remote' => env('DEPLOY_GIT_REMOTE', 'origin'),
    'branch' => env('DEPLOY_GIT_BRANCH', 'master'),
    'timeout' => (int) env('DEPLOY_TIMEOUT', 900),
];
