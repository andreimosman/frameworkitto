<?php
    /**
     * You can use PHP here!
     * 
     * Special vars such like $__ROUTE__ and $BASE_URL helps you to perform you job easier.
     * 
     */
?>
<nav class="navbar navbar-dark navbar-expand-lg bg-dark">
    <a class="navbar-brand" href="#">Frameworkitto</a>

    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item <?= $__ROUTE__['controller'] == 'home' ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= $BASE_URL; ?>/home/dashboard">Dashboard</a>
            </li>
            <li class="nav-item <?= $__ROUTE__['controller'] == 'gettingstart' ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= $BASE_URL; ?>/gettingstart">Getting Start</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $BASE_URL; ?>/user/logout">Logout</a>
            </li>
        </ul>
    </div>

</nav>

