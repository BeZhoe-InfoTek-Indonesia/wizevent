<?php

return [
    'action_label'              => 'Sinkronisasi ke Live',
    'create_event_label'        => 'Buat Acara dari Rencana',
    'create_from_plan'          => 'Buat Acara dari Rencana',
    'confirm_label'             => 'Sinkronkan',
    'confirm_sync'              => 'Konfirmasi Sinkronisasi',
    'cancel'                    => 'Batal',

    'modal_heading'             => 'Sinkronisasi Rencana ke Acara Live',
    'create_event_heading'      => 'Buat Acara dari Rencana',
    'create_event_description'  => 'Ini akan membuat Acara baru (draft) menggunakan judul, tanggal, dan lokasi rencana ini.',
    'modal_title'               => 'Sinkronisasi Rencana ke Acara Live',
    'diff_modal_title'          => 'Pratinjau Perubahan',
    'create_event_modal_title'  => 'Buat Acara dari Rencana',

    'has_changes'       => 'Ada perubahan.',
    'no_changes'        => 'Tidak ada perubahan dari acara saat ini.',
    'no_changes_found'  => 'Tidak ditemukan perbedaan antara rencana dan acara live.',

    'success'               => 'Sinkronisasi selesai.',
    'no_sections_selected'  => 'Pilih minimal satu bagian untuk disinkronkan.',
    'create_event_success'  => 'Acara ":title" dibuat dan dihubungkan ke rencana ini.',

    'sections' => [
        'concept'    => 'Konsep & Deskripsi',
        'metadata'   => 'Metadata Acara',
        'performers' => 'Penampil',
        'tickets'    => 'Tipe Tiket',
        'rundown'    => 'Rundown / Agenda',
    ],

    'sync_sections' => [
        'concept'    => 'Sinkronkan deskripsi acara dari konsep rencana',
        'metadata'   => 'Sinkronkan judul, tanggal, dan lokasi',
        'performers' => 'Sinkronkan penampil yang dikonfirmasi',
        'tickets'    => 'Sinkronkan tipe tiket dari strategi harga',
        'rundown'    => 'Sinkronkan item rundown',
    ],

    'diff' => [
        'added'     => 'Baru',
        'updated'   => 'Berubah',
        'unchanged' => 'Tidak berubah',
        'skipped'   => 'Dilewati',
        'summary'   => ':create dibuat, :update diperbarui, :skip tidak berubah',
    ],

    'warnings' => [
        'no_event_linked'       => 'Hubungkan rencana ini ke acara terlebih dahulu, atau buat acara baru dari rencana ini.',
        'concept_overwrite'     => 'Deskripsi acara dimodifikasi setelah sinkronisasi terakhir. Sinkronisasi akan menimpa perubahan tersebut.',
        'published_event'       => 'Acara ini sudah dipublikasikan. Beberapa perubahan mungkin memengaruhi pengunjung.',
        'no_selected_scenario'  => 'Tidak ada skenario harga yang dipilih. Pilih skenario di Strategi Harga sebelum menyinkronkan tiket.',
    ],

    'validation' => [
        'no_linked_event'       => 'Rencana harus dihubungkan ke acara sebelum sinkronisasi.',
        'missing_title'         => 'Rencana harus memiliki judul.',
        'plan_must_have_event'  => 'Rencana harus dihubungkan ke acara sebelum sinkronisasi.',
        'permission_denied'     => 'Anda tidak memiliki izin untuk mengedit acara target.',
        'invalid_status'        => 'Hanya rencana dengan status draft atau aktif yang dapat disinkronkan.',
        'plan_missing_title'    => 'Rencana harus memiliki judul untuk membuat acara.',
        'plan_missing_date'     => 'Rencana harus memiliki tanggal acara untuk membuat acara.',
        'plan_missing_location' => 'Rencana harus memiliki lokasi untuk membuat acara.',
    ],

    'success_messages' => [
        'synced'        => 'Berhasil menyinkronkan :sections ke acara.',
        'event_created' => 'Acara dibuat dan dihubungkan ke rencana ini.',
    ],

    'history' => [
        'title'           => 'Riwayat Sinkronisasi',
        'no_syncs'        => 'Belum ada sinkronisasi yang tercatat.',
        'synced_at'       => 'Disinkronkan pada',
        'synced_by'       => 'Disinkronkan oleh',
        'sections_synced' => 'Bagian yang disinkronkan',
        'target_event'    => 'Acara Target',
    ],

    'acknowledge_overwrite' => 'Saya memahami ini akan menimpa pengeditan manual pada acara live.',
];
