<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Faker\Factory;
use App\Entity\Quote;
use App\Entity\Template;
use App\TemplateManager;
use App\Repository\SiteRepository;
use App\Context\ApplicationContext;
use App\Repository\QuoteRepository;
use App\Repository\DestinationRepository;

$faker = Factory::create();

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
    new DestinationRepository(),
    new ApplicationContext()
);

$quote = new Quote(
    $faker->randomNumber(),
    $faker->randomNumber(),
    $faker->randomNumber(),
    new DateTime($faker->date())
);

$message = $templateManager->getTemplateComputed($template, ['quote' => $quote]);

echo $message->getSubject() . "\n" . $message->getContent();
