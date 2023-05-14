<div id="layout" class="css-flexHorizontal">
    <div id="leftSide" class="css-itemContainer">
        <div id="friends" class="css-itemContainer">
            <div id="friendsHeader" class="css-containerHeader"><h2>Friends</h2></div>
            <div id="friendsList" class="css-itemContainer">
                <?php
                    foreach($this->friendsList as $friend){
                        $checkboxClass = 'css-invisible';
                        $redirectLink = "/profile?view=user&id={$friend['id']}";
                        $imageSrc = '/resource?type=img&path=' . $friend['picturePath'];
                        $headerText = $friend['displayName'];
                        include(__DIR__ . '/../../Common/Components/Templates/Tile.php');
                    }
                ?>
            </div>
        </div>
    </div>
    <div id="invitations" class = "css-itemContainer">
        <div class="css-containerHeader"><h2>Invitations</h2></div>
        <div id="invitationsList" class = "">
                <?php
                foreach($this->receivedInvitations as $invitation){
                    $redirectLink = "/profile?view=user&id={$invitation['senderId']}";
                    $formTarget = '/friends';
                    $formHandler = 'friendsHandler';
                    $formAction = 'answerInvitation';
                    $formArgs = ['args[invitationId]' => $invitation['invitationId']];
                    $imageSrc = '/resource?type=img&path=' . $invitation['picturePath'];
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
