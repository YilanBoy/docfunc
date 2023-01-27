<?php

namespace App\Http\Traits\Livewire;

use Illuminate\Support\Str;

trait MarkdownConverter
{
    public function convertToHtml(string $body): string
    {
        $html = Str::of($body)->markdown([
            'html_input' => 'strip',
        ]);

        $search = [
            '<h1>', '</h1>',
            '<h2>', '</h2>',
            '<h3>', '</h3>',
            '<h4>', '</h4>',
            '<h5>', '</h5>',
            '<h6>', '</h6>',
        ];

        $replace = [
            '<p>', '</p>',
            '<p>', '</p>',
            '<p>', '</p>',
            '<p>', '</p>',
            '<p>', '</p>',
            '<p>', '</p>',
        ];

        return str_replace($search, $replace, $html);
    }
}
