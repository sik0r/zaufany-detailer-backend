<?php

declare(strict_types=1);

namespace App\Tests\E2E;

use App\Entity\CompanyRegisterLead;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversNothing;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 */
#[CoversNothing]
class CompanyRegisterLeadTest extends WebTestCase
{
    private EntityManagerInterface $em;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->em = self::getContainer()->get(EntityManagerInterface::class);
    }

    public function testSuccessfulSubmissionCreatesLeadAndSendsEmail(): void
    {
        $crawler = $this->client->request('GET', '/dla-warsztatow/zaloz-konto');
        $form = $crawler->selectButton('Wyślij zgłoszenie')->form([
            'company_register_lead[firstName]' => 'Anna',
            'company_register_lead[lastName]' => 'Nowak',
            'company_register_lead[nip]' => '5270103391',
            'company_register_lead[phoneNumber]' => '600123456',
            'company_register_lead[email]' => 'anna.nowak@example.com',
        ]);
        $this->client->submit($form);

        // we need to check email before redirect
        $this->assertEmailCount(1);
        $email = $this->getMailerMessage();
        $this->assertEmailHeaderSame($email, 'To', 'anna.nowak@example.com');

        $this->assertResponseRedirects();
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.bg-green-100.text-green-700', 'Dziękujemy');

        $lead = $this->em->getRepository(CompanyRegisterLead::class)
            ->findOneBy(['email' => 'anna.nowak@example.com'])
        ;

        $this->assertNotNull($lead);
        $this->assertSame('new', $lead->getStatus());
        $this->assertSame('Anna', $lead->getFirstName());
        $this->assertSame('Nowak', $lead->getLastName());
        $this->assertSame('5270103391', $lead->getNip());
        $this->assertSame('600123456', $lead->getPhoneNumber());
        $this->assertSame('anna.nowak@example.com', $lead->getEmail());
    }

    public function testDuplicateEmailShowsError(): void
    {
        // Przygotuj lead z tym samym emailem
        $lead = new CompanyRegisterLead();
        $lead->setFirstName('Jan');
        $lead->setLastName('Kowalski');
        $lead->setNip('1111111111');
        $lead->setPhoneNumber('600111222');
        $lead->setEmail('duplicate@example.com');
        $this->em->persist($lead);
        $this->em->flush();

        $crawler = $this->client->request('GET', '/dla-warsztatow/zaloz-konto');
        $form = $crawler->selectButton('Wyślij zgłoszenie')->form([
            'company_register_lead[firstName]' => 'Adam',
            'company_register_lead[lastName]' => 'Nowak',
            'company_register_lead[nip]' => '5270103391',
            'company_register_lead[phoneNumber]' => '600222333',
            'company_register_lead[email]' => 'duplicate@example.com',
        ]);
        $this->client->submit($form);

        $this->assertSelectorTextContains('.text-red-500', 'Podany adres e-mail jest już zarejestrowany');
    }

    public function testDuplicateNipShowsError(): void
    {
        $lead = new CompanyRegisterLead();
        $lead->setFirstName('Jan');
        $lead->setLastName('Kowalski');
        $lead->setNip('5270103391');
        $lead->setPhoneNumber('600111222');
        $lead->setEmail('unique@example.com');
        $this->em->persist($lead);
        $this->em->flush();

        $crawler = $this->client->request('GET', '/dla-warsztatow/zaloz-konto');
        $form = $crawler->selectButton('Wyślij zgłoszenie')->form([
            'company_register_lead[firstName]' => 'Adam',
            'company_register_lead[lastName]' => 'Nowak',
            'company_register_lead[nip]' => '5270103391',
            'company_register_lead[phoneNumber]' => '600222333',
            'company_register_lead[email]' => 'adam.nowak@example.com',
        ]);
        $this->client->submit($form);

        $this->assertSelectorTextContains('.text-red-500', 'Podany NIP jest już zarejestrowany');
    }

    public function testInvalidEmailShowsError(): void
    {
        $crawler = $this->client->request('GET', '/dla-warsztatow/zaloz-konto');
        $form = $crawler->selectButton('Wyślij zgłoszenie')->form([
            'company_register_lead[firstName]' => 'Adam',
            'company_register_lead[lastName]' => 'Nowak',
            'company_register_lead[nip]' => '5270103391',
            'company_register_lead[phoneNumber]' => '600222333',
            'company_register_lead[email]' => 'not-an-email',
        ]);
        $this->client->submit($form);
        $this->assertSelectorTextContains('.text-red-500', 'Podany adres e-mail jest nieprawidłowy');
    }

    public function testEmptyFieldsShowErrors(): void
    {
        $crawler = $this->client->request('GET', '/dla-warsztatow/zaloz-konto');
        $form = $crawler->selectButton('Wyślij zgłoszenie')->form([
            'company_register_lead[firstName]' => '',
            'company_register_lead[lastName]' => '',
            'company_register_lead[nip]' => '',
            'company_register_lead[phoneNumber]' => '',
            'company_register_lead[email]' => '',
        ]);
        $this->client->submit($form);

        $this->assertSelectorTextContains('.text-red-500', 'Imię jest wymagane');
    }
}
