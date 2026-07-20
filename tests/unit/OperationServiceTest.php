<?php

namespace Tests\Unit;

use App\Models\BaremeFraisModel;
use App\Models\ClientModel;
use App\Models\CompteModel;
use App\Models\MouvementCompteModel;
use App\Models\OperationModel;
use App\Models\TypeOperationModel;
use App\Services\DetectionOperateurService;
use App\Services\FraisService;
use App\Services\OperationService;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Test\CIUnitTestCase;

final class OperationServiceTest extends CIUnitTestCase
{
    public function testPrepareUnTransfertExterne(): void
    {
        $detection = $this->createMock(DetectionOperateurService::class);
        $detection->method('detecter')->willReturn([
            'telephone' => '0371234567', 'prefixe' => '037', 'nom' => 'Partenaire',
            'est_interne' => false, 'commission_externe' => 100,
        ]);
        $frais = $this->createMock(FraisService::class);
        $frais->method('calculerTransfert')->willReturn([
            'montant' => 10_000, 'frais_transfert' => 100, 'commission_externe' => 100,
            'frais_retrait' => 0, 'frais_total' => 200, 'total_debite' => 10_200,
        ]);

        $service = new OperationService(
            $detection, $frais,
            $this->createMock(ClientModel::class), $this->createMock(CompteModel::class),
            $this->createMock(OperationModel::class), $this->createMock(MouvementCompteModel::class),
            $this->createMock(TypeOperationModel::class), $this->createMock(BaseConnection::class),
        );
        $resultat = $service->preparerTransfert('0371234567', 10_000);

        $this->assertFalse($resultat['operateur']['est_interne']);
        $this->assertSame(10_200, $resultat['calcul']['total_debite']);
    }
}
