<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Faker\Factory;
use App\Entity\Quote;
use App\Entity\Template;
use App\TemplateManager;
use App\Repository\SiteRepository;
use App\Context\ApplicationContext;
use App\Repository\QuoteRepository;
use App\Repository\DestinationRepository;

class TemplateManagerTest extends TestCase
{
    private $faker;
    private $destinationRepository;
    private $applicationContext;

    protected function setUp(): void
    {
        $this->faker = Factory::create();
        $this->destinationRepository = new DestinationRepository();
        $this->applicationContext = new ApplicationContext();
    }

    public function testTemplateComputation(): void
    {
        $destinationId = $this->faker->randomNumber();
        $expectedDestination = $this->destinationRepository->getById($destinationId);
        $expectedUser = $this->applicationContext->getCurrentUser();

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

        $templateManager = new TemplateManager(
            new QuoteRepository(),
            new SiteRepository(),
            $this->destinationRepository,
            $this->applicationContext
        );

        $message = $templateManager->getTemplateComputed(
            $template,
            [
                'quote' => $quote
            ]
        );

        $this->assertEquals(
            'Votre livraison à ' . $expectedDestination->getCountryName(),
            $message->getSubject()
        );
        $this->assertEquals(
            "
Bonjour " . $expectedUser->getFirstname() . ",

Merci de nous avoir contacté pour votre livraison à " . $expectedDestination->getCountryName() . ".

Bien cordialement,

L'équipe de Shipper
",
            $message->getContent()
        );
    }
}