<?php

class DriverForm extends CFormModel
{
	public $carPlate;
	public $brand;
	public $model;
	public $year;
	//public $status;
	//public $account;
	public $image1;
	public $image2;
	//public $document;
	public $token;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			//array('carPlate, brand, model, year, status, account, token', 'required'),
			array('carPlate, brand, model, year, token', 'required'),
			array('image1, image2', 'file', 'allowEmpty'=>false, 'types'=>'jpg,jpeg,gif,png'),
			//array('document', 'file', 'allowEmpty'=>false, 'types'=>'jpg,jpeg,gif,png, doc, docx, pdf')
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'token' => 'Token',
			'carPlate' => 'Car Plate',
			'brand' => 'Brand',
			'model' => 'Model',
			'year' => 'Year',
			'status' => 'Status',
			'document' => 'Document',
			'image1' => 'Car Image 1',
			'image2' => 'Car Image 2',
			'account'=>'Account',
		);
	}

}