<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', 'git@github.com:melisje/lnl-rentman.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('easyhost-lnl02.llstageservice.be')
    ->set('remote_user', 'jef')
    ->set('deploy_path', '/var/wwww/lnl-rentman');

// Taken
task('deploy', [
    'deploy:prepare',
    'deploy:vendors',
    'artisan:storage:link',
    'artisan:migrate',
    'artisan:config:cache',
    'artisan:route:cache',
    'artisan:view:cache',
    'artisan:event:cache',
    'deploy:publish',
    ]);
// Hooks
// Als een deploy faalt, ontgrendel de boel voor de volgende poging
after('deploy:failed', 'deploy:unlock');

// CRUCIAL: Herstart de queue na een succesvolle deploy
after('deploy:publish', 'artisan:queue:restart');


