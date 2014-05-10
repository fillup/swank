/**
 * Global variables for handlebars templates
 */
var apiListTableTemplate, operationsListTableTemplate, parametersListTableTemplate;
var responsesListTableTemplate;

/**
 * On page load activities
 */
$(function(){
    if (typeof application_id !== 'undefined' && application_id !== null) {
        $('#buttonUpdateApplication').html('Update Application');
        //loadApiListMenu();
        $('#addApisPanel').show();
    }
    
    $('#addApiOperationButton').click(function(){
        if(api_id == null){
            showAlert('apiError','You must select an API first.');
        }
    });
    
    /**
     * If application_id is set, load list of APIs
     */
    loadApiListMenu();
});


/**
 * Global variable/function definitions
 */

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
    var visibility = $('input[name=visibility]:checked').val();

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
                resource_path: resource_path,
                visibility: visibility
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
            },
            error: function(xhr) {
                var response = $.parseJSON(xhr.responseText);
                showAlert('applicationError', '[' + response.code + '] ' + response.error);
            }
        });
    }

    return false;
}

/**
 * Create/Update an API
 */
function updateApi(id)
{
    var formId = '#formUpdateApi-'+id;
    var path = $((formId+' [name=path]')).val();
    var desc = $((formId+' [name=description]')).val();
    var application_id = $((formId+' [name=application_id]')).val();
    
    if(path.length < 1){
        showModalAlert(id,false,'An API path is required');
        return false;
    } else if (desc.length < 1){
        showModalAlert(id,false,'An API description is required');
        return false;
    } else {
        // Set default method and url
        var method = 'POST';
        var url = '/api/api';
        // Update method and url if this is an existing application
        if (id !== 'NEW') {
            method = 'PUT';
            url += '/' + id;
        }

        $.ajax({
            url: url,
            type: method,
            data: {
                path: path,
                description: desc,
                application_id: application_id
            },
            success: function(response) {
                console.log(response);
                if (response.success === true) {
                    showModalAlert(id,true,'API updated successfuly, you may now add operations to this API.');
                    loadApiListMenu();
                } else {
                    showModalAlert(id,false,'[' + response.code + '] ' + response.error);
                }
            },
            error: function(xhr) {
                var response = $.parseJSON(xhr.responseText);
                showModalAlert(id,false,'[' + response.code + '] ' + response.error);
            }
        });
    }
    
    return false;
}

/**
 * Create/Update an Operation
 */
function updateOperation(id)
{
    var formId = '#formUpdateOperation-'+id;
    var operationMethod = $((formId+' [name=operationMethod]')).val();
    var nickname = $((formId+' [name=nickname]')).val();
    var type = $((formId+' [name=type]')).val();
    var summary = $((formId+' [name=summary]')).val();
    var api_id = $((formId+' [name=api_id]')).val();
    
    if(nickname.length < 1){
        showModalAlert(id,false,'A nickname is required');
        return false;
    } else if (summary.length < 1){
        showModalAlert(id,false,'A summary is required');
        return false;
    } else {
        // Set default method and url
        var method = 'POST';
        var url = '/api/apiOperation';
        // Update method and url if this is an existing application
        if (id !== 'NEW') {
            method = 'PUT';
            url += '/' + id;
        }

        $.ajax({
            url: url,
            type: method,
            data: {
                method: operationMethod,
                nickname: nickname,
                type: type,
                summary: summary,
                api_id: api_id
            },
            success: function(response) {
                console.log(response);
                if (response.success === true || response.success === 'true') {
                    showModalAlert(id,true,'Operation updated successfuly, you may now add parameters and responses to this Operation.');
                    loadOperationListMenu(api_id);
                } else {
                    showModalAlert(id,false,'[' + response.code + '] ' + response.error);
                }
            },
            error: function(xhr) {
                var response = $.parseJSON(xhr.responseText);
                showModalAlert(id,false,'[' + response.code + '] ' + response.error);
            }
        });
    }
    
    return false;
}

/**
 * Create/Update a Parameter
 */
