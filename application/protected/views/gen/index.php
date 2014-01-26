<script type="text/javascript">
    var application_id = <?php if(!is_null($application->id)){ echo '"'.$application->id.'"'; } else { echo 'null'; } ?>
</script>
<div class="page-header">
    <h1>Swagger Code Generator <small>Create &amp; Define your API</small></h1>
</div>
<div class="panel-group" id="accordion">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                    1) Define Application <span id="applicationNameTitle"></span>
                </a>
            </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse in">
            <div class="panel-body" id="divApplicationForm">
                <div class="alert alert-danger" id="applicationError" style="display: none;">
                    <strong>doh!</strong> <span id="applicationErrorMsg"></span>
                </div>
                <div class="alert alert-success" id="applicationSuccess" style="display: none;">
                    <strong>w00t!</strong> <span id="applicationSuccessMsg"></span>
                </div>
                <form class="form-horizontal" role="form" id="formUpdateApplication" onsubmit="return updateApplication()">
                    <div class="form-group" id="divApplicationName">
                        <label for="inputApplicationName" class="col-lg-2 control-label">Application Name</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" 
                                   name="applicationName" id="inputApplicationName" 
                                   placeholder="My Dope App"
                                   value="<?php if(!is_null($application->name)){ echo CHtml::encode($application->name); } ?>">
                        </div>
                    </div>
                    <div class="form-group" id="divApplicationDescription">
                        <label for="inputApplicationDescription" class="col-lg-2 control-label">Description</label>
                        <div class="col-lg-8">
                            <textarea name="applicationDescription" 
                                      id="inputApplicationDescription" 
                                      class="form-control" rows="3" 
                                      placeholder="This field is not part of the Swagger specification and is only used within Swank"><?php if(!is_null($application->description)){ echo CHtml::encode($application->description); } ?></textarea>
                        </div>
                    </div>
                    <div class="form-group" id="divApiVersion">
                        <label for="inputApiVersion" class="col-lg-2 control-label">API Version</label>
                        <div class="col-lg-2">
                            <input type="text" class="form-control" 
                                   name="apiVersion" id="inputApiVersion" 
                                   placeholder="1.0"
                                   value="<?php if(!is_null($application->api_version)){ echo CHtml::encode($application->api_version); } ?>">
                        </div>
                    </div>
                    <div class="form-group" id="divBasePath">
                        <label for="inputBasePath" class="col-lg-2 control-label">Base Path</label>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="basePath" 
                                   id="inputBasePath" 
                                   placeholder="https://mydomain.com/api"
                                   value="<?php if(!is_null($application->base_path)){ echo CHtml::encode($application->base_path); } ?>">
                        </div>
                    </div>
                    <div class="form-group" id="divResourcePath">
                        <label for="inputResourcePath" class="col-lg-2 control-label">Resource Path</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" name="resourcePath" 
                                   id="inputResourcePath" placeholder="/person"
                                   value="<?php if(!is_null($application->resource_path)){ echo CHtml::encode($application->resource_path); } ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button type="submit" id="buttonUpdateApplication" 
                                    class="btn btn-primary">
                                <?php
                                    if(!is_null($application->id)){
                                        echo 'Update Application';
                                    } else {
                                        echo "Create Application";
                                    }
                                ?>
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="panel panel-default" id="addApisPanel" style="display: none;">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                    2) Define APIs
                </a>
                <a class="btn btn-primary btn-xs pull-right" style="color: white;" 
                   href="javascript: showModal('NEW','<?php echo Yii::app()->createUrl('/gen/getEditApiForm',array('application_id' => $application->id)); ?>')" id="addApiButton">
                    <span class="glyphicon glyphicon-plus"></span> Add API
                </a>
            </h4>
        </div>
        <div id="collapseTwo" class="panel-collapse collapse">
            <div class="panel-body" id="divApiForm">

                <div class="alert alert-danger" id="apiError" style="display: none;">
                    <strong>doh!</strong> <span id="apiErrorMsg"></span>
                </div>
                <div class="alert alert-success" id="apiSuccess" style="display: none;">
                    <strong>w00t!</strong> <span id="apiSuccessMsg"></span>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                APIs
                                <a class="btn btn-primary btn-xs pull-right" style="color: white;" 
                                   href="javascript: showModal('NEW','<?php echo Yii::app()->createUrl('/gen/getEditApiForm',array('application_id' => $application->id)); ?>')" id="addApiButton">
                                     <span class="glyphicon glyphicon-plus"></span> Add API
                                </a>
                            </div>
                            <ul class="nav nav-pills nav-stacked" id="apiListMenu">
                            </ul>                        
                        </div>
                    </div>
                    <div class="col-md-9" id="editApiContainer">
                        <?php //echo $this->actionGetEditApiForm(); ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>