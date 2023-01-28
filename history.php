<?php

session_start();

function history($title, $connect_db, $load_area) {
    $main = '
<script src="js/history.js"></script>
<script>
    bin = "../imgs/trash.svg";
</script>
'.$connect_db.'
<p class="learn-text">Сохраняйте свои впечатления от поездок в комментариях</p>
<div class="content-wrap">
    
    <div id="content">

    </div>
</div>
'.$load_area.'
    ';

    return base(
        '<link rel="stylesheet" href="css/history.css">',
        $title,
        nav(3),
        $main
    );

}

?>
<?php

require 'base.php';
require 'nav.php';
require 'connect_db.php';

$user_id = $_SESSION['uid'];
$opened = '';

$title = 'Picnic trip - Travel history';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $comment = $_POST['comment'];
    $comment = str_replace("'", chr(2), $comment);
    $comment = str_replace("`", chr(3), $comment);
    mysqli_query($connect, "
        update history
        set comment = '".$comment."'
        where area_id = ".$id."
    ");
    $opened = $id;
}

if (isset($_SESSION['save_area'])) {
    $obj = json_decode($_SESSION['save_area'], true);
    mysqli_query($connect, "
        insert into history (user_id, area_id)
        values
        (".$user_id.", ".$obj['global_id'].")
    ");
    $opened = ''.$obj['global_id']; // to string?
    unset($_SESSION['save_area']);
}

if (isset($_POST['del_id'])) {
    mysqli_query($connect, "
        delete from history
        where area_id=".$_POST['del_id']." and user_id=".$user_id."
    ");
}


$res = mysqli_query($connect, "
    select area_id, comment
    from history
    where user_id = ".$user_id."
");
$areas = array();
while ($row = mysqli_fetch_assoc($res)) {
    //echo $row['area_id'].'<br>';
    $arr = array($row['area_id'], $row['comment']);
    if ($arr[1] == null) $arr[1] = '';
    else {
        $arr[1] = str_replace(chr(2), "\\'", $arr[1]);
        $arr[1] = str_replace(chr(3), "\\`", $arr[1]);
    }
    array_push($areas, $arr);
}
$load_area = '<script>';
foreach ($areas as $el) {
    $opnd = '';
    if ($opened == ''.$el[0]) $opnd = ', 1';
    $load_area .= "load_area('".$el[0]."', `".$el[1]."`".$opnd.");
    ";
}
$load_area .= '</script>';

$db = file_get_contents('data1.json');
$connect_db = "
<script>
    connect_db('".$db."');
</script>
";

echo history($title, $connect_db, $load_area);
?>

