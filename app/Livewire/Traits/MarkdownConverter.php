<?php

namespace App\Livewire\Traits;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Exception\CommonMarkException;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;
use League\CommonMark\MarkdownConverter as CommonMarkdownConverter;

trait MarkdownConverter
{
    /**
     * @throws CommonMarkException
     */
    public function convertToHtml(string $body): string
    {
        $config = [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
            'max_nesting_level' => 10,
            'external_link' => [
                'internal_hosts' => parse_url(config('app.url'), PHP_URL_HOST),
                'open_in_new_window' => true,
                'nofollow' => 'external',
                'noopener' => 'external',
                'noreferrer' => 'external',
            ],
        ];

        $environment = new Environment($config);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new ExternalLinkExtension());

        $converter = new CommonMarkdownConverter($environment);

        $html = $converter->convert($body);

        return $this->removeHeadingInHtml($html);
    }

    protected function removeHeadingInHtml(string $html): string
    {
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
