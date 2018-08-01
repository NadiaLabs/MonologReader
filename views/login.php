<?php !defined('MONOLOG_READER') && die(); ?>

<div class="container">
    <div class="offset-4 col-4 mt-5">
    <?php if (!empty($viewData['error'])) { ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $viewData['error']; ?>
        </div>
    <?php } ?>

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
