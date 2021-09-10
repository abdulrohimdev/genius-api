<?php

namespace Package\Backup;

use Package\Backup\Application;
use Package\Backup\User;
use Package\Backup\Role;
use Package\Backup\RoleGroup;
use Package\Backup\UserRole;
use Package\Backup\ApplicationGroup;

class Backup
{
    public function run(){
        return [
                Application::backup(),
                User::backup(),
                Role::backup(),
                RoleGroup::backup(),
                UserRole::backup(),
                ApplicationGroup::backup(),
            ];
    }

    
}