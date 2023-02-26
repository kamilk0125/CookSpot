<div id="mainDiv">
    <div id="centerDiv" class="itemContainer">
        <div id="results" class="itemContainer">
            <div id="resultsHeader" class="itemContainer">
                <h2>Search Results</h2>
            </div>    
            <h2 class="<?php echo (empty($this->searchResults) && (strlen($this->searchKeyword) > 0)) ? '' : 'invisible';?>">No Results</h2>
                <?php
                    foreach($this->searchResults as $result){
                        $redirectLink = "profile?view=user&id={$result['id']}";
                        $imageSrc = 'resource?type=img&path=' . $result['picturePath'];
                        $headerText = $result['displayName'];
                        $formHandler = 'friendsHandler';
                        switch($result['relation']['status']){
                            case 'friend':
                                $leftBtnText = '✓ Friends';
                                $leftBtnDisabled = true;
                                $leftBtnClass = 'disabled';
                                break;
                            case 'invitationReceived':
                                $formAction = 'answerInvitation';
                                $leftBtnText = 'Accept Invitation';
                                $leftBtnName = 'args[response]';
                                $leftBtnValue = 'true';
                                $formArgs = ['args[invitationId]' => $result['args']['invitationId']];
                                break;
                            case 'invitationSent':
                                $leftBtnText = '✓ Invitation Sent';
                                $leftBtnDisabled = true;
                                $leftBtnClass = 'disabled';
                                break;
                            default:
                                $formAction = 'newInvitation';
                                $leftBtnText = 'Add to friends';
                                $leftBtnName = 'args[friendId]';
                                $leftBtnValue = $result['id'];
                        }
                        $rightBtnClass = 'invisible';

                        include(__DIR__ . '/../../Common/Components/Templates/ConfirmationTile.php');
                    }
                ?>
        </div>
    </div>
    
</div>
