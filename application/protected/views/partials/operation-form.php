<?php
    if(!is_null($operation->id)){
        $title = "Update Operation";
    } else {
        $title = "Define New Operation";
    }
?>
<form class="form-horizontal" role="form" id="formUpdateOperation-<?php echo $id; ?>">
    <input type='hidden' name='api_id' value='<?php echo $operation->api_id; ?>' />
    <input type="hidden" name="operation_id" id="operation_id" value="<?php echo $operation->id; ?>" />
    <div class="form-group" id="divMethod">
        <label for="inputMethod" class="col-lg-2 control-label">Method</label>
        <div class="col-lg-4">
            <select name='operationMethod' id='inputMethod' class='form-control'>
                <option value='GET' <?php if(!is_null($operation->method) && $operation->method == 'GET'){ echo "selected='selected'"; } ?>>GET</option>
                <option value='POST' <?php if(!is_null($operation->method) && $operation->method == 'POST'){ echo "selected='selected'"; } ?>>POST</option>
                <option value='PUT' <?php if(!is_null($operation->method) && $operation->method == 'PUT'){ echo "selected='selected'"; } ?>>PUT</option>
                <option value='DELETE' <?php if(!is_null($operation->method) && $operation->method == 'DELETE'){ echo "selected='selected'"; } ?>>DELETE</option>
                <option value='PATCH' <?php if(!is_null($operation->method) && $operation->method == 'PATCH'){ echo "selected='selected'"; } ?>>PATCH</option>
            </select>
        </div>
    </div>
    <div class='form-group' id='divNickname'>
        <label for='inputNickname' class='col-lg-2 control-label'>Nickname</label>
        <div class='col-lg-4'>
            <input type='text' name='nickname' class='form-control' placeholder='Nickname' <?php if(!is_null($operation->nickname)){ echo "value='".CHtml::encode($operation->nickname)."'"; } ?> />
        </div>
    </div>
    <div class="form-group" id="divType">
        <label for="inputType" class="col-lg-2 control-label">Response Type</label>
        <div class="col-lg-4">
            <select name='type' id='inputType' class='form-control'>
                <?php
                    foreach($operation->validTypes as $type){
                ?>
                    <option value='<?php echo $type; ?>' 
                        <?php if(!is_null($operation->type) && $operation->type == $type){ echo "selected='selected'"; } ?>>
                        <?php echo $type; ?>
                    </option>
                <?php
                    }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group" id="divSummary">
        <label for="inputSummary" class="col-lg-2 control-label">Summary</label>
        <div class="col-lg-8">
            <textarea name="summary" 
                      id="inputApiDescription" 
                      class="form-control" rows="3" 
                      placeholder="Describe what this operation does."><?php if(!is_null($operation->summary)){ echo CHtml::encode($operation->summary); } ?></textarea>
        </div>
    </div>
</form>