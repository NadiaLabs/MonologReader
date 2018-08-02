<?php !defined('MONOLOG_READER') && die(0); ?>

<div class="container">
    <div class="offset-md-2 col-md-8 offset-lg-3 col-lg-6 mt-5">
    <?php if (!empty($viewData['error'])) { ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $viewData['error']; ?>
        </div>
    <?php } ?>
    <?php if (!empty($viewData['success'])) { ?>
        <div class="alert alert-success" role="alert">
            <?php echo $viewData['success']; ?>
        </div>
    <?php } ?>

        <h1 class="mb-5">Login with password</h1>

        <form method="post">
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" aria-describedby="password" required />
            </div>
            <div class="form-group">
                <button class="btn btn-primary">Login</button>
            </div>
        </form>
    </div>
</div>
