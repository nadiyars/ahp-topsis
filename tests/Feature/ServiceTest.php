<?php

namespace Tests\Feature;

use App\Service\Service;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    protected $service;

    public function __construct()
    {
        parent::__construct();

        $this->service = new Service;
    }

    protected function pairWaise(): array
    {
        return [
            'Orientasi Pelayanan' =>
            [
                [1, null, null, null],
                [5, 1, 3, 6],
                [4, null, 1, 4],
                [2, null, null, 1],
            ],
            'Integritas'          =>
            [
                [1, null, null, 5],
                [5, 1, 2, 7],
                [2, null, 1, 5],
                [null, null, null, 1],
            ],
            'Tanggung Jawab'      =>
            [
                [1, null, null, null],
                [5, 1, 2, 3],
                [5, null, 1, 3],
                [2, null, null, 1],
            ],
            'Komitmen'            => [
                [1, null, null, null],
                [5, 1, 3, 6],
                [4, null, 1, 4],
                [2, null, null, 1],
            ],
            'Kepemimpinan'        => [
                [1, null, null, null],
                [5, 1, 2, 6],
                [4, null, 1, 5],
                [2, null, null, 1],
            ],
            'Kerjasama'           => [
                [1, 3, null, 2],
                [null, 1, null, null],
                [4, 5, 1, 3],
                [null, 3, null, 1],
            ],
            'Prestasi Kerja'      => [
                [1, null, null, 3],
                [5, 1, 2, 5],
                [3, null, 1, 3],
                [null, null, null, 1],
            ],
            'Wawasan'             => [
                [1, 2, 5, 5],
                [null, 1, 3, 3],
                [null, null, 1, 2],
                [null, null, null, 1],
            ],
            'Komunikatif'         => [
                [1, null, 2, 5],
                [3, 1, 3, 5],
                [null, null, 1, 4],
                [null, null, null, 1],
            ],
        ];
    }

    protected function criterias(): array
    {
        return [
            'Orientasi Pelayanan',
            'Integritas',
            'Tanggung Jawab',
            'Komitmen',
            'Kepemimpinan',
            'Kerjasama',
            'Prestasi Kerja',
            'Wawasan',
            'Komunikatif',
        ];
    }

    protected function matrix(): array
    {
        return [
            [1, null, null, null, null, null, null, null, null],
            [9, 1, 2, 2, 2, 3, 7, 7, 7],
            [7, null, 1, 2, 2, 2, 7, 7, 7],
            [9, null, null, 1, 2, 2, 7, 7, 5],
            [7, null, null, null, 1, 2, 5, 5, 5],
            [7, null, null, null, null, 1, 5, 5, 5],
            [4, null, null, null, null, null, 1, 3, 2],
            [3, null, null, null, null, null, null, 1, null],
            [3, null, null, null, null, null, null, 5, 1],
        ];
    }

    /**
     * @test
     * @group f-service
     */
    public function setRelativeInterestMatrixQualitativeCriteria()
    {
        foreach ($this->criterias() as $key => $value) {
            $this->service->addQualitativeCriteria($value);
        }

        $this->service->setRelativeInterestMatrix($this->matrix());
        $this->service->setCandidates(['TF', 'KS', 'NH', 'AT']);

        $this->service->setBatchCriteriaPairWise($this->pairWaise());
        $this->service->finalize();

        $result = $this->service->getResult();

        $this->assertEquals(0.08884097602454889, $result[0]["value"]);
    }
}
