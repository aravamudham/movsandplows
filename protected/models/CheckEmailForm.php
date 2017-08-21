<?php

class CheckEmailForm extends CFormModel
{
	public $email;
	//public $verifyCode;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('email', 'required'),
			// email has to be a valid email address
			array('email', 'email'),
			array('email', 'emailCheck'),

			// verifyCode needs to be entered correctly
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
			//'verifyCode'=>'Verification Code',
			'email'=> 'Your Email'
		);
	}

	public function emailCheck()
	{
		$email = $this->email;

		$check = User::model()->find('email ="'.$email.'"');

		if (!isset($check))
		{
			$this->addError('email', 'Email does not exist, please login/register user on Link Rider app first');
		}

	}
}