<?php
    if($application->authorization_config){
        $authConfig = json_decode($application->authorization_config,true);
        if(!isset($authConfig['param_name'])){
            $authConfig['param_name'] = '';
        }
        if(!isset($authConfig['param_type'])){
            $authConfig['param_type'] = '';
        }
    } else {
        $authConfig = array('param_name' => '', 'param_type' => '');
    }
?>
<div class="form-group" id="divApiKeyParamName">
    <label for="inputApiKeyParamName" class="col-lg-2 control-label">Parameter Name</label>
    <div class="col-lg-4">
        <input type="text" class="form-control" name="apiKeyParamName"
               id="inputApiKeyParamName" placeholder="api_key"
               value="<?php echo CHtml::encode($authConfig['param_name']); ?>">
    </div>
    <span class="help-block">
        This will be added to API calls as either a query parameter or a header parameter.
    </span>
</div>
<div class="form-group" id="divApiKeyParamType">
    <label for="inputApiKeyParamType" class="col-lg-2 control-label">Parameter Type</label>
    <div class="col-lg-4">
        <input type="text" class="form-control" name="apiKeyParamType"
               id="inputApiKeyParamType" placeholder="query"
               value="<?php echo CHtml::encode($authConfig['param_type']); ?>">
    </div>
    <span class="help-block">
        Either <em>query</em> or <em>header</em>.
    </span>
</div>
<script type="text/javascript">
    function getAuthorizationConfig(){
        if($('#inputApiKeyParamName').val() == '' || $('#inputApiKeyParamType').val() == ''){
            return false;
        } else {
            return {
                param_name: $('#inputApiKeyParamName').val(),
                param_type: $('#inputApiKeyParamType').val()
            };
        }
    }
</script>