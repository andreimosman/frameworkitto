<div class="container text-center">
    <form class="form-login" id="formLogin" method="POST" action="<?= $BASE_URL; ?>/user/login">
        <img class="mb-4" src="<?= $BASE_URL; ?>/assets/images/logo-frameworkitto.png" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input name="email" type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <div id="userNotificationArea" class="alert alert-<?= @$notificationType; ?>"><?= @$notificationMessage; ?></div>
        <button id="loginButton" class="btn btn-lg btn-primary btn-block" type="submit" role="submit">Login</button>
        <a class="btn text-right" href="<?= $BASE_URL; ?>/user/forgotpassword"><small>forgot password &nbsp; </small></a>
        <a href="<?= $BASE_URL; ?>/user/signup" class="btn btn-outline-secondary btn-block" type="button" role="button">Create a free account</a>
    </form>
</div>
