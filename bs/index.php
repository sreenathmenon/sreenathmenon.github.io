<?php


$system_path = '../system';
include './bootstrap-ee2.php';

include 'vendor/nb/oxymel/Oxymel.php';

ee()->sync_db = ee()->load->database('sync_db', true);

// shut up php
error_reporting(0);
ee()->load->helper('xml');

		$output = new Oxymel;

		// create our structure
		$output->xml->products->contains;

		

		$query = ee()->sync_db
			->where('processed', 0)
			->order_by('id', 'asc')
			->get('products_initial', 5000);

		$ids_processed = array();

		foreach($query->result() as $row)
		{

			if($row->tax_exempt == 0)
			{
				$tax_exempt = "Taxable";
			}	
			else
			{
				$tax_exempt = "";
			}

			if(!empty($row->product_category_3))
			{
				$product_category = $row->product_category_3;
			}	
			elseif(!empty($row->product_category_2))
			{
				$product_category = $row->product_category_2;
			}
			else
			{
				$product_category = $row->product_category_1;
			}

			// append a product
			$output
				->product->contains
				  	->title->contains->cdata($row->sku .' - ' . $row->name)->end
				  	->sku($row->sku)
				  	->name->contains->cdata($row->name)->end
				  	->price($row->price)
				  	->weight($row->weight)
				  	->length($row->length)
				  	->width($row->width)
				  	->height($row->height)
				  	->handling($row->handling)
				  	->free_shipping($row->free_shipping)
				  	->tax_exempt($tax_exempt)
				  	->category->contains->cdata($product_category)->end
				  	->product_number($row->product_number)
				  	->manufacturer->contains->cdata($row->product_manufacturer)->end
				  	->container($row->product_container)
				  	->condition($row->product_condition)
				  	->listed($row->product_listed)
				  	->location($row->product_location)
				  	->tested($row->product_tested)
				  	->cosmetic_condition->contains->cdata($row->product_cosmetic_condition)->end
				  	->keywords->contains->cdata($row->product_keywords)->end
				  	->natural_search->contains->cdata($row->product_natural_search)->end
				  	->description->contains->cdata($row->product_description)->end
				  	->image($row->product_image_filename)
				  	->stock_level($row->product_stock_level)
				  	->sell_online($row->product_sell_online)
				  	->timestamp($row->timestamp)
				->end;	

			$ids_processed[] = $row->id;

		}	

		// close our structure
		$output->end;	
			  	
		// update processed flag on records	
		if(count($ids_processed) > 0)
		{
			ee()->sync_db->where_in('id', $ids_processed)->set('processed', 1)->update('products_initial');
		}

		header('Content-Type: text/xml');
		exit($output->to_string());