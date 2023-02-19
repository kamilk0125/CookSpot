<div id="layout">
    <div id="leftSide" class="itemContainer">
        <div id="friends" class="itemContainer">
            <div id="friendsHeader" class="ContainerHeader"><h2>Friends</h2></div>
            <div id="friendsList" class="itemContainer">
                <?php
                    foreach($this->friendsManager->friendsList as $friend){
                        $redirectLink = '';
                        $imageSrc = 'resource?type=img&path=' . $friend['picturePath'];
                        $headerText = $friend['displayName'];
                        include(__DIR__ . '/../../Common/Components/Templates/Tile.php');
                    }
                ?>
            </div>
        </div>
    </div>
    <div id="invitations" class = "itemContainer">
        <div class="ContainerHeader"><h2>Invitations</h2></div>
        <div id="invitationsList" class = "listContainer">
                <?php
                foreach($this->friendsManager->receivedInvitations as $invitation){
                    $redirectLink = '';
                    $formTarget = 'friends';
                    $formHandler = 'answerInvitation';
                    $formArgs = ['args[invitationId]' => $invitation['invitationId']];
                    $imageSrc = 'resource?type=img&path=' . $invitation['picturePath'];
                    $headerText = $invitation['displayName'];
                    $leftBtnText = 'Accept';
                    $leftBtnName = 'args[response]';
                    $leftBtnValue = '1';
                    $rightBtnText = 'Deny';
                    $rightBtnName = 'args[response]';
                    $rightBtnValue = '0';
                    include(__DIR__ . '/../../Common/Components/Templates/ConfirmationTile.php');
                }
                ?>
            </form>
        </div>    
    </div>
</div>
