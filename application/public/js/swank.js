/**
 * On page load activities
 */
$(function(){
    if (application_id !== null) {
        $('#buttonUpdateApplication').html('Update Application');
        loadApiListMenu();
        $('#addApisPanel').show();
    }
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
    if(application_id !== null){
        var url = '/api/api?application_id='+application_id;
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response){
                console.log(response);
                if(response.success == true){
                    $('#apiListMenu').empty();
                    for(i=0;i<response.count;i++){
                        var item = '<li>';
                        item += '<a href="">'+response.data[i].path+'</a>';
                        item += '</li>';
                        $('#apiListMenu').append(item);
                    }
                }
            }
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