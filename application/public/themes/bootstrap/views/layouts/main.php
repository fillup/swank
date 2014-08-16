<?php
Yii::app()->bootstrap->register();
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->baseUrl . "/js/handlebars-v1.3.0.js", CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->baseUrl . "/js/swank.js", CClientScript::POS_END);
if($this->route == 'ui/index'){
    $cs->registerScriptFile(Yii::app()->baseUrl . '/js/swagger/shred.bundle.js', CClientScript::POS_END);
    $cs->registerScriptFile(Yii::app()->baseUrl . '/js/swagger/jquery.slideto.min.js', CClientScript::POS_END);
    $cs->registerScriptFile(Yii::app()->baseUrl . '/js/swagger/jquery.wiggle.min.js', CClientScript::POS_END);
    $cs->registerScriptFile(Yii::app()->theme->baseUrl . "/assets/js/jquery.ba-bbq.min.js", CClientScript::POS_END);
    $cs->registerScriptFile(Yii::app()->baseUrl . '/js/swagger/underscore-min.js', CClientScript::POS_END);
    $cs->registerScriptFile(Yii::app()->baseUrl . '/js/swagger/backbone-min.js', CClientScript::POS_END);
    $cs->registerScriptFile(Yii::app()->baseUrl . '/js/swagger/swagger.js', CClientScript::POS_END);
    $cs->registerScriptFile(Yii::app()->baseUrl . '/js/swagger/swagger-ui.js', CClientScript::POS_END);
    $cs->registerScriptFile(Yii::app()->baseUrl . '/js/swagger/highlight.7.3.pack.js', CClientScript::POS_END);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- route: <?php echo $this->route; ?> -->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="<?php echo Yii::app()->theme->baseUrl . "/assets/js/html5shiv.js"; ?>"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl . "/assets/js/respond.min.js"; ?>"></script>
    <![endif]-->

    <!-- Javascript -->
    <script>var baseUrl = "<?php echo Yii::app()->baseUrl; ?>";</script>

    <!-- NOTE: Yii uses this title element for its asset manager, so keep it last -->
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
    <div class="container">
        <?php $this->widget('bootstrap.widgets.TbNavbar', array(
                'brandLabel' => 'Swank.io - Beta',
                'collapse' => true,
                'items' => array(
                    array(
                        'class' => 'bootstrap.widgets.TbNav',
                        'items' => array(
                            array('label'=>'My Apps', 'url'=>array('/me'), 'visible'=>!Yii::app()->user->isGuest),
                            array('label'=>'API Directory', 'url'=>array('/directory'),),
                        ),
                    ),
                    array(
                        'class' => 'bootstrap.widgets.TbNav',
                        'htmlOptions'=>array('class'=>'pull-right'),
                        'items'=>array(
                            array('label'=>'Fork Me', 'url'=>'http://github.com/fillup/swank'),
                            array('label'=>'Login with GitHub', 'url'=>array('/auth/login'),
                                'visible'=>Yii::app()->user->isGuest, ),
                            array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/auth/logout'), 'visible'=>!Yii::app()->user->isGuest)
                        ),
                    )
                ),
        )); ?>
    </div>

    <div class="container" style="padding-top: 30px;">

        <?php // NOTE: this does not use bootstrap's breadcrumbs component because CBreadcrumbs doesn't use UL/LI ?>
        <?php // You can implement it yourself or use Chris83's - http://www.yiiframework.com/extension/bootstrap/ ?>
        <?php if(isset($this->breadcrumbs)):?>
            <?php $this->widget('zii.widgets.CBreadcrumbs', array(
                'links'=>$this->breadcrumbs,
            )); ?>
        <?php endif?>

        <div id="main-content">
            <div class="row">
                <div class="col-md-12">
                    <?php $this->widget('bootstrap.widgets.TbAlert'); ?>
                </div>
            </div>
            <?php if (!$this->menu): ?>
            
                <div class="row">
                    <div class="col-lg-12">
                        <?php echo $content; ?>
                    </div>
                </div>

            <?php else: ?>

                <div class="row">
                    <div class="col-lg-10">
                        <?php echo $content; ?>
                    </div>

                    <div class="col-lg-2">
                        <div class="panel panel-info">
                            <div class="panel-heading">Operations</div>
                                <?php
                                $this->widget('zii.widgets.CMenu', array(
                                    'items'=>$this->menu,
                                    'htmlOptions'=>array('class'=>'nav nav-pills nav-stacked'),
                                ));
                                ?>
                        </div>
                    </div>
                </div>

            <?php endif; ?>


        </div> <!-- /#main-content -->

        <hr>

        <footer>
            <p>
                &copy; <?php echo date('Y',time()); ?> <?php echo Yii::app()->name; ?>. All Rights Reserved.<br/>
                Profiling: <?php echo round(Yii::getLogger()->getExecutionTime(),2); ?>s / <?php echo round(Yii::getLogger()->getMemoryUsage()/1048576,2); ?>mb
            </p>
        </footer>

    </div> <!-- /.container -->

</body>
</html>
