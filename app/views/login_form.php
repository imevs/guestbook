<?/** @var $gb_user User */?>
<? if (!$gb_user->isAuthenticated()): ?>
    <form class="navbar-form pull-right" action="index.php?action=login" method="post">
        <input class="span2" type="text" placeholder="Login" name="username">
        <input class="span2" type="password" placeholder="Password" name="password">
        <button type="submit" class="btn">Sign in</button>
    </form>
<? else: ?>
<ul class="nav pull-right">
    <li class="active"><a href="index.php?action=logout">Logout</a></li>
</ul>

<? endif;?>