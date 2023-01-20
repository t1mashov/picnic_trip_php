
<?php
function nav($a) { // $a = 1 || 2 || 3 || 0
    $a1 = '<a class="nav-btn" href="area.php">Подобрать площадку для пикника</a>';
    $a2 = '<a class="nav-btn" href="items.php">Что взять на пикник</a>';
    $a3 = '<a class="nav-btn" href="history.php">История путешествий</a>';
    switch ($a) {
        case 1:
            $a1 = '<span class="nav-btn selected">Подобрать площадку для пикника</span>'; break;
        case 2:
            $a2 = '<span class="nav-btn selected">Что взять на пикник</span>'; break;
        case 3:
            $a3 = '<span class="nav-btn selected">История путешествий</span>'; break;
    }
    return '
<nav>
    <div class="menu">
        '.$a1.$a2.$a3.'
    </div>
    <div class="out">
        <a class="nav-btn" href="index.php?out">Выйти</a>
    </div>
</nav>
    ';
}
?>
