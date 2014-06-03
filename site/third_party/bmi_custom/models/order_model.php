<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Order_model extends CI_Model {

	public function toTagArray()
	{
		// load the serialized file
		$file = file_get_contents('./1391473878-serialized.txt');

		$order = unserialize($file);

		return $order;
	}

}

/* End of file order_model.php */
/* Location: ./system/expressionengine/third_party/bmi_custom/models/order_model.php */
