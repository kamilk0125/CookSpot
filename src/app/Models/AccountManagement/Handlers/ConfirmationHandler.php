<?php

declare(strict_types=1);

namespace App\Models\AccountManagement\Handlers;

use App\Main\Container\Container;
use App\Models\AccountManagement\User;
use App\Models\Database\SQLQuery;
use App\Models\Resource\ResourceManager;
use Exception;

class ConfirmationHandler{
    
    public function __construct(private Container $container)
    {
        
    }

    public function verifyEmail(int $userId, string $verificationHash){
        $query = new SQLQuery($this->container);

        try{
            $userInfo = $query->getTableRow('emailVerifications', ['userId' => $userId]);
            if($userInfo!== false){
                if($userInfo['verificationHash'] === $verificationHash){
                    $query->beginTransaction();
                    $query->updateTableRow('usersInfo', ['id' => $userId], ['email' => $userInfo['newEmail']]);
                    $query->deleteTableRow('emailVerifications', ['userId' => $userId]);
                    $query->commit();
                    return true;
                }
            }
        }
        catch(Exception $e){
            if($query->inTransaction()){
                $query->rollback();
            }
        }

        return false;
    }

    public function activateAccount(int $id, string $activationHash){
        $query = new SQLQuery($this->container);
        try{
            $userInfo = $query->getTableRow('inactiveAccounts', ['id' => $id]);
            if($userInfo!== false){
                if($userInfo['activationHash'] === $activationHash){
                    unset($userInfo['id'], $userInfo['activationHash'], $userInfo['expirationDate']);
                    $userInfo['picturePath'] = ResourceManager::COMMON_STORAGE_DIR . 'defaultProfilePicture.png';
                    $query->beginTransaction();
                    $query->insertTableRow('usersInfo', $userInfo);
                    $userId = intval($query->lastInsertId());
                    (new User(id: $userId))->createStorageDir();
                    $query->deleteTableRow('inactiveAccounts', ['email' => $userInfo['email']]);
                    $query->commit();

                    return true;
                }
            }
        }
        catch(Exception $e){
            if($query->inTransaction()){
                $query->rollback();
            }
        }

        return false;
    }

    public function authorizePasswordReset(int $userId, string $verificationHash){
        $query = new SQLQuery($this->container);

        try{
            $passwordResetInfo = $query->getTableRow('passwordResetRequests', ['userId' => $userId]);
            if($passwordResetInfo!== false){
                if($passwordResetInfo['verificationHash'] === $verificationHash){
                    return true;
                }
            }
        }
        catch(Exception){
        }

        return false;
    }


}