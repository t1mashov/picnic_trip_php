
<?php

function area($title, $connect_db, $saved_areas, $reg, $a) {
    $nav = '';
    if ($reg == 'true') {
        $nav = nav($a);
    }

    $main = '
<script src="https://api-maps.yandex.ru/2.1/?apikey=34942c00-6f51-4f9e-997f-91c26650fdc9&lang=ru_RU"></script>
<script src="../js/area.js"></script>
'.$connect_db.'
'.$saved_areas.'
<script>
    ymaps.ready(init);
</script>
<script>
    reg = '.$reg.';
</script>

<div class="content">
    <div class="learn-text">Укажите необходимые параметры поиска площадок для пикника</div>
    <div class="settings-wrap">

        <div class="settings">
            <div class="inp-wrap">
                <p>Дата похода на пикник</p>
                <input type="date" class="inp" name="date" id="i-date">
            </div>
            <div class="inp-wrap">
                <p>Время похода на пикник</p>
                <input type="time" class="inp" name="time" id="i-time">
            </div>
            <script>
                set_today()
            </script>

            <p>Отметьте, какие удобства обязательно должны быть в наличии</p>
            <div class="inp-wrap check-wrap">
                <button class="check-btn" id="HasDressingRoom" onclick="click_params(\'HasDressingRoom\')">раздевалка</button>
                <button class="check-btn" id="HasEatery" onclick="click_params(\'HasEatery\')">точка питания</button>
                <button class="check-btn" id="HasToilet" onclick="click_params(\'HasToilet\')">туалет</button>
                <button class="check-btn" id="HasWifi" onclick="click_params(\'HasWifi\')">Wi-Fi</button>
                <button class="check-btn" id="HasFirstAidPost" onclick="click_params(\'HasFirstAidPost\')">медпункт</button>
            </div>

        </div>
    </div>

    <div class="btn-wrap">
        <div class="green-btn" onclick="apply_filter()">Применить</div>
    </div>

    <form method="post">
        <div class="learn-text">Для построения маршрута, нажмите на название из списка площадок</div>
        <div class="map-wrap">
            <div class="map" id="map">
            </div>

            <div class="areas" id="areas">
            </div>
        </div>
        <div id="res">

        </div>
    </form>

</div>

<script>
    //apply_filter()
    fill_areas()
</script>
    ';

    return base(
        '<link rel="stylesheet" href="../css/area.css">',
        $title,
        $nav,
        $main
    );
}
?>


<?php

include 'base.php';
include 'nav.php';
include 'connect_db.php';

session_start();

foreach ($_POST as $k => $v) echo $k.' => '.$v.'<br>';

$a = 1;
$title = 'Picnic Trip - Area selection';

if (isset($_SESSION['uid'])) {
    $user_id = $_SESSION['uid'];
    $res = mysqli_query($connect, "
        select area_id, comment
        from history
        where user_id = ".$user_id."
    ");

    $saved_areas = "
        <script>
            saved = {
    ";
    while ($el = mysqli_fetch_assoc($res)) {
        $comment = $el['comment'];
        if ($comment == null) $comment = '';
        $comment = str_replace(chr(2), "\\'", $comment);
        $comment = str_replace(chr(3), "\\`", $comment);

        $saved_areas .= "'".$el['area_id']."' : `".$comment."`,
        ";
    }
    $saved_areas .= "
            }
        </script>
    ";
}

if (isset($_POST['save_area'])) {
    $objstr = str_replace("''''", '"', $_POST['save_area']);
    $_SESSION['save_area'] = $objstr;
    header('Location: history.php');
}

$db = file_get_contents('data1.json');

$connect_db = "
<script>
    connect_db('".$db."');
</script>
";

$reg = 'false';
if (isset($_SESSION['uid'])) {
    $reg = 'true';
} else {
    $saved_areas = '';
}

echo area($title, $connect_db, $saved_areas, $reg, $a)

?>