function updateParameter(id)
{
    var formId = '#formUpdateParameter-'+id;
    var name = $((formId+' [name=name]')).val();
    var paramType = $((formId+' [name=paramType]')).val();
    var dataType = $((formId+' [name=dataType]')).val();
    var description = $((formId+' [name=description]')).val();
    var required = $((formId+' [name=required]')).val();
    var operation_id = $((formId+' [name=operation_id]')).val();
    
    if(name.length < 1){
        showModalAlert(id,false,'A name is required');
        return false;
    } else if (required.length < 1){
        showModalAlert(id,false,'You must select whether or not the parameter is required');
        return false;
    } else {
        // Set default method and url
        var method = 'POST';
        var url = '/index-test.php/api/apiParameter';
        // Update method and url if this is an existing application
        if (id !== 'NEW') {
            method = 'PUT';
            url += '/' + id;
        }

        $.ajax({
            url: url,
            type: method,
            data: {
                name: name,
                paramType: paramType,
                dataType: dataType,
                required: required,
                description: description,
                operation_id: operation_id
            },
            success: function(response) {
                console.log(response);
                if (response.success === true) {
                    showModalAlert(id,true,'Parameter updated successfuly.');
                    loadParameterListMenu(operation_id);
                } else {
                    showModalAlert(id,false,'[' + response.code + '] ' + response.error);
                }
            },
            error: function(xhr) {
                var response = $.parseJSON(xhr.responseText);
                showModalAlert(id,false,'[' + response.code + '] ' + response.error);
            }
        });
    }
    
    return false;
}

/**
 * Loads list of defined APIs for application into left menu under Add APIs 
 * section
 */
function loadApiListMenu()
{
    if(typeof application_id !== 'undefined' && application_id !== null){
        var source   = $("#apiListTableTemplate").html();
        apiListTableTemplate = Handlebars.compile(source);
        if(application_id !== null) {
            $.getJSON('/api/api?application_id='+application_id,
            function(apis){
                console.log(apis.data);
                $("#apiListTablePlaceholder").html(apiListTableTemplate(apis));
                /**
                 * Add onClick action for APIs in the list to load
                 * the operations menu
                 */
                $('.apiListItem').click(function(){
                   api_id = this.id;
                   loadOperationListMenu(this.id);
                   setRowActiveForTdId(this.id);
                });
            });
        }
    }
}

/**
 * Loads list of defined Operations for a given API
 */
function loadOperationListMenu(api_id)
{
    if(api_id !== null){
        var source   = $("#operationsListTableTemplate").html();
        operationsListTableTemplate = Handlebars.compile(source);
        var url = '/api/apiOperation?api_id='+api_id;
        $.getJSON(url, function(operations){
            console.log(operations.data);
            $("#operationsListTablePlaceholder").html(operationsListTableTemplate(operations));
            /**
             * Add onClick action for Operations in the list to load
             * the parameters and responses
             */
            $('.operationListItem').click(function(){
               operation_id = this.id;
               loadParameterListMenu(this.id);
               //loadResponseListMenu(this.id);
               setRowActiveForTdId(this.id);
            });
        });
    }
}

/**
 * Loads list of defined Parameters for a given Operation
 */
function loadParameterListMenu(operation_id)
{
    if(operation_id !== null){
        var source   = $("#parametersListTableTemplate").html();
        parametersListTableTemplate = Handlebars.compile(source);
        var url = '/api/apiParameter?operation_id='+operation_id;
        $.getJSON(url, function(parameters){
            console.log(parameters.data);
            $("#parametersListTablePlaceholder").html(parametersListTableTemplate(parameters));
            /**
             * Add onClick action for Parameters in the list to load
             * the edit modal
             */
            $('.parameterListItem').click(function(){
               parameter_id = this.id;
               setRowActiveForTdId(this.id);
               showModal(this.id,'/gen/getEditParameterForm/'+this.id);
            });
        });
    }
}

/**
 * Loads list of defined Operations for a given API
 */
function loadResponseListMenu(operation_id)
{
    if(operation_id !== null){
        var source   = $("#responsesListTableTemplate").html();
        responsesListTableTemplate = Handlebars.compile(source);
        var url = '/api/apiResponse?operation_id='+operation_id;
        $.getJSON(url, function(responses){
            console.log(responses.data);
            $("#responsesListTablePlaceholder").html(responsesListTableTemplate(operations));
            /**
             * Add onClick action for APIs in the list to load
             * the operations menu
             */
            $('.responseListItem').click(function(){
               response_id = this.id;
               setRowActiveForTdId(this.id);
            });
        });
    }
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

function showModalAlert(id, success, msg)
{
    if(success){
        var divPrefix = '#success';
    } else {
        var divPrefix = '#error';
    }
    $(divPrefix+'Message-' + id + '-Msg').empty().append(msg);
    $(divPrefix+'MessageBox-' + id).fadeIn('slow');
}

function hideModalAlert(id, success)
{
    if(success){
        var divPrefix = '#success';
    } else {
        var divPrefix = '#error';
    }
    $(divPrefix+'MessageBox-' + id).hide();
}

function showModal(id,url){
    var modalDivId = '#modal-'+id;
    if($(modalDivId).length != 0){
        $(modalDivId).remove();
    }
    $.get(url,null,function(response){
        $('body').append(response);
        $(modalDivId).modal('toggle');
    });
}

function setRowActiveForTdId(id)
{
    $('#'+id).parent().parent().children().removeClass('success')
    if(id){
        $('#'+id).parent().addClass('success');
    }
}