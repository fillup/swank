<?php

/**
 * This is the model class for table "api_response".
 *
 * The followings are the available columns in table 'api_response':
 * @property string $id
 * @property string $operation_id
 * @property integer $code
 * @property string $message
 * @property string $responseModel
 * @property string $created
 * @property string $updated
 *
 * The followings are the available model relations:
 * @property ApiOperation $operation
 */
class ApiResponseBase extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'api_response';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('operation_id, code, message', 'required'),
			array('code', 'numerical', 'integerOnly'=>true),
			array('id, operation_id', 'length', 'max'=>32),
			array('message', 'length', 'max'=>255),
			array('responseModel', 'length', 'max'=>34),
			array('created, updated', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, operation_id, code, message, responseModel, created, updated', 'safe', 'on'=>'search'),
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
			'code' => 'Code',
			'message' => 'Message',
			'responseModel' => 'Response Model',
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
		$criteria->compare('code',$this->code);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('responseModel',$this->responseModel,true);
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
	 * @return ApiResponseBase the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
