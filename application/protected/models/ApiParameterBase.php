<?php

/**
 * This is the model class for table "api_parameter".
 *
 * The followings are the available columns in table 'api_parameter':
 * @property string $id
 * @property string $operation_id
 * @property string $paramType
 * @property string $name
 * @property string $description
 * @property string $dataType
 * @property string $format
 * @property integer $required
 * @property string $minimum
 * @property string $maximum
 * @property string $enum
 * @property string $created
 * @property string $updated
 *
 * The followings are the available model relations:
 * @property ApiOperation $operation
 */
class ApiParameterBase extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'api_parameter';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('operation_id, paramType, name, dataType, required', 'required'),
			array('required', 'numerical', 'integerOnly'=>true),
			array('id, operation_id, name, dataType, format, minimum, maximum', 'length', 'max'=>32),
			array('paramType', 'length', 'max'=>8),
			array('description', 'length', 'max'=>255),
			array('enum', 'length', 'max'=>64),
			array('created, updated', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, operation_id, paramType, name, description, dataType, format, required, minimum, maximum, enum, created, updated', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'operation' => array(self::BELONGS_TO, 'ApiOperation', 'operation_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'operation_id' => 'Operation',
			'paramType' => 'Param Type',
			'name' => 'Name',
			'description' => 'Description',
			'dataType' => 'Data Type',
			'format' => 'Format',
			'required' => 'Required',
			'minimum' => 'Minimum',
			'maximum' => 'Maximum',
			'enum' => 'Enum',
			'created' => 'Created',
			'updated' => 'Updated',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('operation_id',$this->operation_id,true);
		$criteria->compare('paramType',$this->paramType,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('dataType',$this->dataType,true);
		$criteria->compare('format',$this->format,true);
		$criteria->compare('required',$this->required);
		$criteria->compare('minimum',$this->minimum,true);
		$criteria->compare('maximum',$this->maximum,true);
		$criteria->compare('enum',$this->enum,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('updated',$this->updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ApiParameterBase the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
