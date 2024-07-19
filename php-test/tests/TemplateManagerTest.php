<?php

declare(strict_types=1);

use Faker\Factory;
use App\Entity\User;
use App\Entity\Quote;
use App\Entity\Template;
use App\TemplateManager;
use App\Entity\Destination;
use PHPUnit\Framework\TestCase;
use App\Repository\SiteRepository;
use App\Context\ApplicationContext;
use App\Repository\QuoteRepository;
use App\Repository\DestinationRepository;
use PHPUnit\Framework\MockObject\MockObject;

class TemplateManagerTest extends TestCase
{
    private $faker;
    private $destinationRepository;
    private $applicationContext;

    protected function setUp(): void
    {
        $this->faker = Factory::create();
        $this->destinationRepository = $this->createMock(DestinationRepository::class);
        $this->applicationContext = $this->createMock(ApplicationContext::class);
    }

    public function testTemplateComputation(): void
    {
        $destinationId = $this->faker->randomNumber();
        $expectedDestination = $this->createMock(Destination::class);
        $expectedDestination->method('getCountryName')->willReturn('France');

        $expectedUser = $this->createMock(User::class);
        $expectedUser->firstname = 'John';

        $this->destinationRepository->method('getById')->with($destinationId)->willReturn($expectedDestination);
        $this->applicationContext->method('getCurrentUser')->willReturn($expectedUser);

        $quote = new Quote(
            $this->faker->randomNumber(),
            $this->faker->randomNumber(),
            $destinationId,
            new DateTime($this->faker->date())
        );

        $template = new Template(
            1,
            'Votre livraison à [quote:destination_name]',
            "
Bonjour [user:first_name],

Merci de nous avoir contacté pour votre livraison à [quote:destination_name].

Bien cordialement,

L'équipe de Shipper
"
        );

        /** @var QuoteRepository&\PHPUnit\Framework\MockObject\MockObject */
        $quoteRepository = $this->createMock(QuoteRepository::class);

        /** @var SiteRepository&\PHPUnit\Framework\MockObject\MockObject */
        $siteRepository = $this->createMock(SiteRepository::class);

        $templateManager = new TemplateManager(
            $quoteRepository,
            $siteRepository,
            $this->destinationRepository,
            $this->applicationContext
        );

        $message = $templateManager->getTemplateComputed($template, ['quote' => $quote]);

        $this->assertEquals('Votre livraison à France', $message->getSubject());
        $this->assertEquals("
Bonjour John,

Merci de nous avoir contacté pour votre livraison à France.

Bien cordialement,

L'équipe de Shipper
", $message->getContent());
    }
}