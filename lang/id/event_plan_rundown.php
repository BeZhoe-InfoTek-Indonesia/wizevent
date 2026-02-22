<?php

return [
    'title' => 'Rundown Acara',
    'section_title' => 'Jadwal & Agenda',

    'columns' => [
        'sort_order' => '#',
        'title' => 'Judul',
        'time_range' => 'Waktu',
        'duration' => 'Durasi',
        'type' => 'Tipe',
        'talent' => 'Penampil',
        'description' => 'Deskripsi',
        'notes' => 'Catatan',
        'start_time' => 'Mulai',
        'end_time' => 'Selesai',
    ],

    'placeholders' => [
        'title' => 'mis. Pembukaan',
        'description' => 'Deskripsi singkat sesi ini...',
        'start_time' => 'mis. 19:00',
        'end_time' => 'mis. 19:30',
        'type' => 'Pilih tipe',
        'talent' => 'Hubungkan ke penampil (opsional)',
        'notes' => 'Catatan produksi...',
        'sort_order' => 'mis. 1',
    ],

    'types' => [
        'ceremony' => 'Seremonial',
        'performance' => 'Penampilan',
        'break' => 'Istirahat',
        'setup' => 'Persiapan',
        'networking' => 'Networking',
        'registration' => 'Registrasi',
        'other' => 'Lainnya',
    ],

    'actions' => [
        'add' => 'Tambah Item Rundown',
        'edit' => 'Edit Item',
        'delete' => 'Hapus Item',
        'generate_ai' => 'Generator Rundown AI',
        'apply_ai' => 'Terapkan ke Rundown',
    ],

    'messages' => [
        'time_overlap_warning' => 'Terdeteksi tumpang tindih waktu dengan ":item"',
        'ai_applied' => ':count item rundown ditambahkan dari saran AI.',
        'deleted' => 'Item rundown dihapus.',
    ],

    'empty_state' => [
        'heading'     => 'Belum ada item rundown',
        'description' => 'Tambahkan agenda atau gunakan Generator Rundown AI untuk memulai.',
    ],

    'no_rundown'        => 'Belum ada item rundown.',
    'timeline_heading'  => 'Timeline Rundown Acara',
    'time_range'        => 'Waktu',
    'title'             => 'Judul',
    'type'              => 'Tipe',
    'validation' => [
        'missing_category'      => 'Harap isi Kategori Acara terlebih dahulu.',
        'missing_audience_size' => 'Harap isi Ukuran Target Audiens terlebih dahulu.',
    ],

    'ai_modal' => [
        'title'          => 'Generator Rundown AI',
        'description'    => 'AI akan menghasilkan agenda acara berdasarkan detail rencana dan penampil yang dikonfirmasi.',
        'preview_label'  => 'Pratinjau Rundown yang Dihasilkan',
        'generating'     => 'Membuat rundown...',
    ],
];
