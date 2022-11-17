<?php
declare(strict_types=1);

namespace Chanathale\ChanathaleBase\Service;

use TYPO3\CMS\Core\Page\PageRenderer;

/**
 * JsonLdMarkupService
 */
class JsonLdMarkupService
{
    /**
     * @var PageRenderer
     */
    protected PageRenderer $pageRenderer;

    /**
     * __construct
     *
     * @param PageRenderer $pageRenderer
     */
    public function __construct(PageRenderer $pageRenderer)
    {
        $this->pageRenderer = $pageRenderer;
    }

    /**
     * addFaq
     *
     * @param array $items
     * @return void
     */
    public function addFaq(array $items): void
    {
        if (count($items) > 0) {
            $markup = [
                "@context" => "https://schema.org",
                "@type" => "FAQPage",
                "mainEntity" => []
            ];

            foreach ($items as $item) {
                $markup['mainEntity'][] = [
                    "@type" => "Question",
                    "name" => $item['question'],
                    "acceptedAnswer" => [
                        "@type" => "Answer",
                        "text" => strip_tags($item['answer'], '<h1>,<h2>,<h3>,<h4>,<h5>,<h6>,<a>,<br>,<ol>,<ul>,<li>,<p>,<b>,<strong>,<i>,<em>')
                    ]
                ];
            }

            $this->addJavaScript($markup);
        }
    }

    /**
     * addJavaScript
     *
     * @param array $markup
     * @return void
     */
    protected function addJavaScript(array $markup): void
    {
        $snippet = '<script type="application/ld+json" data-ignore="1">' . json_encode($markup) . '</script>';

        $this->pageRenderer->addHeaderData($snippet);
    }
}