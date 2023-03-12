<div id="mainDiv">
    <div id="results" class="css-itemContainer">
        <div id="resultsHeader" class="css-itemContainer">
            <h2>Search Results</h2>
        </div>    
        <h2 class="<?php echo (empty($this->searchResults) && (strlen($this->searchKeyword) > 0)) ? '' : 'css-invisible';?>">No Results</h2>
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
                            $leftBtnClass = 'css-disabled';
                            break;
                        case 'invitationReceived':
                            $formAction = 'answerInvitation';
                            $leftBtnText = 'Accept Invitation';
                            $leftBtnDisabled = false;
                            $leftBtnClass = '';
                            $leftBtnName = 'args[response]';
                            $leftBtnValue = 'true';
                            $formArgs = ['args[invitationId]' => $result['relation']['invitationId']];
                            break;
                        case 'invitationSent':
                            $leftBtnText = '✓ Invitation Sent';
                            $leftBtnDisabled = true;
                            $leftBtnClass = 'css-disabled';
                            break;
                        default:
                            $formAction = 'newInvitation';
                            $leftBtnText = 'Add to friends';
                            $leftBtnDisabled = false;
                            $leftBtnClass = '';
                            $leftBtnName = 'args[friendId]';
                            $leftBtnValue = $result['id'];
                    }
                    $rightBtnClass = 'css-invisible';

                    include(__DIR__ . '/../../Common/Components/Templates/ConfirmationTile.php');
                }
            ?>
    </div>
</div>
