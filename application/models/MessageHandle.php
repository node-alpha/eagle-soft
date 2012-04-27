<?php
class Model_MessageHandle extends Model_Base
{
	private $i_Code = '';
	private $a_Message = array();
	private $sz_Format = 'TEXT';
	private $a_Return = array();

	/**
	 * Set success
	 */
	public function v_fSetSuccess ()
	{
		$this->i_Code = 1;
	}

	/**
	 * Set Error
	 */
	public function v_fSetError ()
	{
		$this->i_Code = 0;
	}

	/**
	 * Set format to return HTML | TEXT | JSON
	 * @param $the_sz_Format
	 */
	public function v_fSetFormat ($the_sz_Format)
	{
		$this->sz_Format = $the_sz_Format;
	}

	/**
	 * Set return
	 */
	public function v_fSetReturn ($the_a_Data)
	{
		foreach ($the_a_Data as $sz_Key => $sz_Value)
		{
			$this->a_Return[$sz_Key] = $sz_Value;
		}
	}

	/**
	 * Check error
	 */
	public function b_fIsError ()
	{
		return $this->i_Code;
	}

	/**
	 * Set message
	 */
	public function v_fSetErrorMessage ($the_sz_Message)
	{
		$this->a_Message[] = $the_sz_Message;
		$this->v_fSetError();
	}

	/**
	 * Set message
	 */
	public function v_fSetMessage ($the_sz_Message)
	{
		$this->a_Message[] = $the_sz_Message;
	}

	/**
	 * Get return
	 */
	public function a_fGetReturn ()
	{
		return $this->a_Return;
	}

	/**
	 * Get message
	 */
	public function sz_fGetMessage ()
	{
		switch ($this->sz_Format)
		{
			case 'HTML':
				return $this->sz_fGetHTML();
				break;
			case 'TEXT':
				return $this->sz_fGetTEXT();
				break;
			case 'JSON':
				return $this->sz_fGetJSON();
				break;
		}
	}

	private function sz_fGetHTML ()
	{
		$sz_Response = '<ul class=\'response-message\'>';
		foreach ($this->a_Message as $sz_Message)
		{
			$sz_Response .= '<li>' . $sz_Message . '</li>';
		}
		$sz_Response .= '</ul>';
		return htmlspecialchars($sz_Response);
	}

	private function sz_fGetTEXT ()
	{
		$sz_Response = '';
		foreach ($this->a_Message as $sz_Message)
		{
			$sz_Response .= $sz_Message . PHP_EOL;
		}
		return $sz_Response;
	}

	private function sz_fGetJSON ()
	{
		$sz_Response = '<ul class=\'response-message\'>';
		foreach ($this->a_Message as $sz_Message)
		{
			$sz_Response .= '<li>' . $sz_Message . '</li>';
		}
		$sz_Response .= '</ul>';
		return $sz_Response;
	}

	public function __toString ()
	{
		$o_Response = new stdClass();
		$o_Response->sz_Message = $this->sz_fGetMessage();
		$o_Response->a_Return = $this->a_Return;
		$o_Response->i_Code = $this->i_Code;
		return json_encode($o_Response);
	}
}