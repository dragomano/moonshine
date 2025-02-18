<?php

declare(strict_types=1);

namespace MoonShine\Components;

use Closure;
use MoonShine\Components\Layout\WithComponents;
use MoonShine\Fields\Field;
use MoonShine\Fields\Fields;
use Throwable;

final class FieldsGroup extends WithComponents
{
    protected string $view = 'moonshine::components.fields-group';

    /**
     * @throws Throwable
     */
    public function previewMode(): self
    {
        return $this->mapFields(
            fn (Field $field): Field => $field->forcePreview()
        );
    }

    public function fill(array $raw = [], mixed $casted = null, int $index = 0): self
    {
        return $this->mapFields(
            fn (Field $field): Field => $field->resolveFill($raw, $casted ?? $raw, $index)
        );
    }

    public function withoutWrappers(): self
    {
        return $this->mapFields(
            fn (Field $field): Field => $field->withoutWrapper()
        );
    }

    public function mapFields(Closure $callback): self
    {
        if(! $this->components instanceof Fields) {
            $this->components = Fields::make($this->components);
        }

        $this->components
            ->onlyFields()
            ->map(fn (Field $field): Field => $callback($field));

        return $this;
    }
}
