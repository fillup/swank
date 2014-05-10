<script type="text/javascript">
    var application_id = <?php if(!is_null($application->id)){ echo '"'.$application->id.'"'; } else { echo 'null'; } ?>;
    var api_id = null;
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
        <div id="collapseOne" class="panel-collapse collapse <?php if(is_null($application->id)){ echo 'in'; } ?>">
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
                    <div class="form-group" id="divVisibility">
                        <label for="inputVisibility" class="col-lg-2 control-label">Application Visibility</label>
                        <div class="col-lg-4" style='padding-left: 30px;'>
                            <div class='radio'>
                            <label>
                                <input type='radio' name='visibility' value='public'
                                       <?php if($application->visibility == 'public'){ echo "checked='checked'"; } ?>
                                       /> Public
                            </label>
                            </div>
                            <div class='radio'>
                            <label>
                                <input type='radio' name='visibility' value='unlisted'
                                       <?php if($application->visibility == 'unlisted'){ echo "checked='checked'"; } ?>
                                       /> Unlisted
                            </label>
                            </div>
                            <span class="help-block">
                                Publicly visible APIs will be listed in the 
                                <a href='<?php echo Yii::app()->createUrl('/directory'); ?>'>
                                    API Directory
                                </a>
                            </span>
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
            </h4>
        </div>
        <div id="collapseTwo" class="panel-collapse collapse <?php if(!is_null($application->id)){ echo 'in'; } ?>">
            <div class="panel-body" id="divApiForm">

                <div class="alert alert-danger" id="apiError" style="display: none;">
                    <strong>doh!</strong> <span id="apiErrorMsg"></span>
                </div>
                <div class="alert alert-success" id="apiSuccess" style="display: none;">
                    <strong>w00t!</strong> <span id="apiSuccessMsg"></span>
                </div>
                <div class='container'>
                    <div class='row'>
                        <div class="col-md-3">
                            <p class='well well-sm' style='text-align: center;'>
                                First: Add/Select an API
                            </p>
                        </div>
                        <div class="col-md-3">
                            <p class='well well-sm' style='text-align: center;'>
                                Second: Add/Select an Operation
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class='well well-sm' style='text-align: center;'>
                                Finally: Define parameters and responses
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="panel panel-info">
                                <div class="panel-heading">APIs</div>
                                <div id='apiListTablePlaceholder'></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="panel panel-info">
                                <div class="panel-heading">Operations</div>
                                <div id='operationsListTablePlaceholder'>
                                    <p style='text-align: center;'><i>(Select an API)</i></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="panel panel-info">
                                <div class="panel-heading">Parameters</div>
                                <div id='parametersListTablePlaceholder'>
                                    <p style='text-align: center;'><i>(Select an Operation)</i></p>
                                </div>                      
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="panel panel-info">
                                <div class="panel-heading">Responses</div>
                                <div id='responsesListTablePlaceholder'>
                                    <p style='text-align: center;'><i>(Select an Operation)</i></p>
                                </div>                        
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script id="apiListTableTemplate" type="text/x-handlebars-template">
    <table class="table table-borderless table-hover table-striped table-responsive" style="width: 100%">
      <tbody>
      {{#each data}}
        <tr>
          <td style='width: 20px;'>
            <a class='nohreflink' href="javascript: showModal('{{this.id}}','/gen/getEditApiForm/{{this.id}}')">
              <span class="glyphicon glyphicon-pencil"></span>
            </a>
          </td>
          <td class='nohreflink apiListItem' id='{{this.id}}'>
                {{this.path}}
                <span class="glyphicon glyphicon-chevron-right pull-right"></span>
          </td>
        </tr>
      {{/each}}
      </tbody>
    </table>
    <p style="text-align: center; padding: 0px 15px 15px 15px; margin: 0;">
      <a class='addApiButton btn btn-primary btn-xs' 
      href='javascript: showModal("NEW","<?php echo Yii::app()->createUrl('/gen/getEditApiForm',array('application_id' => $application->id)); ?>")'>
        <span class="glyphicon glyphicon-plus"></span> Add API
      <a>
    </p>
</script>
<script id="operationsListTableTemplate" type="text/x-handlebars-template">
    <table class="table table-borderless table-hover table-striped table-responsive" style="width: 100%">
      <tbody>
      {{#each data}}
        <tr>
          <td style='width: 20px;'>
            <a class='nohreflink' href="javascript: showModal('{{this.id}}','/gen/getEditOperationForm/{{this.id}}')">
              <span class="glyphicon glyphicon-pencil"></span>
            </a>
          </td>
          <td class='nohreflink operationListItem' id='{{this.id}}'>
                {{this.nickname}}
                <span class="glyphicon glyphicon-chevron-right pull-right"></span>
          </td>
        </tr>
      {{/each}}
      </tbody>
    </table>
    <p style="text-align: center; padding: 0px 15px 15px 15px; margin: 0;">
      <a class='addApiButton btn btn-primary btn-xs' 
      href="javascript: showModal('NEW','/gen/getEditOperationForm?api_id='+api_id)">
        <span class="glyphicon glyphicon-plus"></span> Add Operation
      <a>
    </p>
</script>
<script id="parametersListTableTemplate" type="text/x-handlebars-template">
    <table class="table table-borderless table-hover table-striped table-responsive" style="width: 100%">
      <tbody>
      {{#each data}}
        <tr class='nohreflink parameterListItem' id='{{this.id}}'>
          <td style='width: 20px;'>
            <span class="glyphicon glyphicon-pencil"></span>
          </td>
          <td>
                {{this.name}}
          </td>
        </tr>
      {{/each}}
      </tbody>
    </table>
    <p style="text-align: center; padding: 0px 15px 15px 15px; margin: 0;">
      <a class='addParameterButton btn btn-primary btn-xs' 
      href="javascript: showModal('NEW','/gen/getEditParameterForm?operation_id='+operation_id)">
        <span class="glyphicon glyphicon-plus"></span> Add Parameter
      <a>
    </p>
</script>