<?php

return [
    'title' => 'Manajemen Talent',
    'section_title' => 'Lineup Talent',

    'columns' => [
        'performer' => 'Penampil',
        'planned_fee' => 'Biaya Rencana',
        'actual_fee' => 'Biaya Aktual',
        'slot_time' => 'Waktu Slot',
        'duration' => 'Durasi (menit)',
        'contract_status' => 'Status Kontrak',
        'budget_linked' => 'Terhubung Anggaran',
        'performance_order' => 'Urutan',
        'rider_notes' => 'Catatan Rider',
        'notes' => 'Catatan',
    ],

    'placeholders' => [
        'performer' => 'Cari dan pilih penampil...',
        'planned_fee' => 'mis. 5000000',
        'actual_fee' => 'mis. 5000000',
        'slot_time' => 'mis. 19:00',
        'duration' => 'mis. 60',
        'performance_order' => 'mis. 1',
        'rider_notes' => 'Daftar kebutuhan teknis dan hospitality...',
        'notes' => 'Catatan tambahan...',
        'contract_status' => 'Pilih status',
    ],

    'contract_statuses' => [
        'draft' => 'Konsep',
        'negotiating' => 'Negosiasi',
        'confirmed' => 'Terkonfirmasi',
        'cancelled' => 'Dibatalkan',
    ],

    'actions' => [
        'add' => 'Tambah Talent',
        'edit' => 'Edit Talent',
        'delete' => 'Hapus Talent',
        'link_to_budget' => 'Hubungkan ke Anggaran',
        'unlink_from_budget' => 'Putuskan dari Anggaran',
    ],

    'messages' => [
        'already_assigned' => 'Penampil ini sudah ditambahkan ke rencana ini.',
        'budget_linked' => 'Biaya talent dihubungkan ke baris anggaran.',
        'budget_unlinked' => 'Tautan anggaran dihapus.',
        'deleted' => 'Talent dihapus dari rencana.',
        'fee_synced' => 'Baris anggaran diperbarui dengan biaya baru.',
    ],

    'empty_state' => [
        'heading' => 'Belum ada talent',
        'description' => 'Tambahkan penampil ke lineup rencana acara Anda.',
    ],

    'summary' => [
        'total_planned'      => 'Total Biaya Rencana',
        'total_confirmed'    => 'Total Biaya Terkonfirmasi',
        'budget_utilization' => 'Utilisasi Anggaran Talent',
    ],

    'status_board' => [
        'no_talent'      => 'Belum Ada Talent',
        'no_talent_hint' => 'Tambahkan penampil ke rencana ini untuk melihat papan status.',
        'none'           => '(tidak ada)',
    ],
];
