<?php

declare(strict_types=1);

namespace App\Models\Login\Workers;

use App\Main\Container\Container;
use App\Models\Database\SQLQuery;
use DateTime;

class VerificationWorker{

    public function __construct(private Container $container)
    {
        
    }

    public function addNewEmailVerification(int $userId, string $newEmail){
        $verificationHash = base64_encode(random_bytes(20));
        $actualDate = new DateTime();
        $expirationDate = (new DateTime())->modify('+1 day');
        $query = new SQLQuery($this->container);
        $queryResult = $query->getTableRow('emailVerifications', ['userId' => $userId]);

        if($queryResult!==false){
            $query->updateTableRow('emailVerifications', ['userId' => $userId], 
            [
                'newEmail' => $newEmail, 
                'verificationHash' => $verificationHash,
                'createdAt' => $actualDate->format('Y-m-d H:i:s'),
                'expirationDate' => $expirationDate->format('Y-m-d H:i:s')
            ]);
        }
        else{
            $query->insertTableRow('emailVerifications', 
            [
                'userId' => $userId, 
                'newEmail' => $newEmail, 
                'verificationHash' => $verificationHash,
                'createdAt' => $actualDate->format('Y-m-d H:i:s'),
                'expirationDate' => $expirationDate->format('Y-m-d H:i:s')
            ]);
        }
        return $verificationHash;
    }

    public function createPasswordResetRequest(int $userId){
        $actualDate = new DateTime();
        $expirationDate = (new DateTime())->modify('+1 day');
        $query = new SQLQuery($this->container);
            
        $verificationHash = base64_encode(random_bytes(20));
        $query->insertTableRow('passwordResetRequests', [
            'userId' => $userId, 
            'verificationHash' => $verificationHash, 
            'createdAt' => $actualDate->format('Y-m-d H:i:s'),
            'expirationDate' => $expirationDate->format('Y-m-d H:i:s')
        ]);
       
        return $verificationHash;
    }

    public function getPasswordResetRequest(int $userId){
        return (new SQLQuery($this->container))->getTableRow('passwordResetRequests', ['userId' => $userId]);
    }

    public function removePasswordResetRequest(int $userId){
        return (new SQLQuery($this->container))->deleteTableRow('passwordResetRequests', ['userId' => $userId]);
    }
}