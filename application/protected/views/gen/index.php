<script type="text/javascript">
    var application_id = null;

    $(function() {
        if (application_id !== null) {
            $('#buttonUpdateApplication').html('Update Application');
        }
    });

    function updateApplication()
    {
        // Clear errors
        $('.has-error').removeClass('has-error');
        hideAlert('applicationError');
        hideAlert('applicationSuccess');

        var application_name = $('#inputApplicationName').val();
        var application_desc = $('#inputApplicationDescription').val();
        var api_version = $('#inputApiVersion').val();
        var base_path = $('#inputBasePath').val();
        var resource_path = $('#inputResourcePath').val();

        if (application_name.length < 2) {
            $('#divApplicationName').addClass('has-error');
            showAlert('applicationError', 'Applicaton name is required.');
        } else if (api_version.length < 1) {
            $('#divApiVersion').addClass('has-error');
            showAlert('applicationError', 'API Version is required.');
        } else if (base_path.length < 8) {
            $('#divBasePath').addClass('has-error');
            showAlert('applicationError', 'Base Path must be a URL.');
        } else if (resource_path.length < 1) {
            $('#divResourcePath').addClass('has-error');
            showAlert('applicationError', 'Resource Path is required.');
        } else {
            // Set default method and url
            var method = 'POST';
            var url = '/api/application';
            // Update method and url if this is an existing application
            if (application_id !== null) {
                method = 'PUT';
                url += '/' + application_id;
            }

            $.ajax({
                url: url,
                type: method,
                data: {
                    name: application_name,
                    description: application_desc,
                    api_version: api_version,
                    base_path: base_path,
                    resource_path: resource_path
                },
                success: function(response) {
                    console.log(response);
                    if (response.success === true) {
                        application_id = response.application_id;
                        $('#buttonUpdateApplication').html('Update Application');
                        $('#applicationNameTitle').html(' - ' + application_name);
                        showAlert('applicationSuccess', 'Application created successfuly, you may now add APIs to your application.');
                    } else {
                        showAlert('applicationError', '[' + response.code + '] ' + response.error);
                    }
                }
            });
        }

        return false;
    }

    function showAlert(id, msg)
    {
        $('#' + id + 'Msg').empty().append(msg);
        $('#' + id).fadeIn('slow');
    }

    function hideAlert(id)
    {
        $('#' + id).hide();
    }
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
                            <input type="text" class="form-control" name="applicationName" id="inputApplicationName" placeholder="My Dope App">
                        </div>
                    </div>
                    <div class="form-group" id="divApplicationDescription">
                        <label for="inputApplicationDescription" class="col-lg-2 control-label">Description</label>
                        <div class="col-lg-8">
                            <textarea name="applicationDescription" id="inputApplicationDescription" class="form-control" rows="3" placeholder="This field is not part of the Swagger specification and is only used within Swank"></textarea>
                        </div>
                    </div>
                    <div class="form-group" id="divApiVersion">
                        <label for="inputApiVersion" class="col-lg-2 control-label">API Version</label>
                        <div class="col-lg-2">
                            <input type="text" class="form-control" name="apiVersion" id="inputApiVersion" placeholder="1.0">
                        </div>
                    </div>
                    <div class="form-group" id="divBasePath">
                        <label for="inputBasePath" class="col-lg-2 control-label">Base Path</label>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="basePath" id="inputBasePath" placeholder="https://mydomain.com/api">
                        </div>
                    </div>
                    <div class="form-group" id="divResourcePath">
                        <label for="inputResourcePath" class="col-lg-2 control-label">Resource Path</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" name="resourcePath" id="inputResourcePath" placeholder="/person">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button type="submit" id="buttonUpdateApplication" class="btn btn-primary">Create Application</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                    2) Add APIs
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
                            <div class="panel-heading">APIs</div>
                            <ul class="nav nav-pills nav-stacked" id="yw4">
                                <li><a href="#application">Define Application</a></li>
                                <li><a href="#apis">Define APIs</a></li>
                                <li><a href="#operations">Define Operations</a></li>
                                <li><a href="#Parameters">Define Parameters</a></li>
                                <li><a href="#responses">Define Responses</a></li>
                            </ul>                        
                        </div>
                    </div>
                    <div class="col-md-9">
                        <h3>Add New API</h3>
                        
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>