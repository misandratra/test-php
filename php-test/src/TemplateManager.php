<?php

namespace App;

require_once __DIR__.'/../vendor/autoload.php';

use App\Entity\Site;
use App\Entity\User;
use App\Entity\Quote;
use App\Entity\Template;
use App\Entity\Destination;
use App\Repository\SiteRepository;
use App\Context\ApplicationContext;
use App\Repository\QuoteRepository;
use App\Repository\DestinationRepository;

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
        $replaced->setSubject($this->computeText($replaced->getSubject(), $data));
        $replaced->setContent($this->computeText($replaced->getContent(), $data));

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
        $quoteFromRepository = $this->quoteRepository->getById($quote->getId());
        $site = $this->siteRepository->getById($quote->getSiteId());
        $destination = $this->destinationRepository->getById($quote->getDestinationId());

        $replacements = [
            '[quote:destination_name]' => $destination ? $destination->getCountryName() : '',
            '[quote:destination_link]' => $quoteFromRepository ? $this->getDestinationLink($site, $destination, $quoteFromRepository) : '',
            '[quote:summary_html]' => $quoteFromRepository ? Quote::renderHtml($quoteFromRepository) : '',
            '[quote:summary]' => $quoteFromRepository ? Quote::renderText($quoteFromRepository) : '',
        ];

        return strtr($text, $replacements);
    }

    private function replaceUserVariables(string $text, User $user): string
    {
        $replacements = [
            '[user:first_name]' => ucfirst(mb_strtolower($user->getFirstname())),
        ];

        return strtr($text, $replacements);
    }

    private function getDestinationLink(?Site $site, ?Destination $destination, ?Quote $quote): string
    {
        if ($site && $destination) {
            return "{$site->getUrl()}/{$destination->getCountryName()}/quote/{$quote->getId()}";
        }
        return '';
    }
}
