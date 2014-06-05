<div class="page-header">
    <h1>My Applications</h1>
    <a class="btn btn-primary btn-xs" href="<?php echo Yii::app()->createUrl('/gen'); ?>">
        <span class="glyphicon glyphicon-plus"></span> Add Application
    </a>
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
        array(
            'name' => 'updated',
            'value' => 'date("M j, Y", strtotime($data->updated))',
        ),
        array(// display a column with "view", "update" and "delete" buttons
            'class' => 'CButtonColumn',
            'template' => '{view}{update}',
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
        ),
    ),
    'htmlOptions' => array(
        'class' => 'table table-striped',
    ),
    'enablePagination' => true,
));
?>