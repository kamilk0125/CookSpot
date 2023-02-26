<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type = "text/css" href="styles/main.css">
    <?php if(isset($this->cssFile)) echo '<link rel="stylesheet" type = "text/css" href="styles/' . $this->cssFile .'">'; ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->pageName ?></title>
</head>
<body>
      <div class="header <?php echo isset($_SESSION['currentUser']) ? 'loggedIn' : '';?>">
            <a id="logo" href="/"><img src = "resource?type=img&path=general/logo.svg" alt = "Logo"></a>
            <form id="searchForm" action="search" method="GET" class="">
                  <div id="searchBar" class="<?php echo isset($_SESSION['currentUser']) ? '' : 'invisible';?>">
                        <input type="text" placeholder="search for..." name="keyword" value="<?php echo $_GET['keyword'] ?? '';?>">
                        <button type="submit" class="squared">Search</button> 
                  </div>
            </form>
            <a id="logoutBtn" class="<?php echo isset($_SESSION['currentUser']) ? '' : 'invisible';?>" href="login?view=logout"><button class="squared">Log out</button></a>
      </div>
      <div id="pageContent">