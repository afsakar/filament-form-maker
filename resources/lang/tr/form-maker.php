<?php

// translations for Afsakar/FormMaker
return [
    'navigation_group' => 'Form Yönetimi',

    'resources' => [
        'builder' => [
            'section_title' => 'Form Bilgileri',
            'model_label' => 'Form Oluşturucu',
            'plural_model_label' => 'Form Oluşturucular',
            'inputs' => [
                'name' => 'Adı',
            ],
            'options' => [
                'title' => 'Form Ayarları',
                'description' => 'Daha Fazla Form Ayarları',
                'notification_name' => 'Bildirim Adı',
                'background_color' => 'Arka Plan Rengi',
                'mail_notifications' => 'Mail Bildirimleri',
                'user_emails' => 'Kullanıcı E-postaları',
                'user_emails_hint' => 'Forum\'un doldurulduktan sonra iletilmesini istediğiniz kullanıcıları seçiniz.',
                'static_fields' => [
                    'full_span' => 'Tam Genişlik',
                    'placeholder' => 'Placeholder',
                    'notification_name' => 'Bildirim Adı',
                    'is_required' => 'Zorunlu Alan',
                    'hidden_label' => 'Gizli Etiket',
                    'field_id' => 'Alan ID',
                    'html_id' => 'HTML ID',
                    'column_span' => 'Sütun Genişliği',
                    'helper_text' => 'Yardımcı Metin',
                ],
                'select_field' => [
                    'title' => 'Seçim Kutusu Ayarları',
                    'is_multiple' => 'Çoklu Seçim',
                    'is_searchable' => 'Arama Yapılabilir',
                    'data_source' => 'Veri Kaynağı',
                ],
                'checkbox_radio_field' => [
                    'title' => 'Onay Kutusu / Seçim Düğmesi Ayarları',
                    'data_source' => 'Veri Kaynağı',
                    'one_column' => 'Tek Sütun',
                    'two_columns' => 'İki Sütun',
                    'three_columns' => 'Üç Sütun',
                    'four_columns' => 'Dört Sütun',
                ],
                'text_field' => [
                    'title' => 'Metin Alanı Ayarları',
                    'field_type' => 'Alan Tipi',
                    'types' => [
                        'text' => 'Metin',
                        'email' => 'E-posta',
                        'url' => 'URL',
                        'number' => 'Sayı',
                    ],
                    'max_value' => 'Maksimum Değer',
                    'min_value' => 'Minimum Değer',
                ],
                'file_field' => [
                    'title' => 'Dosya Alanı Ayarları',
                    'max_size' => 'Maksimum Dosya Sayısı',
                    'hint' => 'KB cinsinden',
                    'accepted_file_types' => 'İzin Verilen Uzantılar',
                    'image' => 'Resim',
                ],
                'visibility' => [
                    'title' => 'Koşullu Görünürlük',
                    'active' => 'Koşullu Görünürlük Aktif',
                    'fieldId' => 'Koşul Bağlanacak Alan',
                    'values' => 'Görünürlük Değeri',
                    'values_helper_text' => 'Eğer herhangi bir değer seçildiği takdirde görünür olmasını istiyorsanız bu alanı boş bırakın.',
                ],
            ],
            'sections' => [
                'title' => 'Bölümler',
                'count' => 'Bölüm Sayısı',
                'add_action_label' => 'Bölüm Ekle',
                'new_section_label' => 'Yeni Bölüm',
                'inputs' => [
                    'title' => 'Başlık',
                    'columns' => 'Sütun Sayısı',
                    'columns_hint' => 'Bölümdeki sütun sayısı',
                ],
            ],
            'fields' => [
                'title' => 'Alanlar',
                'add_action_label' => 'Alan Ekle',
                'new_field_label' => 'Yeni Alan',
                'options' => [
                    'title' => 'Alan Ayarları',
                    'description' => 'Daha Fazla Alan Ayarları',
                ],
                'inputs' => [
                    'name' => 'Alan Adı',
                    'type' => 'Alan Tipi',
                ],
            ],
        ],
        'collections' => [
            'model_label' => 'Form Koleksiyonu',
            'plural_model_label' => 'Form Koleksiyonları',
        ],
        'form_data' => [
            'model_label' => 'Form Verileri',
            'plural_model_label' => 'Form Verileri',
        ],
    ],
];
