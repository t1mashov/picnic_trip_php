
<?php

function index($title, $error_message) {

    $main = '
<div class="content">
    <div class="left">
        <p>
            С помощью нашего приложения Вы сможете <b>найти</b> площадку,
            подходящую Вам по всем параметрам, <b>подготовиться</b> к походу,
            собрать необходимые вещи и ничего не забыть
        </p>
        <p>
            Зарегистрируйтесь или войдите в аккаунт, чтобы получить <b>полный доступ</b> к функционалу приложения
        </p>
        <p><a class="link-open-data" href="https://data.mos.ru/opendata/7708308010-mesta-dlya-piknika">
            На сайте используются данные из Портала открытых данных города Москвы
        </a></p>
        <div class="no-reg-wrap">
            <a href="area.php?guest"><button class="green-btn">Продолжить без регистрации</button></a>
        </div>
    </div>
    <div class="right">
        <form method="post">
            <h3>Вход</h3>
            <table>
            <tr>
                <td class="td-name">Логин:</td>
                <td><input type="text" autocomplete="new-password" name="name"></td>
            </tr>
            <tr>
                <td class="td-name">Пароль:</td>
                <td><input type="password" autocomplete="new-password" name="password"></td>
            </tr>
            </table>
            <div class="form-btn-wrap">
                <div><button class="form-btn" type="submit" name="enter">Войти</button></div>
                <div><button class="form-btn" type="submit" name="reg">Зарегистрироваться</button></div>
            </div>
            <div class="error">
                '.$error_message.'
            </div>
        </form>
    </div>
</div>
    ';

    return base(
        '<link rel="stylesheet" href="../css/index.css">',
        $title,
        '',
        $main
    );
}
?>


<?php

include 'base.php';
include 'nav.php';
include 'connect_db.php';

$error_message = '';
$title = 'Picnic Trip - Authorization';

session_start();

if (isset($_POST['enter'])) {
    $login = str_replace("'", '`', $_POST['name']);
    $password = $_POST['password'];
    $password_hash = md5($password);

    $res = mysqli_query($connect, "
        select * from user
        where name='".$login."' and password_hash='".$password_hash."'
    ");
    if ($user = mysqli_fetch_assoc($res)) {
        $_SESSION['uid'] = $user['id'];
        header('Location: area.php');
    } else {
        $error_message = 'Неверный логин или пароль';
    }
}
else if (isset($_POST['reg'])) {
    $login = trim($_POST['name']);
    $password = $_POST['password'];

    if ($login == '') {
        $error_message = 'Укажите логин';
    } else {
        $res = mysqli_query($connect, "
            select name from user
        ");
        $uniq = true;
        while ($user = mysqli_fetch_assoc($res)) {
            if ($login == $user['name']) $uniq = false;
        }

        if ($uniq) {
            mysqli_query($connect, "
                insert into user (name, password_hash)
                values
                ('".$login."', '".md5($password)."')
            ");
        } else {
            $error_message = 'Такой логин уже существует';
        }
    }

}

else if (isset($_GET['out']) || isset($_GET['guest'])) {
    if (isset($_SESSION['uid'])) {
        unset($_SESSION['uid']);
    }
    if (isset($_GET['guest'])) {
        header('Location: area.php');
    }
}

//foreach($_SESSION as $k => $v) echo $k.' => '.$v.'<br>';

echo index($title, $error_message);

?>