<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type = "text/css" href="styles/main/main.css">
    <link rel="icon" type="image/svg+xml" href="/resource?type=img&path=general/icon.svg">
    <?php if(isset($this->cssFile)) echo '<link rel="stylesheet" type="text/css" href="styles/' . $this->cssFile .'">'; ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width">
    <title><?php echo $this->pageName ?></title>
</head>
<body>
      <div id="header" class="css-header <?php echo isset($_SESSION['currentUser']) ? 'css-loggedIn' : '';?>">
            <a id="logo" href="/"><img src="/resource?type=img&path=general/logo.svg" alt="Logo"></a>
            <form id="searchForm" action="/search" method="GET" class="">
                  <div id="searchBar" class="<?php echo isset($_SESSION['currentUser']) ? '' : 'css-invisible';?>">
                        <input type="text" placeholder="search for..." name="keyword" value="<?php echo $_GET['keyword'] ?? '';?>">
                        <button type="submit" class="css-squared">Search</button> 
                  </div>
            </form>
            <a id="logoutBtn" class="<?php echo isset($_SESSION['currentUser']) ? '' : 'css-invisible';?>" href="login?view=logout"><button class="css-squared">Log out</button></a>
      </div>
      <div id="pageContent">
            <div id="mainNav" class="css-topNav css-flexHorizontal <?php echo isset($_SESSION['currentUser']) ? '' : 'css-invisible';?>">
                  <a href="/profile">Profile</a>
                  <a href="/friends">Friends</a>
                  <a href="/share">Share</a>
            </div>