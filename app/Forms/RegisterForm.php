<?php



use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Form;



class RegisterForm extends Form
{

	public function initialize()
	{
		
		$this->add(
		
			new text(
				'name'
			)
		
		);

		$this->add(
		
			new text(
				'email'
			)
		
		);

	}

}





?>