<?php

namespace Tests\Unit;

use App\Models\PrefixeModel;
use App\Services\DetectionOperateurService;
use CodeIgniter\Test\CIUnitTestCase;
use InvalidArgumentException;

final class DetectionOperateurServiceTest extends CIUnitTestCase
{
    public function testDetecteOperateurInterne(): void
    {
        $model = $this->createMock(PrefixeModel::class);
        $model->method('getByPrefixe')->with('033')->willReturn([
            'prefixe' => '033', 'actif' => 1, 'nom_operateur' => 'MobiCash',
            'est_interne' => 1, 'commission_externe' => 0,
        ]);

        $resultat = (new DetectionOperateurService($model))->detecter('033 12-345-67');

        $this->assertSame('0331234567', $resultat['telephone']);
        $this->assertTrue($resultat['est_interne']);
        $this->assertSame('MobiCash', $resultat['nom']);
    }

    public function testDetecteOperateurExterne(): void
    {
        $model = $this->createMock(PrefixeModel::class);
        $model->method('getByPrefixe')->with('037')->willReturn([
            'prefixe' => '037', 'actif' => 1, 'nom_operateur' => 'Opérateur partenaire',
            'est_interne' => 0, 'commission_externe' => 100,
        ]);

        $resultat = (new DetectionOperateurService($model))->detecter('0371234567');

        $this->assertFalse($resultat['est_interne']);
        $this->assertSame(100, $resultat['commission_externe']);
    }

    public function testRefuseFormatInvalide(): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new DetectionOperateurService($this->createMock(PrefixeModel::class)))->detecter('033123');
    }

    public function testRefusePrefixeInconnu(): void
    {
        $model = $this->createMock(PrefixeModel::class);
        $model->method('getByPrefixe')->willReturn(null);
        $this->expectExceptionMessage('Préfixe inconnu');
        (new DetectionOperateurService($model))->detecter('0321234567');
    }

    public function testRefuseOperateurDesactive(): void
    {
        $model = $this->createMock(PrefixeModel::class);
        $model->method('getByPrefixe')->willReturn([
            'prefixe' => '037', 'actif' => 0, 'nom_operateur' => 'Partenaire',
            'est_interne' => 0, 'commission_externe' => 100,
        ]);
        $this->expectExceptionMessage('désactivé');
        (new DetectionOperateurService($model))->detecter('0371234567');
    }
}
