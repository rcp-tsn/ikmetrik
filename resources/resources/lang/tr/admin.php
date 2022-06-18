<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Turkish Language Admin Translations
    |--------------------------------------------------------------------------
    */

    'dashboard' => [
        'index' => 'Dashboard',
    ],
    'root' => 'Dashboard',
    'datatables' => [               // DataTables, files can be found @ https://datatables.net/plug-ins/i18n/
        'sInfo'                     => '_TOTAL_ Kayıttan _START_ - _END_ Arası Kayıtlar',
        'sInfoEmpty'                => 'Kayıt Yok',
        'sInfoFiltered'             => '( _MAX_ Kayıt İçerisinden Bulunan )',
        'sInfoPostFix'              => '',
        'sLengthMenu'               => 'Sayfada _MENU_ Kayıt Göster',
        'sProcessing'               => '<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>',
        'sSearch'                   => 'Bul:',
        'sUrl'                      => '',
        'sZeroRecords'              => 'Eşleşen Kayıt Bulunmadı',
        'oPaginate' => [
            'sFirst'                => 'İlk',
            'sLast'                 => 'Son',
            'sNext'                 => 'Sonraki',
            'sPrevious'             => 'Önceki'
        ]
    ],
    'fields' => [
        'created_at'                => 'Oluşturulma',
        'deleted_at'                => 'Silinme',
        'no'                        => 'Hayır',
        'published_at'              => 'Yayınlanma Tarihi',
        'reset'                     => 'Reset',
        'save'                      => 'Kaydet',
        'updated_at'                => 'Güncelleme',
        'uploaded'                  => 'Yüklenmiş Dosya',
        'yes'                       => 'Evet'
    ],
    'ops' => [
        'delete_confirmation'       => 'Seçili kaydı silmek istediğine emin misiniz?',
        'delete_title'              => 'Kayıt Silme İsteği!',
        'confirmation'              => 'Emin misiniz?',
        'create'                    => 'Yeni Ekle',
        'delete'                    => 'Sil',
        'edit'                      => 'Düzenle',
        'modified'                  => 'Son Düzenlenme',
        'name'                      => 'İşlemler',
        'order'                     => 'Sırala',
        'show'                      => 'Göster',
        'cancel'                      => 'Vazgeç'
    ],
];
