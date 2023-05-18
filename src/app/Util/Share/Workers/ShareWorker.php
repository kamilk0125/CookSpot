<?php

declare(strict_types=1);

namespace App\Util\Share\Workers;

use App\Util\Database\SQLQuery;

class ShareWorker
{
    public function __construct()
    {
        
    }

    public function addSharedRecipe(int $ownerId, int $recipeId, string $recipeFilePath){
        $query = new SQLQuery();
        $query->insertTableRow('sharedRecipes', 
        [
            'ownerId' => $ownerId, 
            'recipeId' => $recipeId,
            'recipeFilePath' => $recipeFilePath
        ]);

        return intval($query->lastInsertId());
    }

    public function addUserShareInfo(int $sharedItemId, int $userId){
        $query = new SQLQuery();
        $query->insertTableRow('usersShareInfo', 
        [
            'userId' => $userId, 
            'sharedItemId' => $sharedItemId
        ]);
        
    }

    public function getSharedRecipesInfo(int $userId){
        $query = new SQLQuery();
        $queryResults = $query->executeQuery(
            "SELECT sharedRecipes.sharedItemId, sharedRecipes.ownerId, sharedRecipes.recipeId, sharedRecipes.recipeFilePath, sharedResources.resourceId AS pictureId
            FROM sharedRecipes 
            INNER JOIN usersShareInfo 
            ON usersShareInfo.userId = :userId AND usersShareInfo.sharedItemId = sharedRecipes.sharedItemId
            INNER JOIN sharedResources
            ON usersShareInfo.sharedItemId = sharedResources.sharedItemId AND sharedResources.resourceType = 'img'" ,
            ['userId' => $userId]
        )->fetchAll();

        $sharedRecipesInfo = [];
        if(!empty($queryResults)){
            foreach($queryResults as $result){
                $ownerId = $result['ownerId'];
                $sharedRecipesInfo[$ownerId]['recipeFilePath'] = $result['recipeFilePath'];
                $sharedRecipesInfo[$ownerId]['recipes'][$result['recipeId']]=[
                    'recipeId' => $result['recipeId'], 'sharedItemId' => $result['sharedItemId'], 'pictureId' => $result['pictureId']
                ];
            }
        }
        return $sharedRecipesInfo;
    }

    public function getRecipeShareInfo(int $ownerId, int $recipeId){
        $shareInfo = [];
        $query = new SQLQuery();
        $queryResults = $query->executeQuery(
            'SELECT usersShareInfo.userId, usersShareInfo.sharedItemId 
            FROM usersShareInfo 
            INNER JOIN sharedRecipes 
            ON sharedRecipes.sharedItemId = usersShareInfo.sharedItemId 
            AND sharedRecipes.ownerId = :ownerId 
            AND sharedRecipes.recipeId = :recipeId',
            ['ownerId' => $ownerId, 'recipeId' => $recipeId]
        )->fetchAll();

        foreach($queryResults as $result){
            $shareInfo['sharedItemId'] = $result['sharedItemId'];
            $shareInfo['recipients'][] = $result['userId'];
        }

        return $shareInfo;
    }

    public function removeRecipeShareInfo(int $sharedItemId){
        $query = new SQLQuery();
        $query->deleteTableRow('sharedRecipes', ['sharedItemId' => $sharedItemId]);
        $query->deleteTableRow('usersShareInfo', ['sharedItemId' => $sharedItemId]);
        $query->deleteTableRow('sharedResources', ['sharedItemId' => $sharedItemId]);
    }

    








}