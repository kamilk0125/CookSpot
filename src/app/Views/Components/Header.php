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
      <div class="header">
            <a href="/"><img src = "resource?type=img&path=general/logo.svg" alt = "Logo"></a>
      </div>
      <div id="pageContent">