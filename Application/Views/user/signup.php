
<div class="container text-center">
    <form id="formSignUp" class="form-login" method="POST" action="<?= $BASE_URL; ?>/user/signup">
        <img class="mb-4" src="<?= $BASE_URL; ?>/assets/images/logo-frameworkitto.png" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Free Sign Up</h1>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input name="email" type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <label for="confirmPassword" class="sr-only">Retype password</label>
        <input name="confirm_password" type="password" id="inputConfirmPassword" class="form-control" placeholder="Retype password" required>
        <div id="userNotificationArea" class="alert alert-<?= @$notificationType; ?>"><?= @$notificationMessage; ?></div>
        <button id="signupButton" class="btn btn-lg btn-success btn-block" type="submit" role="submit">Create Account</button>
        <a href="<?= $BASE_URL; ?>/user/login" class="btn btn-outline-primary btn-block" type="button" role="button">I already have an account</a>
    </form>
</div>
