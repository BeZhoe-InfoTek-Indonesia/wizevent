<?php

return [
    'title' => 'Perencana Acara',
    'description' => 'Rencanakan acara sebelum publikasi dengan wawasan bertenaga AI',

    'labels' => [
        'title' => 'Judul',
        'event' => 'Acara Terhubung',
        'event_category' => 'Kategori Acara',
        'target_audience_size' => 'Ukuran Target Audiens',
        'target_audience_description' => 'Deskripsi Target Audiens',
        'budget_target' => 'Target Anggaran',
        'revenue_target' => 'Target Pendapatan',
        'event_date' => 'Tanggal Acara',
        'location' => 'Lokasi',
        'description' => 'Deskripsi',
        'notes' => 'Catatan',
        'status' => 'Status',
        'created_at' => 'Dibuat Pada',
        'updated_at' => 'Diperbarui Pada',
    ],

    'placeholders' => [
        'title' => 'Masukkan judul rencana acara',
        'event' => 'Pilih acara untuk dihubungkan (opsional)',
        'event_category' => 'mis., Festival Musik, Konferensi, Workshop',
        'target_audience_size' => 'mis., 500',
        'target_audience_description' => 'Jelaskan target audiens Anda',
        'budget_target' => '0,00',
        'revenue_target' => '0,00',
        'event_date' => 'Pilih tanggal acara',
        'location' => 'mis., Pusat Konvensi Jakarta',
        'description' => 'Jelaskan rencana acara Anda',
        'notes' => 'Catatan tambahan (internal)',
    ],

    'statuses' => [
        'draft' => 'Draf',
        'active' => 'Aktif',
        'completed' => 'Selesai',
        'archived' => 'Diarsipkan',
    ],

    'filters' => [
        'has_linked_event' => 'Memiliki Acara Terhubung',
    ],

    'ai_actions' => [
        'demo_mode_warning' => 'Mode Demo — Konfigurasi API key AI untuk hasil nyata.',

        'concept_builder' => [
            'action_label'        => 'AI Pembuat Konsep',
            'regenerate_label'    => 'Buat Ulang Konsep',
            'apply_label'         => 'Terapkan ke Acara',
            'preview_title'       => 'Konsep Acara AI',
            'description'         => 'Hasilkan konsep acara yang dibuat AI berdasarkan detail rencana Anda.',
            'ai_result_label'     => 'Konsep yang Dihasilkan AI',
            'regenerate_confirm'  => 'Ini akan mengganti konsep AI saat ini. Lanjutkan?',
            'apply_confirm'       => 'Ini akan menimpa deskripsi acara terhubung. Lanjutkan?',
            'apply_success'       => 'Konsep berhasil diterapkan ke acara.',
            'no_ai_result'        => 'Belum ada konsep yang dibuat. Jalankan AI Pembuat Konsep terlebih dahulu.',
            'no_linked_event'     => 'Hubungkan acara ke rencana ini terlebih dahulu.',
            'success'             => 'Konsep berhasil dibuat.',
        ],

        'budget_forecast' => [
            'action_label'        => 'Perkiraan Anggaran AI',
            'apply_label'         => 'Terapkan ke Anggaran',
            'preview_title'       => 'Perkiraan Anggaran AI',
            'apply_confirm'       => 'Ini akan menambahkan :count item pengeluaran dari perkiraan AI. Lanjutkan?',
            'apply_success'       => ':count item berhasil ditambahkan dari perkiraan AI.',
            'no_ai_result'        => 'Belum ada perkiraan. Jalankan Perkiraan Anggaran AI terlebih dahulu.',
            'success'             => 'Perkiraan anggaran berhasil dibuat.',
            'ai_generated_note'   => 'Estimasi dari AI',
            'validation' => [
                'missing_category'      => 'Harap isi Kategori Acara terlebih dahulu.',
                'missing_audience_size' => 'Harap isi Ukuran Target Audiens terlebih dahulu.',
            ],
        ],

        'pricing_strategy' => [
            'action_label'        => 'Strategi Harga AI',
            'apply_label'         => 'Terapkan ke Tiket Acara',
            'preview_title'       => 'Strategi Harga AI',
            'apply_confirm'       => 'Ini akan membuat :count tipe tiket baru pada acara terhubung. Tiket yang ada tidak akan diubah. Lanjutkan?',
            'apply_success'       => ':count tipe tiket berhasil dibuat dari strategi harga AI.',
            'no_ai_result'        => 'Belum ada strategi harga. Jalankan Strategi Harga AI terlebih dahulu.',
            'no_linked_event'     => 'Hubungkan acara ke rencana ini terlebih dahulu untuk membuat tiket.',
            'success'             => 'Strategi harga berhasil dibuat.',
            'target_met'          => 'Target Tercapai',
            'target_not_met'      => 'Target Belum Tercapai',
            'surplus'             => 'Surplus: :amount',
            'deficit'             => 'Defisit: :amount',
            'validation' => [
                'missing_revenue_target' => 'Harap isi Target Pendapatan terlebih dahulu.',
                'missing_audience_size'  => 'Harap isi Ukuran Target Audiens terlebih dahulu.',
            ],
        ],

        'risk_assessment' => [
            'action_label'  => 'Penilaian Risiko AI',
            'preview_title' => 'Penilaian Risiko AI',
            'success'       => 'Penilaian risiko selesai.',
            'overall_score' => 'Skor Risiko Keseluruhan',
            'mitigation'    => 'Mitigasi',
            'no_ai_result'  => 'Belum ada penilaian. Jalankan Penilaian Risiko AI terlebih dahulu.',
        ],
    ],

    'planning_vs_realization' => [
        'title'                    => 'Rencana vs Realisasi',
        'planned_revenue'          => 'Pendapatan Terencana',
        'actual_revenue'           => 'Pendapatan Aktual',
        'revenue_target'           => 'Target Pendapatan',
        'planned_expenses'         => 'Pengeluaran Terencana',
        'actual_expenses'          => 'Pengeluaran Aktual',
        'planned_net_profit'       => 'Laba Bersih Terencana',
        'actual_net_profit'        => 'Laba Bersih Aktual',
        'revenue_achievement_rate' => 'Tingkat Pencapaian Pendapatan',
        'budget_utilization_rate'  => 'Tingkat Pemanfaatan Anggaran',
        'net_margin'               => 'Margin Bersih',
        'tickets_sold_vs_target'   => 'Tiket Terjual vs Target',
        'tickets_sold'             => 'Tiket Terjual',
        'no_linked_event'          => 'Tidak ada acara terhubung — hubungkan acara untuk melacak pendapatan.',
        'no_data'                  => 'Belum ada data tersedia.',
        'expense_by_category'      => 'Pengeluaran per Kategori',
        'revenue_comparison'       => 'Perbandingan Pendapatan',
        'kpi_summary'              => 'Ringkasan KPI',
    ],

    'line_items' => [
        'title' => 'Item Baris',
        'labels' => [
            'type' => 'Tipe',
            'category' => 'Kategori',
            'description' => 'Deskripsi',
            'planned_amount' => 'Jumlah Rencana',
            'actual_amount' => 'Jumlah Aktual',
            'notes' => 'Catatan',
            'sort_order' => 'Urutan',
            'variance' => 'Variansi',
        ],
        'placeholders' => [
            'category' => 'mis., Venue, Pemasaran, Talent',
            'description' => 'Deskripsi opsional',
            'notes' => 'Catatan tambahan',
        ],
        'types' => [
            'expense' => 'Pengeluaran',
            'revenue' => 'Pendapatan',
        ],
    ],
];
