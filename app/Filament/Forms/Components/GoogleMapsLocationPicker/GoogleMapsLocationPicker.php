<?php

namespace App\Filament\Forms\Components\GoogleMapsLocationPicker;

use Filament\Forms\Components\TextInput;

class GoogleMapsLocationPicker extends TextInput
{
    protected string $view = 'filament.forms.components.google-maps-location-picker';

    protected bool $showMap = true;

    protected ?string $mapHeight = '300px';

    protected ?string $mapType = 'roadmap';

    protected int $zoom = 15;

    public function showMap(bool $show = true): static
    {
        $this->showMap = $show;

        return $this;
    }

    public function hideMap(): static
    {
        return $this->showMap(false);
    }

    public function mapHeight(string $height): static
    {
        $this->mapHeight = $height;

        return $this;
    }

    public function mapType(string $type): static
    {
        $this->mapType = $type;

        return $this;
    }

    public function zoom(int $zoom): static
    {
        $this->zoom = $zoom;

        return $this;
    }

    public function getMapHeight(): string
    {
        return $this->mapHeight;
    }

    public function getMapType(): string
    {
        return $this->mapType;
    }

    public function getZoom(): int
    {
        return $this->zoom;
    }

    public function getShowMap(): bool
    {
        return $this->showMap;
    }
}
