

<?php
function items($title, $items, $my_items) {
    // foreach ($items as $key => $val) {
    //     foreach ($val as $el) {
    //         echo $key.' '.$el.'<br>';
    //     }
    // }
    $my_list = '';
    $main = '
    <script src="js/items.js"></script>
    <form method="post">
        <p class="learn-text">
            Сборы на пикник – дело хлопотное. 
            Кто не ловил себя на мысли о том, 
            чтобы взять все самое необходимое, 
            а по приезду на место, выяснял, что это важное, 
            оказывается, оставил дома. 
        </p>
        <p class="learn-text">
            Например, многие забывают такие важные вещи, 
            как спички, соль, ножи.
        </p>
        <p class="learn-text">
            <b>Чтобы не попасть</b> в подобную ситуацию,
            заносите вещи, которые хотите взять
            в список ниже и, при сборе, отмечайте, 
            которые уже взяли.
        </p>
        <div class="content">
            <div class="std-list">
                <h3>Стандартный список</h3>
                <div class="std-list-content">';

        foreach ($items as $key => $val) {
            $main.='<div class="category-name">
                        <div><b>'.$key.'</b></div>
                        <div class="show-hide" id="btn_'.$key.'" onclick="show_hide_category(\'btn_'.$key.'\', \'container_'.$key.'\')">
                            Скрыть
                        </div>
                    </div>
                    <div id="container_'.$key.'">';
        
            foreach ($val as $el) {
                $main.='<div class="std-item" data-content="'.$el.'">
                            <div class="item-name">'.$el.'</div>
                            <div class="send-to-my-list" onclick="add_element_from_std(\'imgs/trash.svg\', \''.$el.'\')">&gt&gt</div>
                        </div>';
            
            }
            $main.='</div>';
                        
        }
        $main.='</div>
            </div>
            <div class="my-list">
                <h3>Мой список</h3>
                <div class="my-list-content-wrap">
                    <div class="find">
                        <input class="find-input" placeholder="Поиск" oninput="find_element()" id="find-input">
                    </div>
                    <div class="my-list-content">
';   

        $my_list = '<script>
            my_list = [
        ';

        foreach ($my_items as $el) {
            $ready_item = '';
            $ready_trash = '';
            $ready_check = '';
            $check_img = '';
            if ($el['checked']) {
                $ready_item = 'ready-item';
                $ready_trash = 'ready-trash';
                $ready_check = 'ready-check';
                $check_img = '<img src="imgs/check.svg" width="20px">';
            }
                    $el = '<div class="my-item '.$ready_item.'" data-content="'.$el['name'].'">
                        <div class="my-item-w1">
                            <div class="trash '.$ready_trash.'" onclick="del_element(\''.$el['name'].'\')">
                                <img class="trash-img" src="imgs/trash.svg">
                            </div>
                            <div class="item-name">'.$el['name'].'</div>
                        </div>
                        <div class="my-item-w2" onclick="select(\''.$el['name'].'\')">
                            <div class="check '.$ready_check.'">
                                '.$check_img.'
                            </div>
                        </div>
                    </div>';
                    $main .= $el;
                    $my_list .= "`".$el."`, ";
        }
        $my_list .= '
            ];
        for (let i=0; i<my_list.length; i++) {
            my_list[i] = $(my_list[i]);
        }
        </script>
        ';
        
            $main .= '</div>
                </div>
                <div class="green-btn add-el" id="add-el-btn" onclick="add_element()">Добавить элемент</div>
                <button class="green-btn add-el" id="save-page-btn" onfocus="on_save_btn()">Сохранить изменения</button>
                <div class="hidden" id="add-el-container">
                    <p><input class="add-el-field" type="text" id="add-el-field"></p>
                    <div class="save">
                        <div class="green-btn save-out" onclick="add_element_from_input(\'./imgs/trash.svg\')">Сохранить</div>
                        <div class="green-btn save-out" onclick="cancel()">Отменить</div>
                    </div>
                </div>
            </div>
        </div>
        <div id="res">
    
        </div>
    </form>

    '.$my_list.'

    ';

    return base(
        '<link rel="stylesheet" href="../css/items.css">',
        $title,
        nav(2),
        $main
    );
}
?>


<?php

include 'base.php';
include 'nav.php';
include 'connect_db.php';

session_start();

$user_id = $_SESSION['uid'];
$title = 'Picnic Trip - Collecting items';

if (isset($_POST['items'])) {
    mysqli_query($connect, "
        delete from my_item
        where user_id=".$user_id."
    ");
    // echo $_POST['items'].'<br>';
    $mas = array();
    $items = explode(',', $_POST['items']);
    for ($i=0; $i<sizeof($items)-1; $i++) {
        array_push($mas, explode(':', $items[$i]));
    }

    if (@$mas[0][0] != '') {
        $add_items = "
            insert into my_item (name, checked, user_id)
            values
        ";
        for ($i=0; $i<sizeof($mas); $i++) {
            $add_items .= "('".$mas[$i][0]."', ".$mas[$i][1].", ".$user_id.")";
            if ($i < sizeof($mas)-1) $add_items .= ', ';
        }
        // echo $add_items;
        $res = mysqli_query($connect, $add_items);
    }
}

$res = mysqli_query($connect, "
    select name, checked 
    from my_item
    where user_id=".$user_id."
");
$my_items = mysqli_fetch_all($res);
$my_items_arr = array();
foreach ($my_items as &$row) {
    array_push($my_items_arr, array(
        'name' => $row[0],
        'checked' => $row[1]
    ));
}

$res = mysqli_query($connect, "
    select c.name as cname, i.name as iname
    from category c join item i on (i.category_id = c.id)
");
$table = mysqli_fetch_all($res);

$items_dict = array();

foreach($table as $row) {
    if ( !isset($items_dict[$row[0]])) $items_dict[$row[0]] = array();
    array_push($items_dict[$row[0]], $row[1]);
}



echo items($title, $items_dict, $my_items_arr);

?>