<?php
/**
 * Partial for rendering a modal
 * Expected variables:
 * $id - unique ID for record or modal
 * $title - Header/title for modal
 * $body - Body content, html
 * $saveable - boolean of whether or not to show a save button
 * $deleteable - boolean of whether or not to show the delete button
 * $saveAction - javascript function call for save action
 * $deleteAction - javascript function call for delete action
 */
?>
<!-- Modal -->
<div id="modal-<?php echo $id; ?>" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="myModalLabel"><?php echo $title; ?></h3>
            </div>
            <div class="modal-body">
                <div id="successMessageBox-<?php echo $id; ?>" class="alert alert-success" style="display: none;">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Success!</strong> <span id="successMessage-<?php echo $id; ?>-Msg"></span>
                </div>
                <div id="errorMessageBox-<?php echo $id; ?>" class="alert alert-danger" style="display: none;">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Error!</strong> <span id="errorMessage-<?php echo $id; ?>-Msg"></span>
                </div>
                <?php echo $body; ?>
            </div>
            <div class="modal-footer">
                <?php
                    if($deleteable){
                ?>
                    <button id="save-button-<?php echo $id; ?>"
                            class="btn btn-danger pull-left"
                            onclick="<?php echo $deleteAction; ?>"><i class="icon-trash"></i> Delete</button>
                <?php
                    }
                ?>
                <button class="btn <?php if (!$saveable) {
                    echo 'btn-primary';
                } ?>" 
                        data-dismiss="modal"><i class="icon-remove"></i> Close</button>
                <?php if ($saveable) { ?>
                    <button id="save-button-<?php echo $id; ?>" 
                            class="btn btn-primary" 
                            onclick="<?php echo $saveAction; ?>"><i class="icon-ok icon-white"></i> Save changes</button>
                <?php } ?>
            </div>
        </div>
    </div>
</div>