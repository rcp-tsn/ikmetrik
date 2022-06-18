<?php

/*
 * Variables
 */

return [

    'requests' => [
        'request_types' => [
            "Cevabın Başvuru Formunda belirtmiş olduğum adresime gönderilmesini talep ederim." => "Cevabın Başvuru Formunda belirtmiş olduğum adresime gönderilmesini talep ederim.",
            "Cevabın Başvuru Formunun belirtmiş olduğum elektronik posta adresime gönderilmesini talep ederim. (E-posta yöntemini seçmeniz halinde size daha hızlı yanıt verebileceğiz.)" => "Cevabın Başvuru Formunun belirtmiş olduğum elektronik posta adresime gönderilmesini talep ederim. (E-posta yöntemini seçmeniz halinde size daha hızlı yanıt verebileceğiz.)",
            "Elden teslim almak istiyorum. (Vekaleten teslim alınması durumunda noter tasdikli vekaletname veya noter tasdikli yetki belgesi olması gerekmektedir. Kişinin eşi, babası gibi yakınlarına asla bilgi verilmemektedir.)"=>"Elden teslim almak istiyorum. (Vekaleten teslim alınması durumunda noter tasdikli vekaletname veya noter tasdikli yetki belgesi olması gerekmektedir. Kişinin eşi, babası gibi yakınlarına asla bilgi verilmemektedir.)"
        ],
        'company_contact_type'=>
            [
                      "Personel"=>"Personel",
                     "Ziyaretçi"=>"Ziyaretçi",
                      "Müşteri"=>"Müşteri",
                      "Tedarikçi" => "Tedarikçi",
                      "Diğer" => "Diğer",
            ]
    ],
    'shared'=>
        [
            'question_types' => [
                '4' => 'SEÇENEK KUTUSU',
            ],
        ],
    'employees' => [
        'work_type'=>
            [
                '1'=>'Beyaz Yaka',
                '2' => 'Mavi Yaka',
                '0'=>'Seçiniz',

            ],


        'languages'=>
            [
                '0'=>'İngilizce',
                '1'=>'Almanca',
                '2'=>'Fransızca',
                '3'=>'İspanyolca',
                '4'=>'Çince',
                '5'=>'Portekizce',
                '6'=>'Arapça'
            ],
        'level'=>
            [
                '0'=>'Temel',
                '1'=>'Orta',
                '2'=>'İyi',
                '3'=>'Mükemmel'
            ],
        'contract_type' => [
            0 => '',
            1 => 'SÜRESİZ',
            2 => 'SÜRELİ',
        ],
        'home' => [
             '0' => 'HAYIR',
             '1' => 'EVET'
            ],
        'discon_time' => [
            '0' => 'Gün',
            '1'=>'Saat',
            '2'=>'Dakika'
        ],
        'discontinuity'=>
            [
              '0'=>'Seçiniz',
                '1'=>'Ceza Yok',
                '2'=>'Uyarı/İkaz Etmek',
                '3'=>'1 Günlük Ücret Kesintisi',
                '4'=>'2 Günlük Ücret Kesintisi'

            ],
        'University'=>
            [
                '0'=>'HAYIR',
                '1'=>'EVET',
            ],
        'marital_status' => [
            0 => 'BEKAR',
            1 => 'EVLİ',
            2 => 'BOŞANMIŞ',
            3 => 'BELİRTİLMEMİŞ',
        ],
        'gender' => [
            0 => 'BELİRTİLMEMİŞ',
            1 => 'ERKEK',
            2 => 'KADIN'
        ],
        'disability_level' => [
            '0' => 'YOK',
            '1' => '1.KİŞİ',
            '2' => '2.KİŞİ VE ÜSTÜ',
        ],
        'blood_group' => [
            '0' => '0-',
            '1' => '0+',
            '2' => 'A-',
            '3' => 'A+',
            '4' => 'B-',
            '5' => 'B+',
            '6' => 'AB-',
            '7' => 'AB+',
        ],
        'educational_status' => [
            '0' => 'MEZUN',
            '1' => 'ÖĞRENCİ',
        ],

        'completed_education' => [
            '1' => 'İlkokul',
            '2' => 'Ortaokul',
            '3' => 'Lise',
            '4' => 'Önlisans',
            '5' => 'Lisans',
            '6' => 'Yüksek Lisans',
            '7' => 'Doktora',
        ],

        'school_status' => [
            '0' => 'Tamamlandı',
            '1' => 'Devam Ediyor',
            '2' => 'Bıraktı',
        ],

        'account_type' => [
            '' => '',
            '1' => 'ÇEK',
            '2' => 'DİĞER',
            '3' => 'VADELİ',
            '4' => 'VADESİZ',
        ],
        'salary_unit' => [
            '0' => 'TL',
            '1' => 'USD',
            '2' => 'EUR',
            '3' => 'AZN',
            '4' => 'GBP',
            '5' => 'SGD',
            '6' => 'JPY',
            '7' => 'CNH',
            '8' => 'KRW',
        ],
        'salary_period' => [
            '0' => 'AYLIK',
            '1' => 'YILLIK',
            '2' => 'HAFTALIK',
            '3' => 'GÜNLÜK',
            '4' => 'SAATLİK'
        ],
        'salary_type' => [
            0 => 'BRÜT',
            1 => 'NET',
        ],

        'include_agi' => [
            '0' => 'AGİ DAHİL DEĞİL',
            '1' => 'AGİ DAHİL',
        ],

        'leave_type' => [
            0 => '',
            13 => 'Yıllık İzin',
            12 => 'Ücretsiz İzin',
            11 => 'Rapor İzni',
            14 => 'Ücretli İzin',
            1 => 'Doğum Sonrası İzni',
            2 => 'Vefat İzni',
            3 => 'Süt İzni',
            4 => 'Devamsızlık İzni',
            5 => 'İş Arama İzni',
            6 => 'Evlilik İzni',
            7 => 'Doğum İzni',
            8 => 'Askerlik İzni',
            9 => 'Babalık İzni',
            10 => 'Yol İzni',



        ],
        'leave_durum'=>
            [
                '0'=> ' <div class="alert alert-danger" role="alert">REDDEDİLDİ</div> ',
                '1'=> ' <div class="alert alert-warning" role="alert">ONAY BEKLENİYOR</div> ',
                '2'=> ' <div class="alert alert-success" role="alert">KABUL EDİLDİ</div> ',
            ],

        'layoff_type' => [
            0 => '01 - Deneme süreli iş sözleşmesinin işverence feshi',
            1 => '02 - Deneme süreli iş sözleşmesinin işçi tarafından feshi',
            2 => '03 - Belirsiz süreli iş sözleşmesinin işçi tarafından feshi (istifa)',
            3 => '04 - Belirsiz süreli iş sözleşmesinin işveren tarafından haklı sebep bildirilmeden feshi',
            4 => '05 - Belirli süreli iş sözleşmesinin sona ermesi',
            5 => '08 - Emeklilik (yaşlılık) veya toptan ödeme nedeniyle',
        ],

        'documents' => [
            '1' =>  'SGK işe giriş bildirgesi',
            '2' => 'İş sözleşmesi',
            '3' => 'Erkek çalışanlar için askerlik durumu belgesi',
            '4' => 'Adli sicil kaydı',
            '5' => 'Diploma fotokopisi',
            '6' => 'Sağlık raporu',
            '7' => 'İkametgâh belgesi',
            '8' => 'Nüfus kayıt örneği',
            '9' => 'Nüfus cüzdanı fotokopisi',
            '10' => 'Kan grubu',
            '11' => 'İş sözleşmesi',
        ],


    ],

    'work_type' =>
        [
            '1' => 'TAM ZAMANLI',
            '2' => 'PART-TİME',
            '3' => 'STAJYER',
            '4' => 'YARI ZAMANLI',
            '5' => 'KONTRATLI',
            '6' => 'SERBEST ÇALIŞAN'
        ],
    'contract_type' =>
        [
            '1' => 'SÜRELİ',
            '2' => 'SÜRESİZ'
        ],
    'employee_status' =>
        [
            '1'=> 'AKTİF',
            '0'=> 'PASİF',

        ],

    'user' => [
        'select_types' => [
            'job' => 'Meslek',
            'department' => 'Bölüm',
            'work_title' => 'Ünvan',
        ]
    ],
    'crm' => [
        'customer_types' => [
            '1' => 'TEŞVİK',
            '2' => 'KVKK',
            '3' => 'BODROLAMA',
            '4' => 'EĞİTİM',
            '5' => 'DANIŞMANLIK',
        ],
        'status' => [
            '' => 'SEÇİNİZ',
            'POTANSİYEL' => 'POTANSİYEL',
            'GERÇEK' => 'GERÇEK',
        ],
        'contack_types' =>
            [
                '1'=>'BİLDİRİMLER',
                '2'=>'HABERLER',
                '3'=>'ACİL DURUM'
            ],
    ],
    'cv' => [
        'tabs' => [
            'personal_detail' => 'Kişisel Bilgiler',
            'personal_contact' => 'İletişim Bilgileri',
            'work_experience' => 'İş Deneyimi',
            'educational_information' => 'Eğitim Bilgileri',
            'certificate' => 'Sertifikalar',
            'foreign_language' => 'Yabancı Dil',
            'skill' => 'Yetenekler',
            'reference' => 'Referanslar',
            'expert_detail' => 'Uzman Ücret & Yetkinlik Detayı',
        ],
        'genders' => [
            'e' => 'Erkek',
            'k' => 'Kadın',
        ],
        'marital' => [
            0 => 'Bekar',
            1 => 'Evli',
        ],
        'working_styles' => [
            1 => 'Serbest',
            2 => 'Yarı Zamanlı / Parttime',
            3 => 'Dönemsel / Proje Bazlı',
            4 => 'Stajyer',
            5 => 'Tam Zamanlı',
            6 => 'Gönüllü',
        ],
        'language_points' => [
            1 => 'Başlangıç',
            2 => 'Temel',
            3 => 'Orta',
            4 => 'İyi',
            5 => 'İleri',
        ],
        'training_levels' => [
            1 => 'Lise',
            2 => 'Ön Lisans',
            3 => 'Lisans',
            4 => 'Yüksek Lisans',
            5 => 'Doktora',
        ],

    ],
    'months' => [
        0 => 'Seçiniz',
        1 => 'Ocak',
        2 => 'Şubat',
        3 => 'Mart',
        4 => 'Nisan',
        5 => 'Mayıs',
        6 => 'Haziran',
        7 => 'Temmuz',
        8 => 'Ağustos',
        9 => 'Eylül',
        10 => 'Ekim',
        11 => 'Kasım',
        12 => 'Aralık',
    ],
    'question' => [
        'types' => [
            1 => 'Tek doğru cevap',
            2 => 'Birden çok doğru cevap',
            3 => 'Cevapsız',
        ],
        'type_colors' => [
            1 => 'dark',
            2 => 'info',
            3 => 'warning',
        ],
        'difficulties' => [
            '' => 'Seçiniz',
            'kolay' => 'Kolay',
            'orta' => 'Orta',
            'zor' => 'Zor',
        ],
    ],
    'test_types' => [
        1 => 'Standart hesaplama',
        2 => 'Seçeneklerin toplanması',
        3 => 'Seçeneklerin puanlarının toplanması',
    ],
    'test' => [
        'groups' => [
            1 => 'Genel',
            2 => 'İngilizce',
            3 => 'Yetkinlik',
            4 => 'Almanca',
            5 => 'Fransızca',
            6 => 'İtalyanca',
        ]
    ],

    'performance' => [
          'units' => [
                '' => 'Seçiniz',
                'kilo' => 'Kilo',
                'adet' => 'Adet',
                'saat' => 'Saat',
          ],
          'periods' => [
                '' => 'Seçiniz',
                '3' => '3 Ay',
                '6' => '6 Ay',
                '12' => '12 Ay',
          ],
          'status' => [
                0 => '<span class="label label-outline label-warning">Onay sürecinde</span>',
                1 => '<span class="label label-outline label-danger">Düzenleme talep edildi</span>',
                2 => '<span class="label label-outline label-success">Onaylandı</span>',
                3 => '<span class="label label-outline label-success">Değerlendirme için düzenleme talep edildi</span>',
                4 => '<span class="label label-outline label-success">Değerlendirme onay sürecinde</span>',
                5 => '<span class="label label-outline label-success">Değerlendirme Onaylandı</span>',
          ],
          'realizations' => [
                '' => 'Seçiniz',
                1 => 'Düşük Performans',
                2 => 'Tam Performans',
                3 => 'Mükemmel Performans',
          ],

    ],
    'customization' => [
          'dashboard_background' => [
                'bg-1.jpg' => 'Resim 1',
                'bg-2.jpg' => 'Resim 2',
                'bg-3.jpg' => 'Resim 3',
                'bg-4.jpg' => 'Resim 4',
                'bg-5.jpg' => 'Resim 5',
                'bg-6.jpg' => 'Resim 6',
                'bg-7.jpg' => 'Resim 7',
          ]
    ],
    'interview' => [
          'types' => [
                0 => 'Yüz Yüze',
                1 => 'Online',
                2 => 'Video',
          ],
          'types_interview' => [
                0 => 'Yüz Yüze',
                1 => 'Online',
          ],
          'status' => [
                0 => 'Bekliyor',
                1 => 'Gerçekleşti',
                2 => 'Gerçekleşmedi',
          ]

    ],

    'target_questions' =>
        [
            '1 ' => 'Görev alanı ile ilgili  hijyen ve isg kurallarını yerine getirir.',
            '2'  => 'Sorumlu olduğu alanda görevini eksiksiz  yerine getirir.',
            '3'  => 'Astlarına  görevlerini yürütmelerine yardımcı olur.',
            '4'  => 'Görevi ile ilgili çalışmaları özverili olarak yerine getirir.0',
            '5'  => 'Araç-gereç ve diğer kaynakları verimli kullanır ve israftan kaçınır.',
            '6'  => 'İşlerin yürütülmesinde astlarına öneriler sunar ve sorumluluklar  alır',
            '7'  => 'Kurumdaki işlerin etkin yürütülmesinde mesleki alanında astlarına görev dağılımını adaletli yapar ',
            '8'  => 'İşi ile ilgili detaylara hakimdir.Detaylar konusunda titiz davranır.',
            '9'  =>  'Zamanı verimli ve etkili kullanır.',
            '10' =>  'Yazılı sözlü ve sosyal iletişimi güçlüdür.',
            '11' =>  'İşiyle ilgili yenilik ve değişikliklerle ilgili araştırma yapar.',
            '12' =>  'Astlarına karşı her daim naziktir',
            '13' =>  'Yaptığı iş ve işlemlere ilişkin geri bildirimde bulunur.',
            '14' =>  'Çalışmalarını ve yaptığı iş ile ilgili  geliştirilmesi gereken hususları önerileriyle birlikte astlarına bildirir.',
            '15' =>  'İşlerin planlanan zamanda  sonuçlandırılması için çalışır.',
            '16' =>  'İşlerin düzgün yürümesi için gerekli tedbirleri alır, sonuçları izler ve değerlendirir.',
            '17' =>  'Kurum içi ve dışı kişilere nasıl davranması gerektiğini bilir ve nezaket dışı davranışlar sergilemez.',
            '18' =>  'İşletme içi olası olumsuzluklarda duyarlı davranır ve ilgili birimleri bilgilendirir.',
            '19' =>  'Hata yapar veya başarısız olursa sorumluluğu üstlenir.',
            '20' =>  'Görevlendirilen   toplantılara ve eğitim çalışmalarına katılırım.',
        ],
    'question_type'=>
        [
          '1'=> 'AST/AMİR DEĞERLENDİRME FORMU',
          '2'=> 'ÜST/AST  DEĞERLENDİRME FORMU',
        ],
    'question_grub_type' =>
        [
            '1' => 'AİDİYET VE GÖREV BİLİNCİ',
            '2' => 'ZAMAN YÖNETİMİ',
            '3' => 'İLETİŞİM YÖNETİMİ'
        ],
    'question_grub_type2' =>
        [
            'ÜCRET KESİNTİSİ' => 'ÜCRET KESİNTİSİ',
            'ceza' => 'CEZA',
            'İŞTEN ÇIKARMA' => 'İŞTEN ÇIKARMA',
            'İHTAR CEZALARI'=>'İHTAR CEZALARI'
        ],
    'blueExampleQuestions' => [
        '1'=> "Operatör, çalışma alanı ile ilgili hattan uzakta eğitimler aldı mı? (Fabrika genel bilgilendirme, müşteriler, kalite politikası, çevre politikası,   iş sağlığı ve güvenliği kuralları)",
        '2'=> "Operatör, çalışma talimatları, kontrol gamlarının okunması ve anormal durumlarda ne yapması gerektiği ile ilgili eğitim aldı mı?",
        '3'=> "Operatör, çalışmaya başlamadan önce, yapması gereken günlük koruyucu bakımlar ile ilgili eğitim aldı mı?",
        '4'=> "Operatör, çalışmaya başlamadan önce, nelere dikkat etmesi gerektiğini biliyor mu",
        '5'=> "Operatör, 1. seviye kriterlerini tamamladı mı? Bir üst seviyeye geçebilir mi?",
        '6'=> "Operatör, makine ayarı yapan kişi / kalite personeli yardımıyla üretime başlayabilir mi",
        '7'=> "Operasyonların en az %50' sinde kontrollü çalışabiliyor mu?",
        '8'=> "Preslerdeki günlük koruyucu bakımları kontrollü yapabiliyor mu?",
        '9'=> "Ürün kalitesi, red durumu veya anormal durumlar hakkında sorulan sorulara cevap verebiliyor mu?",
        '10'=> "Operatör , 2. seviye kriterlerini tamamladı mı? Bir üst seviyeye geçebilir mi?",
        '11'=> "Operatör seri başlangıcı yapılmış operasyonlarda, kendi başına üretim yapabiliyor mu?",
        '12'=> "Operasyonların kaliteli bir şekilde en az %75 inde çalışabiliyor mu?",
        '13'=> "Çalışma talimatlarına, hakim mi?",
        '14'=>"Günlük koruyucu bakımları yapabiliyor mu? Ekipman ile ilgili uygunsuzlukları amirine zamanında bildiriyor mu?",
        '15'=>"Operatör , 3. seviye kriterlerini tamamladı mı? Bir üst seviyeye geçebilir mi?",
        '16'=> "Kendi başışa operasyonda çalışabilir ve eğitm verebilir mi",
        '17'=> "Tüm operasyonlardaki ekipmanlara ve uygunsuzluklara müdahale edebiliyor mu?",
        '18'=>  "Kalite uygunsuzluklarını analiz edebilmeli ve müşteri beklentilerini açıklayabiliyor mu?",
        '19'=> "Anormal durumları açıklayabiliyor mu?",
        '20'=> "Makine baskı ayarını tek başına yapabilmeli ve makine kurs ayarlarını dikkate alarak çalışabilmelidir.",

    ],
        'WhiteExampleQuestions' => [
    '1'=> "BÜRO ALETLERİNİ KULLANABİLME",
    '2'=> "DİKSİYON  VE İLETİŞİM TEKNİKLERİ BİLGİSİ",
    '3'=> "EVRAK VE ÖZLÜK İŞ TAKİP BİLGİSİ	PDKS KULLANMI VE VERİ GİRİŞ  BİLGİSİ",
    '4'=> "İDARİ İŞLERİN ORGANİZASYONU	İŞE ALIM SÜREÇLERİ",
    '5'=> "PERSONEL SEÇME VE MÜLAKAT TEKNİKLERİ BİLGİSİ",
    '6'=> "İŞE GİRİŞ / ÇIKIŞ İŞLEMLERİ",
    '7'=> "PUANTAJ VE BORDRO SÜREÇLERİ",
    '8'=> "4857 SAYIYI İŞ KANUNU VE 6331 SAYILI İŞ GÜVENLİK KANUN BİLGİSİ",
    '9'=> "ÇMA ANKET VE DEĞERLENDİRİLMESİ	TEMİZLİK VE SERVİS HİZMET BİLGİSİ",
    '10'=> "SANTRAL KULLANMA BİLGİSİ?",
    '11'=> "OFİS ARAÇ VE GEREÇLERİ KULLANMABİLGİSİ",
    '12'=> "ERP KULLANMA BECERİSİ",
    '13'=> "IATF 16949 BİLGİSİ",
    '14'=>"IATF 16949 İÇ TETKİKÇİ	EĞİTİM VEREBİLİR",
    '15'=>"DENETLEME YAPABİLİR	SÜRECİ TEK BAŞINA YÖNETEBİLİR",

],

    'ceza_type'=>[
        '1'=>'UYARI',
        '2'=>'ÜCRET KESİNTİSİ',
        '3'=>'İŞTEN ÇIKARMA'
    ],

];

