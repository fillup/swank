<?php
    if(!is_null($parameter->id)){
        $title = "Update Parameter";
    } else {
        $title = "Define New Parameter";
    }
?>
<form class="form-horizontal" role="form" id="formUpdateParameter-<?php echo $id; ?>">
    <input type='hidden' name='parameter_id' value='<?php echo $parameter->id; ?>' />
    <input type="hidden" name="operation_id" id="operation_id" value="<?php echo $parameter->operation_id; ?>" />
    <div class='form-group' id='divName'>
        <label for='inputName' class='col-lg-2 control-label'>Name</label>
        <div class='col-lg-4'>
            <input type='text' name='name' class='form-control' placeholder='Name' <?php if(!is_null($parameter->name)){ echo "value='".CHtml::encode($parameter->name)."'"; } ?> />
        </div>
    </div>
    <div class="form-group" id="divParamType">
        <label for="inputParamType" class="col-lg-2 control-label">Parameter Type</label>
        <div class="col-lg-4">
            <select name='paramType' id='inputParamType' class='form-control'>
                <?php
                    foreach(array_keys($parameter->validParamTypes) as $paramType){
                ?>
                <option value='<?php echo $paramType; ?>' <?php if($parameter->paramType == $paramType){ echo "selected='selected'"; } ?>>
                    <?php echo $paramType; ?>
                </option>
                <?php
                    }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group" id="divDataType">
        <label for="inputDataType" class="col-lg-2 control-label">Data Type</label>
        <div class="col-lg-4">
            <select name='dataType' id='inputDataType' class='form-control'>
                <?php
                    foreach($parameter->primitiveDataTypes as $type){
                ?>
                <option value='<?php echo $type; ?>' <?php if($parameter->dataType == $type){ echo "selected='selected'"; } ?>>
                    <?php echo $type; ?>
                </option>
                <?php
                    }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group" id="divRequired">
        <label for="inputRequired" class="col-lg-2 control-label">Required</label>
        <div class="col-lg-4">
            <label class="radio-inline">
              <input type="radio" name="required" id="required1" value="1" <?php if($parameter->required == '1'){ echo "checked='checked'"; } ?>> Yes
            </label>
            <label class="radio-inline">
              <input type="radio" name="required" id="required0" value="0" <?php if($parameter->required == '0'){ echo "checked='checked'"; } ?>> No
            </label>
        </div>
    </div>
    <div class="form-group" id="divDescription">
        <label for="inputDescription" class="col-lg-2 control-label">Description</label>
        <div class="col-lg-8">
            <textarea name="description" 
                      id="inputDescription" 
                      class="form-control" rows="3" 
                      placeholder="Describe what this parameter is for."><?php if(!is_null($parameter->description)){ echo CHtml::encode($parameter->description); } ?></textarea>
        </div>
    </div>
</form>