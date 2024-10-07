<?php

// translations for Afsakar/FormMaker
return [
    'navigation_group' => 'Form Management',

    'resources' => [
        'builder' => [
            'section_title' => 'Form Information',
            'model_label' => 'Form Builder',
            'plural_model_label' => 'Form Builders',
            'inputs' => [
                'name' => 'Name',
            ],
            'options' => [
                'title' => 'Form Settings',
                'description' => 'More Form Settings',
                'notification_name' => 'Notification Name',
                'background_color' => 'Background Color',
                'mail_notifications' => 'Mail Notifications',
                'user_emails' => 'Notification Emails',
                'user_emails_hint' => 'Select the users you want to send the form to after it is submitted.',
                'static_fields' => [
                    'full_span' => 'Full Width',
                    'placeholder' => 'Placeholder',
                    'notification_name' => 'Notification Name',
                    'is_required' => 'Required Field',
                    'hidden_label' => 'Hidden Label',
                    'field_id' => 'Field ID',
                    'html_id' => 'HTML ID',
                    'column_span' => 'Column Width',
                    'helper_text' => 'Helper Text',
                ],
                'notifications' => [
                    'label' => 'Successful Notification Settings',
                    'title' => 'Title',
                    'body' => 'Body',
                ],
                'select_field' => [
                    'title' => 'Select Box Settings',
                    'is_multiple' => 'Multiple Selection',
                    'is_searchable' => 'Searchable',
                    'data_source' => 'Data Source',
                ],
                'checkbox_radio_field' => [
                    'title' => 'Checkbox / Radio Button Settings',
                    'data_source' => 'Data Source',
                    'one_column' => 'One Column',
                    'two_columns' => 'Two Columns',
                    'three_columns' => 'Three Columns',
                    'four_columns' => 'Four Columns',
                ],
                'text_field' => [
                    'title' => 'Text Field Settings',
                    'field_type' => 'Field Type',
                    'types' => [
                        'text' => 'Text',
                        'email' => 'Email',
                        'url' => 'URL',
                        'number' => 'Number',
                    ],
                    'max_value' => 'Maximum Value',
                    'min_value' => 'Minimum Value',
                ],
                'file_field' => [
                    'title' => 'File Field Settings',
                    'max_size' => 'Maximum File Size',
                    'hint' => 'In KB',
                    'accepted_file_types' => 'Accepted Extensions',
                    'image' => 'Image',
                ],
                'visibility' => [
                    'title' => 'Conditional Visibility',
                    'active' => 'Conditional Visibility Active',
                    'fieldId' => 'Field to Bind Condition',
                    'values' => 'Visibility Value',
                    'values_helper_text' => 'Leave this field empty if you want it to be visible when any value is selected.',
                ],
            ],
            'sections' => [
                'title' => 'Sections',
                'count' => 'Section Count',
                'add_action_label' => 'Add Section',
                'new_section_label' => 'New Section',
                'inputs' => [
                    'title' => 'Title',
                    'columns' => 'Column Count',
                    'columns_hint' => 'Number of columns in the section',
                ],
            ],
            'fields' => [
                'title' => 'Fields',
                'add_action_label' => 'Add Field',
                'new_field_label' => 'New Field',
                'options' => [
                    'title' => 'Field Settings',
                    'description' => 'More Field Settings',
                ],
                'inputs' => [
                    'name' => 'Field Name',
                    'type' => 'Field Type',
                ],
            ],
        ],
        'collections' => [
            'model_label' => 'Form Collection',
            'plural_model_label' => 'Form Collections',
            'inputs' => [
                'name' => 'Name',
                'type' => 'Type',
                'type_options' => [
                    'list' => 'List',
                    'model' => 'Model',
                ],
                'values' => [
                    'title' => 'Values',
                    'label' => 'Label',
                    'value' => 'Value',
                    'add_value' => 'Add Value',
                ],
            ],
        ],
        'form_data' => [
            'model_label' => 'Form Data',
            'plural_model_label' => 'Form Data',
            'inputs' => [
                'name' => 'Form Name',
                'status' => 'Status',
                'ip_address' => 'IP Address',
                'user_agent' => 'Browser Info',
                'file' => [
                    'label' => 'File',
                    'download' => 'Download File',
                ],
                'created_at' => 'Created At',
                'updated_at' => 'Updated At',
            ],
            'section_title' => 'Form Information',
            'actions' => [
                'open' => [
                    'label' => 'Reopen',
                    'modal' => [
                        'title' => 'Reopen Form',
                        'body' => 'Are you sure you want to reopen the form?',
                        'success' => 'Form successfully reopened.',
                    ],
                ],
                'close' => [
                    'label' => 'Close',
                    'modal' => [
                        'title' => 'Close Form',
                        'body' => 'Are you sure you want to close the form?',
                        'success' => 'Form successfully closed.',
                    ],
                ],
            ],
        ],
    ],

    'notification' => [
        'toast' => [
            'title' => 'Operation Successful',
            'body' => 'Your message has been sent. We will contact you as soon as possible.',
        ],
        'mail' => [
            'title' => 'New Notification - :form_name',
            'greeting' => 'Hello!',
            'view' => 'View',
        ],
    ],

    'enums' => [
        'field_types' => [
            'text' => 'Text',
            'phone' => 'Phone',
            'textarea' => 'Text Area',
            'select' => 'Select Box',
            'file' => 'File',
            'date' => 'Date',
            'checkbox' => 'Checkbox',
            'radio' => 'Radio Button',
        ],
        'form_status' => [
            'closed' => 'Closed',
            'open' => 'Open',
        ],
    ],
];
