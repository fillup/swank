<?php

/**
 * This is the model class for table "api_operation".
 *
 * The followings are the available columns in table 'api_operation':
 * @property string $id
 * @property string $api_id
 * @property string $method
 * @property string $nickname
 * @property string $type
 * @property string $summary
 * @property string $notes
 * @property string $created
 * @property string $updated
 *
 * The followings are the available model relations:
 * @property Api $api
 * @property ApiParameter[] $apiParameters
 * @property ApiResponse[] $apiResponses
 */
class ApiOperationBase extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'api_operation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('api_id, method, nickname, type', 'required'),
			array('id, api_id, nickname, type', 'length', 'max'=>32),
			array('method', 'length', 'max'=>8),
			array('summary', 'length', 'max'=>255),
			array('notes', 'length', 'max'=>2048),
			array('created, updated', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, api_id, method, nickname, type, summary, notes, created, updated', 'safe', 'on'=>'search'),
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
			'api' => array(self::BELONGS_TO, 'Api', 'api_id'),
			'apiParameters' => array(self::HAS_MANY, 'ApiParameter', 'operation_id'),
			'apiResponses' => array(self::HAS_MANY, 'ApiResponse', 'operation_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'api_id' => 'Api',
			'method' => 'Method',
			'nickname' => 'Nickname',
			'type' => 'Type',
			'summary' => 'Summary',
			'notes' => 'Notes',
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
		$criteria->compare('api_id',$this->api_id,true);
		$criteria->compare('method',$this->method,true);
		$criteria->compare('nickname',$this->nickname,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('summary',$this->summary,true);
		$criteria->compare('notes',$this->notes,true);
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
	 * @return ApiOperationBase the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
