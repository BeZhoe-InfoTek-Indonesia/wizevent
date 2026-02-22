<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Actions as SchemaActions;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Enums\FontWeight;

class ReviewPayment extends Page implements HasSchemas
{
    use InteractsWithSchemas;
    protected static string $resource = OrderResource::class;

    protected string $view = 'filament.resources.orders.pages.review-payment';
    
    public Order $record;
    public ?string $status = null;
    public ?string $cancellation_reason = null;

    public function mount(Order $record): void
    {
        $this->record = $record->load(['files', 'paymentProof']);
        $this->status = $this->record->status;
        
        if ($this->record->status !== 'pending_verification') {
            redirect()->to(OrderResource::getUrl('index'));
        }
    }
    
    public function getTitle(): string 
    {
        return __('payment_proof.review') . ' #' . $this->record->order_number;
    }

    public function schema(Schema $schema): Schema
    {
        return $schema
            ->record($this->record)
            ->schema([
                Grid::make(['lg' => 3])->schema([
                    Group::make([
                        Section::make(__('Order Information'))
                            ->icon('heroicon-m-shopping-cart')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextEntry::make('order_number')
                                        ->label(__('order.order_number'))
                                        ->weight(FontWeight::Bold)
                                        ->copyable(),
                                    TextEntry::make('total_amount')
                                        ->label(__('order.total_amount'))
                                        ->money('IDR')
                                        ->weight(FontWeight::ExtraBold)
                                        ->color('success'),
                                    TextEntry::make('status')
                                        ->label(__('order.status_label'))
                                        ->badge()
                                        ->color(fn (string $state): string => match ($state) {
                                            'pending_payment' => 'warning',
                                            'pending_verification' => 'info',
                                            'completed' => 'success',
                                            'cancelled' => 'danger',
                                            'expired' => 'gray',
                                            default => 'gray',
                                        })
                                        ->formatStateUsing(fn (string $state): string => __("order.status.{$state}")),
                                    TextEntry::make('created_at')
                                        ->label(__('order.created_at'))
                                        ->dateTime(),
                                ]),
                            ]),

                        Section::make(__('Customer Details'))
                            ->icon('heroicon-m-user')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextEntry::make('user.name')
                                        ->label(__('profile.name'))
                                        ->weight(FontWeight::Bold),
                                    TextEntry::make('user.email')
                                        ->label(__('profile.email'))
                                        ->icon('heroicon-m-envelope')
                                        ->color('primary'),
                                    TextEntry::make('event.title')
                                        ->label(__('order.event'))
                                        ->columnSpanFull(),
                                ]),
                            ]),
                    ])->columnSpan(['lg' => 2]),

                    Group::make([
                        Section::make(__('STATUS UPDATE'))
                            ->compact()
                            ->schema([
                                Grid::make(1)->schema([
                                    Select::make('status')
                                        ->label(__('order.status_label'))
                                        ->options([
                                            'pending_payment' => __('order.status.pending_payment'),
                                            'pending_verification' => __('order.status.pending_verification'),
                                            'completed' => __('order.status.completed'),
                                            'cancelled' => __('order.status.cancelled'),
                                            'expired' => __('order.status.expired'),
                                        ])
                                        ->required()
                                        ->live()
                                        ->afterStateUpdated(fn ($state) => $this->status = $state)
                                        ->rules([
                                            fn (Get $get) => function (string $attribute, $value, \Closure $fail) use ($get) {
                                                if ($value === 'completed' && $this->record->files->isEmpty()) {
                                                    $fail(__('order.validation.payment_proof_required'));
                                                }

                                                if ($value === 'cancelled' && blank($get('cancellation_reason'))) {
                                                    $fail(__('order.validation.cancellation_note_required'));
                                                }
                                            },
                                        ]),
                                    
                                    Textarea::make('cancellation_reason')
                                        ->label(__('order.cancellation_notes'))
                                        ->placeholder(__('order.cancellation_notes_placeholder'))
                                        ->visible(fn (Get $get) => $get('status') === 'cancelled' || $this->status === 'cancelled')
                                        ->required(fn (Get $get) => $get('status') === 'cancelled' || $this->status === 'cancelled')
                                        ->live()
                                        ->validationAttribute(__('order.cancellation_notes'))
                                        ->rows(3),

                                    DateTimePicker::make('expires_at')
                                        ->label(__('order.expires_at'))
                                        ->default(now()->addHours(24)),

                                    DateTimePicker::make('completed_at')
                                        ->label(__('order.completed_at')),

                                    SchemaActions::make([
                                        Action::make('saveChanges')
                                            ->label(__('Save Changes'))
                                            ->icon('heroicon-m-check-circle')
                                            ->color('danger')
                                            ->requiresConfirmation()
                                            ->action(function () {
                                                $data = $this->getSchema('schema')->getState();
                                                $newStatus = $data['status'];
                                                $reason = $data['cancellation_reason'] ?? null;
                                                
                                                $this->status = $newStatus;

                                                $this->record->update([
                                                    'expires_at' => $data['expires_at'] ?? $this->record->expires_at,
                                                    'completed_at' => $data['completed_at'] ?? $this->record->completed_at,
                                                    'cancellation_reason' => $reason,
                                                ]);

                                                if ($newStatus === 'completed') {
                                                    if ($this->record->paymentProof) {
                                                        app(OrderService::class)->approvePayment($this->record->paymentProof, auth()->user());
                                                    } else {
                                                        app(OrderService::class)->approveManualOrder($this->record);
                                                    }

                                                    Notification::make()
                                                        ->title(__('payment_proof.approved_successfully'))
                                                        ->success()
                                                        ->send();
                                                } elseif ($newStatus === 'cancelled') {
                                                    if ($this->record->paymentProof) {
                                                        app(OrderService::class)->rejectPayment(
                                                            $this->record->paymentProof,
                                                            auth()->user(),
                                                            $reason
                                                        );
                                                    } else {
                                                        app(OrderService::class)->cancelOrder($this->record, $reason);
                                                    }

                                                    Notification::make()
                                                        ->title(__('payment_proof.rejected_successfully'))
                                                        ->warning()
                                                        ->send();
                                                } else {
                                                    $this->record->update(['status' => $newStatus]);
                                                    Notification::make()
                                                        ->title(__('Status updated to') . ' ' . $newStatus)
                                                        ->success()
                                                        ->send();
                                                }
                                                    
                                                return redirect()->to(OrderResource::getUrl('index'));
                                            }),
                                    ])->fullWidth(),
                                ]),
                            ]),

                        Section::make(__('order.payment_proofs'))
                            ->icon('heroicon-m-clipboard-document-list')
                            ->headerActions([
                                Action::make('downloadAll')
                                    ->label(__('DOWNLOAD ALL'))
                                    ->icon('heroicon-m-arrow-down-tray')
                                    ->color('danger')
                                    ->size('xs')
                                    ->url(fn () => route('admin.orders.download_files', $this->record->id))
                                    ->openUrlInNewTab(),
                            ])
                            ->schema([
                                RepeatableEntry::make('files')
                                    ->label(false)
                                    ->schema([
                                        ViewEntry::make('url')
                                            ->label(false)
                                            ->view('filament.resources.orders.components.payment-proof-item'),
                                    ])
                                    ->grid(2)
                                    ->placeholder(__('payment_proof.no_file_uploaded_manual_check'))
                                    ->columnSpanFull(),

                                Grid::make(3)
                                    ->schema([
                                        TextEntry::make('files_count')
                                            ->label(__('Files Uploaded'))
                                            ->state(fn () => $this->record->files()->count() . ' Images')
                                            ->weight(FontWeight::Bold)
                                            ->color('gray'),
                                        TextEntry::make('paymentProof.created_at')
                                            ->label(__('Last Uploaded At'))
                                            ->dateTime('M d, Y H:i:s')
                                            ->weight(FontWeight::Bold)
                                            ->color('gray'),
                                        TextEntry::make('paymentProof.status')
                                            ->label(__('Status'))
                                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                                'pending' => 'Waiting Review',
                                                'approved' => 'Approved',
                                                'rejected' => 'Rejected',
                                                default => $state,
                                            })
                                            ->color(fn (string $state): string => match ($state) {
                                                'pending' => 'warning',
                                                'approved' => 'success',
                                                'rejected' => 'danger',
                                                default => 'gray',
                                            })
                                            ->weight(FontWeight::ExtraBold),
                                    ])
                                    ->extraAttributes(['class' => 'mt-6 pt-6 border-t border-gray-100 gap-y-4']),
                            ]),
                    ])->columnSpan(['lg' => 1]),
                ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
