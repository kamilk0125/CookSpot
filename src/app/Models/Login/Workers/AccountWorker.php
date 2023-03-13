<?php

declare(strict_types=1);

namespace App\Models\Login\Workers;

use App\Main\Container\Container;
use App\Models\Database\SQLQuery;
use DateTime;

class AccountWorker{

    public function __construct(private Container $container)
    {
        
    }

    public function registerAccount(string $username, string $displayName, string $email, string $password, string $confirmPassword){
        $accountData['activationHash'] = base64_encode(random_bytes(20));
        $accountData['id'] = $this->addNewAccount($username, $displayName, $email, $password, $accountData['activationHash']);

        return $accountData;
    }

    private function addNewAccount(string $username, string $displayName, string $email, string $password, string $activationHash){
        $query = new SQLQuery($this->container);
        $actualDate = new DateTime();
        $expirationDate = (new DateTime())->modify('+1 day');
        $query->insertTableRow('inactiveAccounts', 
        [
            'username' => $username, 
            'displayName' => $displayName, 
            'email' => $email, 
            'authHash' => password_hash($password, PASSWORD_DEFAULT),
            'activationHash' => $activationHash,
            'createdAt' => $actualDate->format('Y-m-d H:i:s'),
            'expirationDate' => $expirationDate->format('Y-m-d H:i:s')
        ]);

        return intval($query->lastInsertId());
    }

    public function accountExists(string $id):bool
    {
        $query = new SQLQuery($this->container);
        foreach(['usersInfo', 'inactiveAccounts'] as $table){
            $userInfo = $query->getTableRow($table, ['username' => $id, 'email' => $id]);
            if($userInfo!==false)
                return true;
        }
   
        return false;
    }

    public function removeInactiveAccount(int $userId){
        (new SQLQuery($this->container))->deleteTableRow('inactiveAccounts', ['id' => $userId]);
    }

    public function getAccountInfo(int $userId = 0, string $id = ''){
        return (new SQLQuery($this->container))->getTableRow('usersInfo', ['id' => $userId, 'username' => $id, 'email' => $id]);
    }
}