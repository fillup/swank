<div class="page-header">
    <h1>API Directory</h1>
    <form class="form-inline" role="form" method="GET">
        <div class="form-group">
            <label class="sr-only" for="searchInput">Search</label>
            <input type="text" class="form-control" id="searchInput"
                   placeholder="Search" name="query"
                   value="<?php if($query) { echo CHtml::encode($query); } ?>">
        </div>
        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span> Search</button>
    </form>
</div>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
        'dataProvider' => $apps,
        'columns' => array(
            'name',
            array(
                'name' => 'description',
                'value' => 'substr($data->description,0,30)."..."',
            ),
            array(
                'name' => 'updated',
                'value' => 'date("M j, Y", strtotime($data->updated))',
            ),
            array(// display a column with "view", "update" and "delete" buttons
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'template' => '{view} ',
                'buttons' => array(
                    'view' => array(
                        'label' => 'View Swagger UI',
                        'url' => 'Yii::app()->createUrl("ui/$data->id")',
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