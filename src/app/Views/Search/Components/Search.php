<div id="mainDiv">
    <div id="centerDiv" class="itemContainer">
        <div id="results" class="itemContainer">
            <div id="resultsHeader" class="itemContainer">
                <h2>Search Results</h2>
            </div>    
            <h2 class="<?php echo (empty($this->searchManager->searchResults) && !empty($this->formData)) ? '' : 'invisible';?>">No Results</h2>
                <?php
                    foreach($this->searchManager->searchResults as $result){
                        $redirectLink = '';
                        $imageSrc = 'resource?type=img&path=' . $result['picturePath'];
                        $headerText = $result['displayName'];
                        switch($result['relationStatus']){
                            case 'friend':
                                $leftBtnText = '✓ Friends';
                                $leftBtnDisabled = true;
                                $leftBtnClass = 'disabled';
                                break;
                            case 'invitationReceived':
                                $formHandler = 'answerInvitation';
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
                                $formHandler = 'newInvitation';
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
