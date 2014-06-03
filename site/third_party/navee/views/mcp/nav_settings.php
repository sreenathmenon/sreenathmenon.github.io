<?php
$data = '<p>' . lang('cp_nav_settings_instructions') . '</p>';
$data .= form_open('C=addons_modules' . AMP . 'M=show_module_cp' . AMP . 'module=navee' . AMP . 'method=nav_settings_handler');
$data .= '<input type="hidden" name="nav_id" value="' . $nav_id . '" />';

// Define table template
$this->table->set_template($cp_table_template);


// Channels
if (sizeof($channels) > 0)
{
    $this->table->set_heading(array(lang('cp_nav_settings_channels'), lang('cp_nav_settings_disable')));
    foreach ($channels as $k => $v)
    {
        $this->table->add_row(array(
                'data'  => ucfirst($v['title']),
                'style' => 'width: 90%',
            ),
            array(
                'data'  => form_checkbox('channel_' . $k, $k, $v['is_selected']),
                'style' => 'width: 10%',
            )
        );
    }

    $data .= $this->table->generate();
}


// Templates
if (sizeof($templates))
{
    $this->table->set_heading(array(lang('cp_nav_settings_templates'), lang('cp_nav_settings_disable')));
    foreach ($templates as $k => $v)
    {
        $this->table->add_row(array(
                'data'  => $v['template_group'] . ' / ' . $v['template_name'],
                'style' => 'width: 90%',
            ),
            array(
                'data'  => form_checkbox('template_' . $k, $k, $v['is_selected']),
                'style' => 'width: 10%',
            )
        );
    }

    $data .= $this->table->generate();
}


$data .= '<div class="tableFooter"><div class="tableSubmit">' . form_submit(array('name' => 'submit', 'value' => lang('submit'), 'class' => 'submit')) . '</div></div>';
$data .= form_close();

echo $data;

/* End of file index.php */
/* Location: ./system/expressionengine/third_party/navee_select/views/mcp/display_settings.php */