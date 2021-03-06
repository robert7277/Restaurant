<?php
$host='us-cdbr-east-04.cleardb.com';
$dbname='heroku_a794d8c2b9b962d';
$user='b28c7b4922770e';
$pass='1dc7b872';
$charset='utf8mb4';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
$db = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=' . $charset, $user, $pass, $options);


function display_restaurant_list($db) {
    $restaurants = $db->query("select RID, name, category, AID, image from restaurants");
        while ($entry = $restaurants->fetch()) {
            $r_address = $db->query('select street, city, state, zip, phone from address where AID = '.$entry['AID']);
            $a_temp = $r_address->fetch();
            ?>
            <div class="col">
                <a href="detail.php?id=<?=$entry['RID']?>" class="text-decoration-none">
                    <div class="card h-100">
                        <img src="<?=$entry['image']?>" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title text-dark"><?=$entry['name']?></h5>
                            <button class="btn btn-sm btn-color rounded-pill text-light"><?=$entry['category']?></button>
                            <p class="card-text text-muted pt-1"><?=$a_temp['street']?>, <?=$a_temp['city']?>, <?=$a_temp['state']?>
                                <?=$a_temp['zip']?></p>
                        </div>
                    </div>
                </a>
            </div>
<?php
        }
}

function display_menu($db, $id){
    $menu = $db->query('select MID, name, description, price, image from menu_items where RID = ' . $id);
        while($item = $menu->fetch()) {?>
            <div class="card mb-2" style="">
                 <div class="row g-1">
                    <div class="col-md-4">
                        <img src="<?=$item['image']?>" class="img-fluid rounded-start menu-thumbnail" alt="...">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"><?=$item['name']?></h5>
                            <p class="card-text mb-0"><?=$item['description']?></p>
                            <p class="card-text"><small class="text-muted"><?=$item['price']?></small></p>
                            <?php
                                if($_SESSION['is_logged'] > 0){
                                ?>
                                    <a href="add_cart.php?mid=<?=$item['MID']?>&rid=<?=$id?>"><button value="<?=$item['MID']?>" class="add-to-order btn btn-sm btn-color rounded-pill text-light">Add to order</button></a>
                                    <?php
                                }
                                    ?>
                        </div>
                    </div>
                </div>
            </div>
<?php
    }
}

function display_menu_visitor($db, $id){
    $menu = $db->query('select MID, name, description, price, image from menu_items where RID = ' . $id);
        while($item = $menu->fetch()) {?>
            <div class="card mb-2" style="">
                 <div class="row g-1">
                    <div class="col-md-4">
                        <img src="<?=$item['image']?>" class="img-fluid rounded-start menu-thumbnail" alt="...">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"><?=$item['name']?></h5>
                            <p class="card-text mb-0"><?=$item['description']?></p>
                            <p class="card-text"><small class="text-muted"><?=$item['price']?></small></p>
                            <a href="signin.php"><button class="add-to-order btn btn-sm btn-color rounded-pill text-light">Add to order</button></a>
                        </div>
                    </div>
                </div>
            </div>
<?php
    }
}

function display_menu_admin($db, $id){
    $menu = $db->query('select MID, name, description, price, image from menu_items where RID = ' . $id);
        while($item = $menu->fetch()) {?>
            <div class="card mb-2" style="">
                 <div class="row g-1">
                    <div class="col-md-4">
                        <img src="<?=$item['image']?>" class="img-fluid rounded-start menu-thumbnail" alt="...">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"><?=$item['name']?></h5>
                            <p class="card-text mb-0"><?=$item['description']?></p>
                            <p class="card-text"><small class="text-muted"><?=$item['price']?></small></p>
                            <a href="modify.php?id=<?=$id?>&mid=<?=$item['MID']?>"> <button class="btn btn-sm btn-warning rounded-pill">Update menu item</button></a>
                           <a href="remove.php?id=<?=$id?>&mid=<?=$item['MID']?>"><button class="btn btn-sm btn-danger rounded-pill">Delete menu item</button></a>
                        </div>
                    </div>
                </div>
            </div>
<?php
    }
    ?>

        <?php
}

function display_menu_in_cart($db, $id){
    $menu = $db->query('select MID, name, description, price, image from menu_items where RID = ' . $id);
        while($item = $menu->fetch()) {?>
            <div id="<?=$item['MID']?>" class="card mb-2 d-none">
                 <div class="row g-1">
                    <div class="col-md-4">
                        <img src="<?=$item['image']?>" class="img-fluid rounded-start menu-thumbnail" style="width: 5rem; height: 5rem;">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"><?=$item['name']?></h5>
                            <p class="card-text"><small class="text-muted"><?=$item['price']?></small></p>
                        </div>
                    </div>
                </div>
            </div>
<?php
    }
}

