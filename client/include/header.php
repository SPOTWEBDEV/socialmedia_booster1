<?php
// System notifications for current user
$notif_stmt = $connection->prepare("
SELECT * 
FROM notifications
WHERE type='system' AND user_id = ?
ORDER BY created_at DESC
LIMIT 5
");

$notif_stmt->bind_param("i", $id);
$notif_stmt->execute();
$notifications = $notif_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<style>



.header-right{
    display:flex;
    align-items:center;
    gap:20px;
}

/* dropdown container */
.notification,
.profile_log{
    position:relative;
    cursor:pointer;
}

/* dropdown menu */
.dropdown-menu{
    display:none;
    position:absolute;
    right:0;
    background:#fff;
    width:300px;
    border-radius:6px;
    box-shadow:0 5px 20px rgba(0,0,0,0.15);
    z-index:999;
    padding:15px;
    margin:10px 0px 0px 0px !important;
}

.dropdown-menu.show{
    display:block;
}

.notify-bell,
.user{
    cursor:pointer;
}

/* notification list */
.lists a{
    display:block;
    text-decoration:none;
    padding:8px 0;
    color:#333;
}

.lists a:hover{
    background:#f7f7f7;
}

/* profile menu */
.dropdown-item{
    display:block;
    padding:10px;
    text-decoration:none;
    color:#333;
}

.dropdown-item:hover{
    background:#f5f5f5;
}

.user-email{
    border-bottom:1px solid #eee;
    padding-bottom:10px;
    margin-bottom:10px;
}

</style>


<div class="header">
<div class="container">
<div class="row">
<div class="col-xxl-12">

<div class="header-content">

<!-- LEFT -->
<div class="header-left">

<div class="brand-logo">
<a class="mini-logo" href="#">
<img src="<?php echo $domain ?>assets/images/logo/favicon.png" width="60">
</a>
</div>

</div>

<!-- RIGHT -->
<div class="header-right">

<!-- DARK MODE -->
<div class="dark-light-toggle" onclick="themeToggle()">
<span class="dark"><i class="fi fi-rr-eclipse-alt"></i></span>
<span class="light"><i class="fi fi-rr-eclipse-alt"></i></span>
</div>


<!-- NOTIFICATION -->
<div class="notification" onclick="toggleDropdown('notificationMenu')">

<div class="notify-bell icon-menu">
<span><i class="fi fi-rs-bells"></i></span>
</div>

<div class="dropdown-menu" id="notificationMenu">

<h4>Recent Notification</h4>

<div class="lists">

<?php if (!empty($notifications)): ?>
<?php foreach ($notifications as $notif): ?>

<?php
$icon_class = 'fi fi-bs-check';
$icon_color = 'success';

if (strpos(strtolower($notif['message']), 'failed') !== false) {
    $icon_class = 'fi fi-sr-cross-small';
    $icon_color = 'fail';
} elseif (strpos(strtolower($notif['message']), 'pending') !== false) {
    $icon_class = 'fi fi-rr-triangle-warning';
    $icon_color = 'pending';
}
?>

<a href="#">
<div class="d-flex align-items-center">

<span class="me-3 icon <?= $icon_color ?>">
<i class="<?= $icon_class ?>"></i>
</span>

<div>
<p><?= htmlspecialchars($notif['message']) ?></p>
<span><?= date("Y-m-d H:i:s", strtotime($notif['created_at'])) ?></span>
</div>

</div>
</a>

<?php endforeach; ?>
<?php else: ?>

<p class="text-center text-muted">No notifications found</p>

<?php endif; ?>

</div>
</div>
</div>


<!-- PROFILE -->
<div class="profile_log" onclick="toggleDropdown('profileMenu')">

<div class="user icon-menu active">
<span><i class="fi fi-rr-user"></i></span>
</div>

<div class="dropdown-menu" id="profileMenu">

<div class="user-email">
<div class="user">

<span class="thumb">
<img class="rounded-full" src="<?php echo $domain ?>client/images/avatar.svg">
</span>

<div class="user-info">
<h5><?= ($username != '' || $username != null) ? $fullname : $username ?></h5>
<span><?php echo $email ?></span>
</div>

</div>
</div>

<a class="dropdown-item" href="<?php echo $domain ?>client/dashboard/">
<i class="fi fi-rr-user"></i> Dashboard
</a>

<a class="dropdown-item" href="<?php echo $domain ?>client/deposits/">
<i class="fi fi-rr-wallet"></i> Deposit
</a>

<a class="dropdown-item" href="<?php echo $domain ?>client/setting/">
<i class="fi fi-rr-settings"></i> Settings
</a>

<a class="dropdown-item logout" href="<?php echo $domain ?>client/logout">
<i class="fi fi-bs-sign-out-alt"></i> Logout
</a>

</div>
</div>

</div>
</div>
</div>
</div>
</div>
</div>

<?php include('../../include/support.php'); ?>


<script>

/* toggle dropdown */
function toggleDropdown(id){

document.querySelectorAll(".dropdown-menu").forEach(menu=>{
if(menu.id !== id){
menu.classList.remove("show");
}
});

document.getElementById(id).classList.toggle("show");

}


/* close when clicking outside */
document.addEventListener("click", function(e){

if(!e.target.closest(".notification") && !e.target.closest(".profile_log")){
document.querySelectorAll(".dropdown-menu").forEach(menu=>{
menu.classList.remove("show");
});
}

});

</script>