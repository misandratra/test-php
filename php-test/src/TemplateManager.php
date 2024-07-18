<?php

declare(strict_types=1);

class TemplateManager
{
    private $quoteRepository;
    private $siteRepository;
    private $destinationRepository;
    private $applicationContext;

    public function __construct(
        QuoteRepository $quoteRepository,
        SiteRepository $siteRepository,
        DestinationRepository $destinationRepository,
        ApplicationContext $applicationContext
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->siteRepository = $siteRepository;
        $this->destinationRepository = $destinationRepository;
        $this->applicationContext = $applicationContext;
    }

    public function getTemplateComputed(Template $tpl, array $data): Template
    {
        $replaced = clone $tpl;
        $replaced->subject = $this->computeText($replaced->subject, $data);
        $replaced->content = $this->computeText($replaced->content, $data);

        return $replaced;
    }

    private function computeText(string $text, array $data): string
    {
        $quote = $data['quote'] ?? null;
        if ($quote instanceof Quote) {
            $text = $this->replaceQuoteVariables($text, $quote);
        }

        $user = $data['user'] ?? $this->applicationContext->getCurrentUser();
        if ($user instanceof User) {
            $text = $this->replaceUserVariables($text, $user);
        }

        return $text;
    }

    private function replaceQuoteVariables(string $text, Quote $quote): string
    {
        $quoteFromRepository = $this->quoteRepository->getById($quote->id);
        $site = $this->siteRepository->getById($quote->siteId);
        $destination = $this->destinationRepository->getById($quote->destinationId);

        $replacements = [
            '[quote:destination_name]' => $destination->countryName,
            '[quote:destination_link]' => $this->getDestinationLink($site, $destination, $quoteFromRepository),
            '[quote:summary_html]' => Quote::renderHtml($quoteFromRepository),
            '[quote:summary]' => Quote::renderText($quoteFromRepository),
        ];

        return strtr($text, $replacements);
    }

    private function replaceUserVariables(string $text, User $user): string
    {
        $replacements = [
            '[user:first_name]' => ucfirst(mb_strtolower($user->firstname)),
        ];

        return strtr($text, $replacements);
    }

    private function getDestinationLink(?Site $site, ?Destination $destination, Quote $quote): string
    {
        if ($site && $destination) {
            return "{$site->url}/{$destination->countryName}/quote/{$quote->id}";
        }
        return '';
    }
}
