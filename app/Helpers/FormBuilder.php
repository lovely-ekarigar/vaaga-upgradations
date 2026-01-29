<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ViewErrorBag;

/**
 * Drop-in replacement for Laravel Collective Form facade (laravelcollective/html).
 * Use Form::open(), Form::label(), etc. in Blade views when the package is not installed.
 */
class FormBuilder
{
    protected $model;
    protected $csrf = true;

    public function open(array $options = [])
    {
        $method = strtoupper($options['method'] ?? 'POST');
        $action = $options['route'] ?? $options['action'] ?? url()->current();
        if (isset($options['route'])) {
            $action = is_array($options['route']) ? route($options['route'][0], array_slice($options['route'], 1)) : route($options['route']);
        }
        $attributes = $this->attributes($options);
        $attributes['method'] = $method === 'GET' ? 'GET' : 'POST';
        $attributes['action'] = $action;
        if (isset($options['files']) && $options['files']) {
            $attributes['enctype'] = 'multipart/form-data';
        }
        $html = '<form' . $this->htmlAttributes($attributes) . '>';
        if ($method !== 'GET' && $method !== 'POST') {
            $html .= '<input type="hidden" name="_method" value="' . e($method) . '">';
        }
        if (($options['csrf'] ?? true) && $this->csrf) {
            $html .= '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
        }
        return $html;
    }

    public function model($model, array $options = [])
    {
        $this->model = $model;
        return $this->open($options);
    }

    public function close()
    {
        $this->model = null;
        return '</form>';
    }

    public function label($name, $value = null, array $options = [])
    {
        $value = $value ?: ucfirst(str_replace('_', ' ', $name));
        $options['for'] = $options['for'] ?? $name;
        return '<label' . $this->htmlAttributes($options) . '>' . e($value) . '</label>';
    }

    public function text($name, $value = null, array $options = [])
    {
        $value = $value ?? $this->old($name) ?? ($this->model ? ($this->model->{$name} ?? null) : null);
        $options['name'] = $name;
        $options['type'] = 'text';
        $options['value'] = old($name, $value);
        return '<input' . $this->htmlAttributes($options) . '>';
    }

    public function textarea($name, $value = null, array $options = [])
    {
        $value = $value ?? $this->old($name) ?? ($this->model ? ($this->model->{$name} ?? null) : null);
        $options['name'] = $name;
        $value = e(old($name, $value));
        unset($options['value']);
        return '<textarea' . $this->htmlAttributes($options) . '>' . $value . '</textarea>';
    }

    public function hidden($name, $value = null, array $options = [])
    {
        $value = $value ?? $this->old($name) ?? ($this->model ? ($this->model->{$name} ?? null) : null);
        $options['name'] = $name;
        $options['type'] = 'hidden';
        $options['value'] = old($name, $value);
        return '<input' . $this->htmlAttributes($options) . '>';
    }

    public function checkbox($name, $value = 1, $checked = null, array $options = [])
    {
        $checked = $checked ?? (bool) $this->old($name) ?? ($this->model ? (bool) ($this->model->{$name} ?? false) : false);
        $options['name'] = $name;
        $options['type'] = 'checkbox';
        $options['value'] = $value;
        if ($checked) {
            $options['checked'] = 'checked';
        }
        if (!empty($options['disabled'])) {
            $options['disabled'] = 'disabled';
        }
        return '<input' . $this->htmlAttributes($options) . '>';
    }

    public function submit($value = null, array $options = [])
    {
        $options['type'] = 'submit';
        $options['value'] = $value;
        return '<input' . $this->htmlAttributes($options) . '>';
    }

    public function select($name, $list = [], $selected = null, array $options = [])
    {
        $selected = $selected ?? $this->old($name) ?? ($this->model ? ($this->model->{$name} ?? null) : null);
        $options['name'] = $name;
        $html = '<select' . $this->htmlAttributes($options) . '>';
        foreach ($list as $key => $label) {
            $sel = ($key == $selected || (string) $key === (string) $selected) ? ' selected' : '';
            $html .= '<option value="' . e($key) . '"' . $sel . '>' . e($label) . '</option>';
        }
        $html .= '</select>';
        return $html;
    }

    public function file($name, array $options = [])
    {
        $options['name'] = $name;
        $options['type'] = 'file';
        return '<input' . $this->htmlAttributes($options) . '>';
    }

    protected function old($name)
    {
        return request()->old($name);
    }

    protected function attributes(array $options)
    {
        $attrs = [];
        foreach (['method', 'action', 'enctype', 'class', 'id', 'name', 'type', 'value', 'placeholder', 'for', 'checked', 'disabled', 'accept'] as $key) {
            if (array_key_exists($key, $options)) {
                $attrs[$key] = $options[$key];
            }
        }
        return $attrs;
    }

    protected function htmlAttributes(array $attributes)
    {
        $html = [];
        foreach ($attributes as $key => $value) {
            if ($key === 'value' && $value === null) {
                continue;
            }
            if ($value === true) {
                $html[] = e($key);
            } elseif ($value !== false && $value !== null) {
                $html[] = e($key) . '="' . e($value) . '"';
            }
        }
        return $html ? ' ' . implode(' ', $html) : '';
    }
}
