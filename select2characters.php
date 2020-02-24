<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/_functions.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/_db.php');

$link = Db::getDbLink();

$q_characters_all = "SELECT COUNT(*) FROM characters";
$r_characters_all = mysqli_query($link, $q_characters_all);
$characters_count_all = mysqli_fetch_row($r_characters_all)[0];
$items_on_page = 10;
$num_pages = ceil($characters_count_all / $items_on_page);
$page = (isset($_GET['page'])) ? $_GET['page'] : 1;
$page = max(1, min($num_pages, intval($page)));

// сортировка
$sort = isset($_GET["sort"]) ? $_GET["sort"] : 'id';
$sort_by = isset($_GET["sort_by"]) ? 1 : 0;
if ($sort_by == 1) {
    $asc = " DESC";
    $desc = " ASC";
    $up = 0;
    $down = 1;
} else {
    $asc = " ASC";
    $desc = " DESC";
    $up = 1;
    $down = 0;
}
$sorts = [
    'id' => ['id ' . $asc, $up],
    'first_name' => ['first_name ' . $asc, $up],
    'second_name' => ['second_name ' . $asc, $up],
    'gender' => ['gender ' . $asc, $up],
    'age' => ['age ' . $asc, $up]
];
$par_sort = ' ORDER BY ' . $sorts[$sort][0];
if ($sorts[$sort][1] == 1) {
    $order = '&#9650;';
    $sortstr = 'возрастающем';
} else {
    $order = '&#9660;';
    $sortstr = 'убывающем';
}


$q_characters = 'SELECT * FROM characters' . $par_sort . ' LIMIT ' . ($page - 1) * $items_on_page . ', ' . $items_on_page;

$r_characters = mysqli_query($link, $q_characters);
$characters_count = mysqli_num_rows($r_characters);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Fallout</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body bgcolor="#f5f5f5">

<nav><a href="/index.php">Main</a> | Characters</nav>
<article>
    <div style="width:670px;" class="my-padding">
        <?php
        if (isset($_SESSION['success'])) {
            echo '<div class="success">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }
        ?>
    </div>

    <h2>Characters list</h2>
    <div class="my-padding">
        <?php
        if (isset($_SESSION['success'])) {
            echo '<div class="success">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }
        ?>
        <div style="width: 720px;">
            <form method="POST" action="/test.php">
            <?php
            $url_params = ['page'];
            ?>
            <table cellspacing="0" border="1" class="my-table">
                <tr>
                    <th width="50">Select</th>
                    <th width="200"><a href="/select2characters.php?sort=first_name<?php echo getParStr($url_params);
                        if ($sort == 'first_name' && !$sort_by) echo '&sort_by=1'; ?>">Name<?php if ($sort == 'first_name') echo ' <span title="Отсортировано в ' . $sortstr . ' порядке">' . $order . '</span>'; ?></a>
                    </th>
                    <th width="200"><a href="/select2characters.php?sort=second_name<?php echo getParStr($url_params);
                        if ($sort == 'second_name' && !$sort_by) echo '&sort_by=1'; ?>">Last
                            name<?php if ($sort == 'second_name') echo ' <span title="Отсортировано в ' . $sortstr . ' порядке">' . $order . '</span>'; ?></a>
                    </th>
                    <th width="150"><a href="/select2characters.php?sort=gender<?php echo getParStr($url_params);
                        if ($sort == 'gender' && !$sort_by) echo '&sort_by=1'; ?>">Gender<?php if ($sort == 'gender') echo ' <span title="Отсортировано в ' . $sortstr . ' порядке">' . $order . '</span>'; ?></a>
                    </th>
                    <th width="50"><a href="/select2characters.php?sort=age<?php echo getParStr($url_params);
                        if ($sort == 'age' && !$sort_by) echo '&sort_by=1'; ?>">Age<?php if ($sort == 'age') echo ' <span title="Отсортировано в ' . $sortstr . ' порядке">' . $order . '</span>'; ?></a>
                    </th>
                    <th width="20">S</th>
                    <th width="20">P</th>
                    <th width="20">E</th>
                    <th width="20">C</th>
                    <th width="20">I</th>
                    <th width="20">A</th>
                    <th width="20">L</th>
                    <th width="70"></th>
                </tr>
                <?php
                for ($i = 0; $i < $characters_count; $i++) {
                    $row = mysqli_fetch_assoc($r_characters);

                    echo '<tr>
                            <td><INPUT name="iid[]" type="checkbox" value=' . $row['id'] . '></td>
                            <td>' . $row['first_name'] . '</td>
                            <td>' . $row['second_name'] . '</td>
                            <td>' . $row['gender'] . '</td>
                            <td>' . $row['age'] . '</td>
                            <td>' . $row['s'] . '</td>
                            <td>' . $row['p'] . '</td>
                            <td>' . $row['e'] . '</td>
                            <td>' . $row['c'] . '</td>
                            <td>' . $row['i'] . '</td>
                            <td>' . $row['a'] . '</td>
                            <td>' . $row['l'] . '</td>
                            <td>
                                <a href="#" onclick="if(window.confirm(\'Delete?\')) { window.location = \'/delete.php?id=' . htmlspecialchars($row['id']) . '\'; } return false;">delete</a>
                            </td>
                    </tr>';

                }
                ?>
                <INPUT type="submit" value="Отправить">
                </form>
            </table>

            <?php if ($num_pages > 1) : ?>
                <div style="text-align: center;">
                    Страница:
                    <?php
                    $url_params = ['sort', 'sort_by'];
                    $url = 'select2characters.php?' . getParStr($url_params);
                    makePager($page, $num_pages, 2, $url);
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</article>
</body>
</html>