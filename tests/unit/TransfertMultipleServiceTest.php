<?php
namespace Tests\Unit;

use App\Services\DetectionOperateurService;
use App\Services\FraisService;
use App\Services\OperationService;
use CodeIgniter\Test\CIUnitTestCase;
use InvalidArgumentException;

final class TransfertMultipleServiceTest extends CIUnitTestCase
{
    public function testRefuseLesDoublonsApresNormalisation(): void
    {
        $detection = $this->createMock(DetectionOperateurService::class);
        $detection->method('detecter')->willReturn(['telephone' => '0331234567', 'prefixe' => '033', 'nom' => 'MobiCash', 'est_interne' => true, 'commission_externe' => 0]);
        $service = new OperationService($detection, $this->createMock(FraisService::class));
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('double');
        $service->preparerTransfertsMultiples(['0331234567', '033 12 345 67'], 2000);
    }
}
