<?php
/**
 * This view has additional javascript requirements.
 * See public/themes/bootstrap/layouts/main.php
 */
?>
<link href='https://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css'/>
<link href='<?php echo Yii::app()->baseUrl; ?>/css/swagger-ui-screen.css' media='screen' rel='stylesheet' type='text/css'/>

<script type="text/javascript">
    $(function() {
        window.swaggerUi = new SwaggerUi({
            url: "<?php echo $swaggerSpecUrl; ?>",
            dom_id: "swagger-ui-container",
            supportedSubmitMethods: ['get', 'post', 'put', 'delete'],
            onComplete: function(swaggerApi, swaggerUi) {
                log("Loaded SwaggerUI");
                $('pre code').each(function(i, e) {
                    hljs.highlightBlock(e)
                });
            },
            onFailure: function(data) {
                log("Unable to Load SwaggerUI");
            },
            docExpansion: "list"
        });
<?php
    if($authorization_type == 'api_key'){
?>
        $('#input_apiKey').change(function() {
            var key = $('#input_apiKey')[0].value;
            log("key: " + key);
            if (key && key.trim() != "") {
                log("added key " + key);
                window.authorizations.add("key",
                    new ApiKeyAuthorization("<?php echo CHtml::encode($authorization_config['param_name']); ?>",
                        key, "<?php echo CHtml::encode($authorization_config['param_type']); ?>"));
            }
        });

<?php
    }
?>
        window.swaggerUi.load();
    });
</script>
<?php
    if($error){
?>
<div class="alert alert-danger" id="applicationError">
    <strong>doh!</strong> <span id="applicationErrorMsg"><?php echo $error; ?></span>
</div>
<?php
    } else {
?>
<h1><?php echo $appName; ?> <small>API Playground</small></h1>
<?php
        if($isOwner){
?>
<a class="btn btn-primary btn-xs"
   style="display: inline-block; margin: 5px 0px 10px 0px"
   href="<?php echo Yii::app()->createUrl('/gen/'.$appId); ?>">
    <span class="glyphicon glyphicon-pencil"></span> Edit Application
</a>
<?php
        }
    }
?>
<div class="swagger-section">
    <div id='header'>
        <div class="swagger-ui-wrap">
            <a id="logo" href="http://swagger.wordnik.com">swagger</a>
            <form id='api_selector'>
                <div class='input' style="display: none;"><input type="hidden" id="input_baseUrl" name="baseUrl" type="text" value="<?php echo $swaggerSpecUrl; ?>"/></div>
<?php
    if($authorization_type == 'api_key'){
?>
                <div class='input'><input placeholder="<?php echo CHtml::encode($authorization_config['param_name']); ?>" id="input_apiKey" name="apiKey" type="text"/></div>
<?php
    }
?>
                <div class='input'><a id="explore" href="#">Explore</a></div>
            </form>
        </div>
    </div>

    <div id="message-bar" class="swagger-ui-wrap">&nbsp;</div>
    <div id="swagger-ui-container" class="swagger-ui-wrap"></div>
</div>