<?php !defined('MONOLOG_READER') && die(0); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Monolog Reader</title>

    <link rel="stylesheet" href="/assets/css/bootstrap.min.css" />
    <script type="text/javascript" src="/assets/js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="/assets/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<nav class="navbar navbar-expand navbar-dark bg-dark">
    <a class="navbar-brand" href="/">MonologReader</a>

    <ul class="navbar-nav mr-auto">

    </ul>

    <div class="form-inline">
        <div class="form-group mr-2">
            <select id="log-key" class="form-control">
                <option value="">-- Choose a log --</option>
            <?php foreach ($viewData['logKeys'] as $logKey) { ?>
                <option value="<?php echo $logKey; ?>" <?php echo $viewData['selectedLogKey'] === $logKey ? 'selected' : ''; ?>>
                    <?php echo $logKey; ?>
                </option>
            <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <a href="/?c=edit-log-config" class="btn btn-outline-light mr-2">Add Log</a>
        </div>
    </div>
</nav>

<?php include $viewFile; ?>

<script type="text/javascript">
    $('#log-key').change(function() {
        var key = $(this).val();

        if (key) {
            location.href = '/?c=logs&key=' + key;
        }
    });
</script>
</body>
</html>
