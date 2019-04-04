<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>点点支付提示信息</title>

    <link rel="shortcut icon" href="favicon.ico">
    <link href="__admin__/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="__admin__/css/font-awesome.css?v=4.4.0" rel="stylesheet">
    <link href="__admin__/css/animate.css" rel="stylesheet">
    <link href="__admin__/css/style.css?v=4.1.0" rel="stylesheet">
</head>

<body class="gray-bg">
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-sm-12">
            <div class="middle-box animated">
                <div class="col-sm-12">
                    <?php switch ($code) {?>
                    <?php case 1:?>
                    <div class="panel panel-info">
                    <?php break;?>
                    <?php case 0:?>
                    <div class="panel panel-danger">
                    <?php break;?>
                    <?php } ?>
                    <div class="panel-heading">
                        <i class="fa fa-spinner fa-spin"></i> &nbsp;提示信息
                    </div>
                    <div class="panel-body">
                        <p style="font-size: 1.2em"><?php echo(strip_tags($msg));?></p>
                        <?php if($wait >= 0){ ?>
                        <hr>
                        <p class="jump">
                            页面将在 <b id="wait"><?php echo($wait);?></b> 秒后自动 <a id="href" href="<?php echo($url);?>">跳转</a> ！
                        </p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- 全局js -->
<script src="/assets/admin/js/jquery.min.js?v=2.1.4"></script>
<script src="/assets/admin/js/bootstrap.min.js?v=3.3.6"></script>
<?php if($wait >= 0){ ?>
<script type="text/javascript">
    (function(){
        var wait = document.getElementById('wait'),
            href = document.getElementById('href').href;
        var interval = setInterval(function(){
            var time = --wait.innerHTML;
            if(time <= 0) {
                location.href = href;
                clearInterval(interval);
            };
        }, 1000);
    })();
</script>
<?php } ?>
</body>
</html>
