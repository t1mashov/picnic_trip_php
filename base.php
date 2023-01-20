
<?php

function base($styles, $title, $nav, $main) {
    return '<!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/base.css">
        <link rel="stylesheet" href="css/nav.css">
        '.$styles.'
        <title>'.$title.'</title>
    </head>
    <body>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <header>
            <div class="app-name">
                <h1>Picnic Trip</h1>
                <p>Отдохните на лучшем пикнике в вашей жизни!</p>
            </div>
            '.$nav.'
        </header>
        <main>
            '.$main.'
        </main>
    </body>
    </html>';
}
?>