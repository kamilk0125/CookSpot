<?php

declare(strict_types=1);

namespace App\Util\Confirmation\Workers;

use App\Model\User;
use App\Util\Database\SQLQuery;
use App\Util\Resource\Handlers\ResourceHandler;
use Exception;

class ConfirmationWorker
{    
    public function __construct()
    {
        
    }

    public function verifyEmail(int $userId, string $verificationHash){
        $query = new SQLQuery();

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
        $query = new SQLQuery();
        try{
            $userInfo = $query->getTableRow('inactiveAccounts', ['id' => $id]);
            if($userInfo!== false){
                if($userInfo['activationHash'] === $activationHash){
                    unset($userInfo['id'], $userInfo['activationHash'], $userInfo['expirationDate']);
                    $userInfo['picturePath'] = ResourceHandler::COMMON_STORAGE_DIR . 'defaultProfilePicture.png';
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
        $query = new SQLQuery();
        try{
            $passwordResetInfo = $query->getTableRow('passwordResetRequests', ['userId' => $userId]);
            if($passwordResetInfo!== false){
                if($passwordResetInfo['verificationHash'] === $verificationHash){
                    return true;
                }
            }
        }
        catch(Exception){}
        
        return false;
    }


}