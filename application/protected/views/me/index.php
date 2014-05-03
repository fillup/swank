<div class="page-header">
    <h1>My Applications</h1>
</div>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $myApps,
    'columns' => array(
        'name',
        array(
            'name' => 'description',
            'value' => 'substr($data->description,0,30)."..."',
        ),
        'base_path',
        'resource_path',
        'api_version',
        array(
            'name' => 'updated',
            'value' => 'date("M j, Y", strtotime($data->updated))',
        ),
        array(// display a column with "view", "update" and "delete" buttons
            'class' => 'CButtonColumn',
            'template' => '{view}{update}{delete}',
            'buttons' => array(
                'view' => array(
                    'label' => 'View Swagger UI',
                    'url' => 'Yii::app()->createUrl("ui/$data->id")',
                ),
                'update' => array(
                    'label' => 'Edit API Definition',
                    'url' => 'Yii::app()->createUrl("gen/$data->id")',
                ),
            ),
            'deleteConfirmation'=>"js:'Record with ID '+$(this).parent().parent().children(':first-child').text()+' will be deleted! Continue?'",
        ),
    ),
    'htmlOptions' => array(
        'class' => 'table table-striped',
    ),
    'enablePagination' => true,
));
?>