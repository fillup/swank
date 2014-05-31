<?php
if(!is_null($response->id)){
    $title = "Update Response";
} else {
    $title = "Define New Response";
}
?>
<form class="form-horizontal" role="form" id="formUpdateResponse-<?php echo $id; ?>">
    <input type='hidden' name='response_id' value='<?php echo $response->id; ?>' />
    <input type="hidden" name="operation_id" id="operation_id" value="<?php echo $response->operation_id; ?>" />
    <div class='form-group' id='divName'>
        <label for='inputName' class='col-lg-2 control-label'>Code</label>
        <div class='col-lg-4'>
            <input type='text' name='code' class='form-control' placeholder='200' <?php if(!is_null($response->code)){ echo "value='".CHtml::encode($response->code)."'"; } ?> />
        </div>
    </div>
    <div class="form-group" id="divMessage">
        <label for="inputMessage" class="col-lg-2 control-label">Message</label>
        <div class="col-lg-8">
            <input type="text" name="message" id="inputMessage" class="form-control" rows="3"
                   placeholder="Describe what this response is for."
                   value="<?php if(!is_null($response->message)){ echo CHtml::encode($response->message); } ?>" />
        </div>
    </div>
    <div class="form-group" id="divResponseModel">
        <label for="inputResponseModel" class="col-lg-2 control-label">Response Data Type</label>
        <div class="col-lg-4">
            <select name='responseModel' id='inputResponseModel' class='form-control'>
                <?php
                foreach($response->primitiveDataTypes as $type){
                    ?>
                    <option value='<?php echo $type; ?>' <?php if($response->responseModel == $type){ echo "selected='selected'"; } ?>>
                        <?php echo $type; ?>
                    </option>
                <?php
                }
                ?>
            </select>
        </div>
    </div>
</form>