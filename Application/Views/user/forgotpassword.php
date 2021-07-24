<div class="container text-center">
    <form class="form-login" id="formForgotPassword" method="POST" action="<?= $BASE_URL; ?>/user/forgotpassword">
        <img class="mb-4" src="<?= $BASE_URL; ?>/assets/images/logo-frameworkitto.png" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Password Recovery</h1>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input name="email" type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
        <div id="userNotificationArea" class="alert alert-<?= @$notificationType; ?>"><?= @$notificationMessage; ?></div>
        <button id="recoverButton" class="btn btn-lg btn-success btn-block" type="submit" role="submit">Recover Password</button>
        <a href="<?= $BASE_URL; ?>/user/login" class="btn btn-outline-primary btn-block" type="button" role="button">Back to Login</a>
    </form>
</div>