function display_restaurant_detail($db, $id): array
{
    $restaurant = $db->query('select name, category, AID, image from restaurants where RID = ' . $id);
    $r_temp = $restaurant->fetch();
    $r_address = $db->query('select street, city, state, zip, phone from address where AID = '.$r_temp['AID']);
    $a_temp = $r_address->fetch();
    $result = array();
    $result['name'] = $r_temp['name'];
    $result['category'] = $r_temp['category'];
    $result['image'] = $r_temp['image'];
    $result['street'] = $a_temp['street'];
    $result['city'] = $a_temp['city'];
    $result['state'] = $a_temp['state'];
    $result['zip'] = $a_temp['zip'];
    $result['phone'] = $a_temp['phone'];
    $result['AID'] = $r_temp['AID'];
    return $result;
}

function create_user($user_array, $db){
    $q = "insert into address(AID, street, city, state, zip, phone) values(null, ?, ?, ?, ?, ?)";
    $var = $db->prepare($q);
    $var->execute([$user_array['street'], $user_array['city'], $user_array['state'], $user_array['zip'], $user_array['phone']]);
    $AID = $db->lastInsertId();
    //$db->query("insert into auth(pid, password) values(null, '".$user_array['password']."')");
    //$PID = $db->lastInsertID();
    $x = "insert into users(uid, aid, password, name, email, isAuth) values(null, ?, ?, ?, ?, 0)";
    $stmt = $db ->prepare($x);
    $stmt->execute([$AID, $user_array['password'], $user_array['name'], $user_array['email']]);
}

function check_if_exists($db, $table, $element, $value): bool{
    //echo "select * from ".$table." where '".$element."' = '".$value."'";
    //die();
    $q = "select * from $table where $element = ? ";
    $temp = $db->prepare($q);
    $temp->execute([$value]);
    $result = $temp->rowcount();
    return $result > 0;
}

function check_password($db, $email, $user_password): bool{
    $q = "select password from users where email = ?";
    $temp = $db->prepare($q);
    $temp->execute([$email]);
    $password = $temp->fetch();
    //print_r($password);
    if (password_verify($user_password, $password['password'])) {
        //echo "Password is correct.";
        return true;
    } else {
        //echo "Password is not correct";
        return false;
    }
}

function get_name_by_email($db, $email): string {
    $q = "select name from users where email = ?";
    $temp = $db->prepare($q);
    $temp->execute([$email]);
    return $temp->fetch()['name'];
}

function get_uid($db, $email): string {
    $uid = $db->query("select UID from users where email = '".$email."'");
    return $uid->fetch()['UID'];
}

function get_user($db, $uid) {
    $temp = $db->query("select aid, name, email from users where uid = ".$uid."");
    $user = $temp->fetch();
    $temp = $db->query("select street, city, state, zip, phone from address where aid = ".$user['aid']."");
    $user_address = $temp->fetch();
    $user['street'] = $user_address['street'];
    $user['city'] = $user_address['city'];
    $user['state'] = $user_address['state'];
    $user['zip'] = $user_address['zip'];
    $user['phone'] = $user_address['phone'];
    return $user;
}

function check_is_admin($db, $uid): bool {
    $temp = $db->query("select isAuth from users where uid = ".$uid);
    $user = $temp->fetch();
    if ($user['isAuth'] == 1) return true; else return false;
}

function checks_for_order($db, $UID): bool{
    $order = $db->query("select count(*) as num_orders from order_list where UID = '".$UID."' and is_complete = 0");
    $num = $order->fetch();
    return($num['num_orders'] > 0);
}

function get_oid($db, $UID):int {
    $oid = $db->query("select OID from order_list where UID = '".$UID."' and is_complete = 0");
    $temp = $oid->fetch();
    print_r($temp);
    return (int)$temp['OID'];
}
function add_to_order($db, $MID){
    $count = $db->query("select count(*) as num from order_items where OID = '".$_SESSION['oid']."' and MID = '".$MID."'");
    $temp = $count->fetch();
    print_r($temp);
    if($temp['num'] == 0){
        $db->query("insert into order_items(OID, MID, amount) VALUES ('".$_SESSION['oid']."', '".$MID."', 1)");
    }
    else{
        $db->query("update order_items set amount = amount + 1 where OID = '".$_SESSION['oid']."' and MID = '".$MID."'");
    }
}

function create_oid($db, $UID): int{
    $db->query("insert into order_list(oid, uid, is_complete) values (null, '".$UID."', 0)");
    return $db->lastInsertId();
}

