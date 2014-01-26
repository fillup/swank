<?php
    if(!is_null($api->id)){
        $title = "Update API";
    } else {
        $title = "Define New API";
    }
?>
<form class="form-horizontal" role="form" id="formUpdateApi-<?php echo $id; ?>">
    <input type='hidden' name='application_id' value='<?php echo $api->application_id; ?>' />
    <input type="hidden" name="api_id" id="api_id" value="<?php echo $api->id; ?>" />
    <div class="form-group" id="divApiPath">
        <label for="inputApiPath" class="col-lg-2 control-label">Path</label>
        <div class="col-lg-4">
            <input type="text" class="form-control" 
                   name="path" id="inputApiPath" 
                   placeholder="/resource"
                   value="<?php if(!is_null($api->path)){ echo CHtml::encode($api->path); } ?>">
        </div>
    </div>
    <div class="form-group" id="divApiDescription">
        <label for="inputApiDescription" class="col-lg-2 control-label">Description</label>
        <div class="col-lg-8">
            <textarea name="description" 
                      id="inputApiDescription" 
                      class="form-control" rows="3" 
                      placeholder="Describe what this API does."><?php if(!is_null($api->description)){ echo CHtml::encode($api->description); } ?></textarea>
        </div>
    </div>
</form>