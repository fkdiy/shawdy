<?php

namespace App\Tests\Integration;

use App\Entity\AbuseReport;
use Doctrine\ORM\EntityManagerInterface;

class AbuseReportTest extends IntegrationTestCase
{
    public function testAbuseReportCanBePersisted(): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $shortCode = 'abc123';

        $abuseReport = new AbuseReport();
        $abuseReport
            ->setShortCode($shortCode)
            ->setEmail('test@example.com')
            ->setMessage('Test report')
            ->setLocale('de');

        $entityManager->persist($abuseReport);
        $entityManager->flush();

        $entityManager->clear();

        $stored = $entityManager
            ->getRepository(AbuseReport::class)
            ->findOneBy(['shortCode' => $shortCode]);

        self::assertNotNull($stored);
        self::assertNotNull($stored->getId());
        self::assertSame($shortCode, $stored->getShortCode());
        self::assertSame('test@example.com', $stored->getEmail());
        self::assertSame('Test report', $stored->getMessage());
        self::assertSame('de', $stored->getLocale());
    }
}
