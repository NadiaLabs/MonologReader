<?php !defined('MONOLOG_READER') && die(0); ?>

<div class="container">
    <div class="offset-md-2 col-md-8 offset-lg-3 col-lg-6 mt-5">
        <h1 class="mb-5">Create password</h1>

        <?php require __DIR__ . '/flash-messages.php'; ?>

        <form method="post">
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" aria-describedby="password" required />
            </div>
            <div class="form-group">
                <label for="password">Repeat Password</label>
                <input type="password" class="form-control" id="password" name="password-repeat" aria-describedby="password" required />
            </div>
            <div class="form-group">
                <button class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
