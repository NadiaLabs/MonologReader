<?php !defined('MONOLOG_READER') && die(0); ?>

<div class="container">
    <div class="offset-3 col-6 mt-5">
    <?php if (!empty($viewData['error'])) { ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $viewData['error']; ?>
        </div>
    <?php } ?>

        <h2><?php echo $viewData['title']; ?></h2>

        <form method="post">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" aria-describedby="name" required
                       value="<?php echo $viewData['logConfig']['name']; ?>" />
            </div>
            <div class="form-group">
                <label for="group">Group</label>
                <input type="text" class="form-control" id="group" name="group" aria-describedby="group" required
                       value="<?php echo $viewData['logConfig']['group']; ?>" />
            </div>
            <div class="form-group">
                <label for="path">Path</label>
                <input type="text" class="form-control" id="path" name="path" aria-describedby="path" required
                       value="<?php echo $viewData['logConfig']['path']; ?>" />
            </div>
            <div class="form-group">
                <input type="hidden" name="old_key" value="<?php echo $viewData['oldKey']; ?>" />

                <button class="btn btn-primary" name="action" value="<?php echo $viewData['action']; ?>">
                    <?php echo ucfirst($viewData['action']); ?>
                </button>
            </div>
        </form>
    </div>
</div>