function display_cart($db){
    $cart = $db->query("select name, RID, menu_items.MID, image, price, amount from menu_items join order_items oi on menu_items.MID = oi.MID and OID ='".$_SESSION['oid']."'");
    if($cart->rowCount() > 0){
        $array = [];
        while($item = $cart->fetch()){
            $array[]=$item;
        }
        return $array;
    }
    else return null;
}
function remove_item($db, $mid, $amount = null){
    if($amount == null){
        $db->query("delete from order_items where MID = '".$mid."' and OID = '".$_SESSION['oid']."'");
    }
    else{
        $db->query("update order_items set amount = amount - '".$amount."' where MID = '".$mid."' and OID = ".$_SESSION['oid']);
    }
}
function close_order($db, $oid){
    $db->query("update order_list set is_complete = 1 where OID = ".$oid);
    $_SESSION['oid'] = create_oid($db, $_SESSION['uid']);
}

function get_aid($db): int{
    $aid = $db->query("select AID from users where UID = ".$_SESSION['uid']);
    $temp = $aid->fetch();
    return (int)$temp;
}
function update_user_address($db, $array){
    $aid = get_aid($db);
    $q = "update address set street = ?, city = ?, state = ?, zip = ?, phone = ? where AID = ".$aid;
    $temp = $db->prepare($q);
    $temp->execute([$array['street'], $array['city'], $array['state'], $array['zip'], $array['phone']]);
}

function update_user($db, $array){
    update_user_address($db, $array);
    $q = "update users set password = ?, name = ?, email = ? where UID =".$_SESSION['uid'];
    $temp = $db->prepare($q);
    $temp->execute([$array['password'], $array['name'], $array['email']]);
}

function add_menu_item($db, $menu_item) {
    echo "<pre>";
    print_r($menu_item);
    echo "insert into menu_items(mid, rid, name, description, price, image) values(null, ".$menu_item['rid'].",
    '".$menu_item['name']."', '".$menu_item['description']."', '".$menu_item['price']."', '".$menu_item['image']."')";
    //die();
    $db->query("insert into menu_items(mid, rid, name, description, price, image) values(null, ".$menu_item['rid'].",
    '".$menu_item['name']."', '".$menu_item['description']."', '".$menu_item['price']."', '".$menu_item['image']."')");
}

function add_restaurant($db, $restaurant) {
    echo "<pre>";
    $db->query("insert into address(AID, street, city, state, zip, phone) values(null, '".$restaurant['street']."', 
    '".$restaurant['city']."', '".$restaurant['state']."','".$restaurant['zip']."', '".$restaurant['phone']."')");
    $aid = $db->lastInsertId();
    echo "insert into restaurants(rid, name, category, aid, image) values(null, '".$restaurant['name']."',
    '".$restaurant['category']."', ".$aid.", '".$restaurant['image']."')";
    $db->query("insert into restaurants(rid, name, category, aid, image) values(null, '".$restaurant['name']."',
    '".$restaurant['category']."', ".$aid.", '".$restaurant['image']."')");
}

function update_restaurant($db, $restaurant) {
    $db->query("update address set street = '".$restaurant['street']."', city = '".$restaurant['city']."',
     state = '".$restaurant['state']."', zip = '".$restaurant['zip']."', phone = '".$restaurant['phone']."' where 
     AID =".$restaurant['AID']);
    $db->query("update restaurants set name = '".$restaurant['name']."', category = '".$restaurant['category']."',
     image = '".$restaurant['image']."' where rid = ".$restaurant['RID']);
}

function delete_restaurant($db, $restaurant) {
    $db->query("delete from address where AID = ".$restaurant['AID']);
    $db->query("delete from restaurants where RID = ".$restaurant['RID']);
    $db->query("delete from menu_items where RID = ".$restaurant['RID']);
    $db->query("update order_list set is_complete = 1 where is_complete = 0");
}
function update_menu_item($db, $item){
    $db->query("update menu_items set name = '".$item['name']."', description = '".$item['description']."',
     price = '".$item['price']."', image = '".$item['image']."' where MID = ".$item['mid']);
}
function delete_menu_item($db, $mid){
    $db->query("delete from menu_items where MID = ".$mid);
    $db->query("delete from order_items where MID = ".$mid);
}
function get_menu_item($db, $mid){
    $menu = $db->query("select * from menu_items where MID = ".(int)$mid );
    return $menu->fetch();
}
function delete_user($db, $uid){
    $temp = $db->query("select * from users where UID = ".(int)$uid);
    $aid = $temp->fetch();
    $db->query("delete from users where UID = ".(int)$uid);
    $db->query("delete from address where AID = ".(int)$aid['AID']);
}