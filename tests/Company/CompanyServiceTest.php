<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */
declare(strict_types = 1);

namespace Wizaplace\Tests\Company;

use GuzzleHttp\Exception\ClientException;
use PHPUnit_Framework_MockObject_MockObject;
use Psr\Http\Message\UploadedFileInterface;
use Wizaplace\Authentication\AuthenticationRequired;
use Wizaplace\Company\CompanyRegistration;
use Wizaplace\Company\CompanyService;
use Wizaplace\Tests\ApiTestCase;
use function GuzzleHttp\Psr7\stream_for;

/**
 * @see CompanyService
 */
class CompanyServiceTest extends ApiTestCase
{
    public function testRegisteringACompanyWithAllInformation()
    {
        $companyRegistration = new CompanyRegistration('ACME2 Test Inc', 'acme2@example.com');
        $companyRegistration->setAddress('24 rue de la gare');
        $companyRegistration->setCapital('1 000 000 000 $');
        $companyRegistration->setCity('Lyon');
        $companyRegistration->setCountry('France');
        $companyRegistration->setDescription('Super ACME company');
        $companyRegistration->setFax('01 02 03 04 05');
        $companyRegistration->setLegalStatus('SARL');
        $companyRegistration->setPhoneNumber('01 02 03 04 05 06');
        $companyRegistration->setRcs('RCS VANNES B 514 919 844');
        $companyRegistration->setVatNumber('12345678901');
        $companyRegistration->setZipcode('69009');
        $companyRegistration->setSiretNumber('732 829 320 00074');
        $companyRegistration->setSlug('acme-inc');
        $companyRegistration->setUrl('https://acme.example.com/');

        $companyRegistration->addUploadedFile('rib', $this->mockUploadedFile('minimal.pdf'));
        $companyRegistration->addUploadedFile('idCard', $this->mockUploadedFile('minimal.pdf'));

        $result = $this->buildUserCompanyService()->register($companyRegistration);

        $company = $result->getCompany();
        $this->assertGreaterThan(0, $company->getId());
        $this->assertEquals('acme-inc', $company->getSlug());
        $this->assertEquals('acme2@example.com', $company->getEmail());
        $this->assertEquals('24 rue de la gare', $companyRegistration->getAddress());
        $this->assertEquals('1 000 000 000 $', $companyRegistration->getCapital());
        $this->assertEquals('Lyon', $companyRegistration->getCity());
        $this->assertEquals('France', $companyRegistration->getCountry());
        $this->assertEquals('Super ACME company', $companyRegistration->getDescription());
        $this->assertEquals('01 02 03 04 05', $companyRegistration->getFax());
        $this->assertEquals('SARL', $companyRegistration->getLegalStatus());
        $this->assertEquals('01 02 03 04 05 06', $companyRegistration->getPhoneNumber());
        $this->assertEquals('RCS VANNES B 514 919 844', $companyRegistration->getRcs());
        $this->assertEquals('12345678901', $companyRegistration->getVatNumber());
        $this->assertEquals('69009', $companyRegistration->getZipcode());
        $this->assertEquals('732 829 320 00074', $companyRegistration->getSiretNumber());
        $this->assertEquals('https://acme.example.com/', $companyRegistration->getUrl());

        $this->assertTrue($result->getFileUploadResult('rib')->isSuccess());
        $this->assertNull($result->getFileUploadResult('rib')->getErrorMessage());
        $this->assertTrue($result->getFileUploadResult('idCard')->isSuccess());
        $this->assertNull($result->getFileUploadResult('idCard')->getErrorMessage());
    }

    public function testRegisteringACompanyWithMinimalInformation()
    {
        $companyRegistration = new CompanyRegistration('ACME Test Inc', 'acme@example.com');

        $result = $this->buildUserCompanyService()->register($companyRegistration);

        $company = $result->getCompany();
        $this->assertGreaterThan(0, $company->getId());
        $this->assertStringStartsWith('acme-test-inc', $company->getSlug());
        $this->assertEquals('acme@example.com', $company->getEmail());
        $this->assertEmpty($company->getFax());
    }

    public function testRegisteringACompanyWithInvalidEmail()
    {
        $companyRegistration = new CompanyRegistration('ACME Test Inc', 'acme@@example.com');

        $this->expectException(ClientException::class); // @TODO: decorate?
        $this->buildUserCompanyService()->register($companyRegistration);
    }

    public function testRegisteringACompanyWithEmptyRequiredFields()
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(400);
        $this->buildUserCompanyService()->register(new CompanyRegistration('', ''));
    }

    public function testRegisteringACompanyAnonymously()
    {
        $this->expectException(AuthenticationRequired::class);
        (new CompanyService($this->buildApiClient()))->register(new CompanyRegistration('doesntmatter', 'really'));
    }

    public function testUploadingBadExtensionRegistrationFiles()
    {
        $companyRegistration = new CompanyRegistration('4CME Test Inc', 'acme4@example.com');
        $companyRegistration->addUploadedFile('rib', $this->mockUploadedFile('dummy.txt'));
        $companyService = $this->buildUserCompanyService();

        $result = $companyService->register($companyRegistration);
        $this->assertGreaterThan(0, $result->getCompany()->getId());

        $this->assertFalse($result->getFileUploadResult('rib')->isSuccess());
        $this->assertEquals('Invalid file', $result->getFileUploadResult('rib')->getErrorMessage());
    }

    private function buildUserCompanyService(): CompanyService
    {
        $apiClient = $this->buildApiClient();
        $apiClient->authenticate('user@wizaplace.com', 'password');

        return new CompanyService($apiClient);
    }

    private function mockUploadedFile(string $filename): UploadedFileInterface
    {
        $path = __DIR__.'/../fixtures/files/'.$filename;

        /** @var UploadedFileInterface|PHPUnit_Framework_MockObject_MockObject $file */
        $file = $this->createMock(UploadedFileInterface::class);
        $file->expects($this->once())->method('getStream')->willReturn(stream_for(fopen($path, 'r')));
        $file->expects($this->once())->method('getClientFilename')->willReturn($filename);

        return $file;
    }
}