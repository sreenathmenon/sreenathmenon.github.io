/**
 * ExpressionEngine Page Helper Accessory
 *
 * Helps users generate a Pages Module URI by allowing them to generate a Page URI by selecting a parent page
 * and automatically pulling the URL Title from the entry.
 *
 * Version: 1.1.1
 *
 * @package		Page Helper
 * @author		Conflux Group, Inc. <support@confluxgroup.com>
 * @link		http://confluxgroup.com
 * @addon link	http://devot-ee.com/add-ons/page-helper/
 * @copyright 	Copyright (c) 2013 Conflux Group, Inc.
 * @license   	http://confluxgroup.com/addons/license.txt
 */

$(document).ready(function (){
	
	// Find the Pages URI text field in the DOM and store a reference to it
	var uri_field = $("#pages_uri");
	
	// Fix for EE Build 20101215, where Pages URI field ID changed.
	if(uri_field.length == 0)
	{
		uri_field = $("#pages__pages_uri");
	}
	// fix for EE 2.8
	if(uri_field.length == 0)
	{
		uri_field = $("input[name=pages__pages_uri]");
	}
	
	
	// Find the URL Title text field in the DOM and store a reference to it.
	var url_title_field = $("#url_title");

	// fix for EE 2.8
	if(url_title_field.length == 0)
	{
		url_title_field = $("input[name=url_title]");
	}

	// Add an instructional paragraph before the <fieldset> containing the Pages URI text field
	uri_field.parent().before('<div class="instruction_text"><p><strong>Instructions:</strong>&nbsp;This field sets the URL for your page. Select the parent page from the dropdown menu. Your entry\'s URL Title is filled out automatically, but you can change it if you like. When done, click Update URL to create your page URL automatically.</p></div>');
	
	// Find the generated Pages <select> menu from the Page Helper accessory tab in the DOM and store
	// a reference to it.
	var pages_dropdown = $("#pages_dropdown");
	
	// Store the HTML of the Pages <select> menu.
	var pages_dropdown_html = pages_dropdown.html();
	
	// Remove the Page Helper accessory tab from the DOM.
	$("#accessoryTabs a.pagehelper").parent().remove();
	
	// Add the Pages <select> menu,the new Page URL Title text field, and the Generate Page URL
	// button before the Pages URI text field.
	uri_field.before('<select id="pages_dropdown">' + pages_dropdown_html + "</select> / ");
	uri_field.before('<input type="text" id="page_url_title">');
	uri_field.before('<input type="button" id="generate_uri" class="submit" value="Update Page URL">');

	// Copy the URL title field to the Page URL Title field
	$("#page_url_title").val(url_title_field.val());

	// Get the current Pages URI
	var current_url = uri_field.val();

	if(current_url == '/example/pages/uri/')
	{
		current_url = '';
	}	

	// Split the segments of the current URL
	var current_url_segments = current_url.substr(1).split('/');

	// Init a new variable for the selected parent
	var selected_parent = '';
	
	// If we've got more than 1 segment, then we loop through and grab everything but the last
	if(current_url_segments.length > 1)
	{
		// pop the last segment off
		current_url_segments.pop();

		// add the leading slash back
		var current_url_parent = '';

		for(seg = 0; seg < current_url_segments.length; seg++)
		{
			current_url_parent += '/' + current_url_segments[seg];
		} 
	}	

	$("#pages_dropdown").find('option[value="' + current_url_parent + '"]').attr('selected', 'selected');

	// refreshing the reference to the pages_dropdown
	var pages_dropdown = $("#pages_dropdown");

	
	// Bind the Keyup event of the URL Title text field to update the Page URL Title text field.
	url_title_field.keyup(function (){
		$("#page_url_title").val(url_title_field.val());
	});

	// Bind the Keyup event of the Title text field to update the Page URL Title text field.

	// use title as field name instead of id
	title_field = $("input[name=title]");


	title_field.keyup(function (){
		$("#page_url_title").val(url_title_field.val());
	});
	
	// Bind the click event of the Generate Page URL button to assemble the two pieces of the new
	// URL, remove any double slashes and place the value in the Pages URI text field.
	$("#generate_uri").click(function(){
		// Find the two needed fields in the DOM and store references to them.
		page_url_title_field = $("#page_url_title");
		pages_dropdown = $("#pages_dropdown");
		
		// Create a new_uri string containing the two field values joined by a slash.
		var new_uri = pages_dropdown.val() + '/' + page_url_title_field.val();
		
		// Replace any double slashes with a single slash.
		while(new_uri.indexOf('//') != -1)
		{
			new_uri = new_uri.replace('//', '/');
		}
		
		// set a boolean as to whether the url should change
		should_change_url = true;

		// if the uri isn't blank and the uri doesn't match the new uri, then we prompt to make sure
		// the uri should update
		if(uri_field.val() != '' && uri_field.val() != '/example/pages/uri/' && uri_field.val() != new_uri)
		{
			should_change_url = confirm('Are you sure you want to change the URL of this page?');
		} 

		// Update the Pages URI text field with the newly generated URI.
		if(should_change_url == true)
		{	
			uri_field.val(new_uri);
		}
	});
	
	
			
}); // End of $(document).ready(function () {});