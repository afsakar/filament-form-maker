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
                'user_emails' => 'Bildirim E-postaları',
                'user_emails_hint' => 'Formun doldurulduktan sonra iletilmesini istediğiniz kullanıcıları seçiniz.',
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
                'notifications' => [
                    'label' => 'Başarılı Bildirim Ayarları',
                    'title' => 'Başlık',
                    'body' => 'Metin',
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
            'inputs' => [
                'name' => 'Adı',
                'type' => 'Tipi',
                'type_options' => [
                    'list' => 'Liste',
                    'model' => 'Model',
                ],
                'values' => [
                    'title' => 'Değerler',
                    'label' => 'Etiket',
                    'value' => 'Değer',
                    'add_value' => 'Değer Ekle',
                ],
            ],
        ],
        'form_data' => [
            'model_label' => 'Form Verileri',
            'plural_model_label' => 'Form Verileri',
            'inputs' => [
                'name' => 'Form Adı',
                'status' => 'Durum',
                'ip_address' => 'IP Adresi',
                'user_agent' => 'Tarayıcı Bilgisi',
                'file' => [
                    'label' => 'Dosya',
                    'download' => 'Dosyayı İndir',
                ],
                'created_at' => 'Oluşturulma Tarihi',
                'updated_at' => 'Güncellenme Tarihi',
            ],
            'section_title' => 'Form Bilgileri',
            'actions' => [
                'open' => [
                    'label' => 'Yeniden Aç',
                    'modal' => [
                        'title' => 'Formu Yeniden Aç',
                        'body' => 'Formu yeniden açmak istediğinize emin misiniz?',
                        'success' => 'Form başarıyla yeniden açıldı.',
                    ],
                ],
                'close' => [
                    'label' => 'Kapat',
                    'modal' => [
                        'title' => 'Formu Kapat',
                        'body' => 'Formu kapatmak istediğinize emin misiniz?',
                        'success' => 'Form başarıyla kapatıldı.',
                    ],
                ],
            ],
        ],
    ],

    'notification' => [
        'toast' => [
            'title' => 'İşlem Başarılı',
            'body' => 'Mesajınız gönderildi. En kısa sürede sizinle iletişime geçeceğiz.',
        ],
        'mail' => [
            'title' => 'Yeni Bildirim - :form_name',
            'greeting' => 'Merhaba!',
            'view' => 'Görüntüle',
        ],
    ],

    'enums' => [
        'field_types' => [
            'text' => 'Metin',
            'phone' => 'Telefon',
            'textarea' => 'Metin Alanı',
            'select' => 'Seçim Kutusu',
            'file' => 'Dosya',
            'date' => 'Tarih',
            'checkbox' => 'Onay Kutusu',
            'radio' => 'Seçim Düğmesi',
        ],
        'form_status' => [
            'closed' => 'Kapatıldı',
            'open' => 'Açık',
        ],
    ],
];
