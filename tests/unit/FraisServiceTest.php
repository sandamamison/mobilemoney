<?php

namespace Tests\Unit;

use App\Models\BaremeFraisModel;
use App\Models\TypeOperationModel;
use App\Services\FraisService;
use CodeIgniter\Test\CIUnitTestCase;
use InvalidArgumentException;
use RuntimeException;

final class FraisServiceTest extends CIUnitTestCase
{
    private function service(?int $fraisTransfert = 100, ?int $fraisRetrait = 200): FraisService
    {
        $types = $this->createMock(TypeOperationModel::class);
        $types->method('getByCode')->willReturnCallback(fn (string $code) => [
            'id' => $code === 'TRANSFERT' ? 3 : 2,
            'code' => $code,
        ]);

        $baremes = $this->createMock(BaremeFraisModel::class);
        $baremes->method('findFraisForAmount')->willReturnCallback(
            fn (int $typeId) => $typeId === 3 ? $fraisTransfert : $fraisRetrait,
        );

        return new FraisService($baremes, $types);
    }

    public function testCalculeTransfertInterne(): void
    {
        $resultat = $this->service()->calculerTransfert(10_000, [
            'est_interne' => true,
            'commission_externe' => 0,
        ]);

        $this->assertSame(100, $resultat['frais_total']);
        $this->assertSame(10_100, $resultat['total_debite']);
        $this->assertSame(0, $resultat['commission_externe']);
    }

    public function testAjouteCommissionExterne(): void
    {
        $resultat = $this->service()->calculerTransfert(10_000, [
            'est_interne' => false,
            'commission_externe' => 150,
        ]);

        $this->assertSame(250, $resultat['frais_total']);
        $this->assertSame(10_250, $resultat['total_debite']);
    }

    public function testAjouteFraisRetraitLorsqueOptionCochee(): void
    {
        $resultat = $this->service()->calculerTransfert(10_000, [
            'est_interne' => false,
            'commission_externe' => 150,
        ], true);

        $this->assertSame(450, $resultat['frais_total']);
        $this->assertSame(10_450, $resultat['total_debite']);
    }

    public function testRefuseMontantNonPositif(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->service()->calculerTransfert(0, ['est_interne' => true, 'commission_externe' => 0]);
    }

    public function testRefuseMontantSansTranche(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Aucune tranche active TRANSFERT');
        $this->service(null)->calculerTransfert(3_000_000, ['est_interne' => true, 'commission_externe' => 0]);
    }
}
