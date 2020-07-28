<?php

declare (strict_types = 1);

namespace App\Service;

use App\Service\ServiceException;

class Service extends BaseService
{
    /**
     * Criteria
     *
     * @var array
     */
    protected $criterias = [];

    /**
     * Raw the criteria
     *
     * @var array
     */
    protected $rawCriteria = [];

    /**
     * Relative matrix
     *
     * @var array
     */
    protected $relativeMatrix = [];

    /**
     * Eigen vector
     *
     * @var array
     */
    protected $eigenVector = [];

    /**
     * Candidates
     *
     * @var array
     */
    protected $candidates = [];

    /**
     * Criteria Pair with matrix
     *
     * @var array
     */
    protected $criteriaPairWise = [];

    /**
     * Final matrix
     *
     * @var array
     */
    protected $finalMatrix = [];

    /**
     * Final ranks of the matrix
     *
     * @var array
     */
    protected $finalRanks = [];

    public function __construct()
    {
        //
    }

    /**
     * Final ranks
     *
     * @return array
     */
    public function getResult()
    {
        return $this->finalRanks;
    }

    /**
     * Final matrix
     * 
     * @return array
     */
    public function getMatrix()
    {
        return $this->finalMatrix;
    }

    /**
     * Add Quantitative
     *
     * @param  string
     * @return self
     */
    public function addQuantitativeCriteria(string $criteriaName): self
    {
        $this->criterias[] = [
            "name" => $criteriaName,
            "type" => self::QUANTITATIVE,
        ];

        return $this;
    }

    /**
     * Add Qualitative
     *
     * @param string $criteriaName
     * @return self
     */
    public function addQualitativeCriteria(string $criteriaName): self
    {
        $this->criterias[] = [
            "name" => $criteriaName,
            "type" => self::QUALITATIVE,
        ];

        return $this;
    }

    /**
     * Finalize of the AHP methode
     *
     * @return self
     */
    public function finalize(): self
    {
        if (count($this->criteriaPairWise) != count($this->criterias)) {
            throw new \ErrorException('Error');
        }

        $m1    = [];
        $ranks = [];
        for ($i = 0; $i < count($this->candidates); $i++) {
            $m1[$i] = [];
            $j      = 0;
            $r      = ["name" => $this->candidates[$i], "value" => 0];

            foreach ($this->criteriaPairWise as $key => $criteriaPairWise) {
                $m1[$i][$j] = $criteriaPairWise["eigen"][$i];
                $r["value"] += $m1[$i][$j] * $this->eigenVector[$j];
                $j++;
            }

            $ranks[] = $r;
        }

        $this->finalRanks  = $ranks;
        $this->finalMatrix = $m1;

        return $this;
    }

    /**
     * Set relative matrix
     *
     * @param array $matrix
     * @return self
     */
    public function setRelativeInterestMatrix(array $matrix): self
    {
        $size = count($this->criterias);

        if ($size != count($matrix)) {
            throw new Exception("Error Processing Request", 1);
        }

        foreach ($matrix as $i => $m) {
            if ($size != count($matrix)) {
                throw new ServiceException("Error Processing Request", 1);
            }

            for ($j = 0; $j < count($m); $j++) {
                if ($i == $j) {
                    if ($matrix[$i][$j] != 1) {
                        throw new ServiceException("Matrix diagonal should have value : 1", 1);
                    }
                } else {
                    if ($matrix[$i][$j] != null) {
                        $matrix[$j][$i] = 1 / $matrix[$i][$j];
                    } else {
                        $matrix[$i][$j] = 1 / $matrix[$j][$i];
                    }
                }
            }
        }

        $do = $this->normalizeRelativeInterestMatrixAndCountEigen($matrix);

        $this->relativeMatrix = $do["matrix"];
        $this->eigenVector    = $do["eigen"];

        return $this;
    }

    /**
     * Set candidates
     *
     * @param array $candidates
     * @return void
     */
    public function setCandidates(array $candidates): void
    {
        $this->candidates = $candidates;
    }

    /**
     * Set criteria pairwaise
     *
     * @param string $criteriaName
     * @param array  $matrix
     * @return bool
     */
    public function setCriteriaPairWise($criteriaName, array $matrix)
    {
        $id = array_search($criteriaName, array_column($this->criterias, "name"));
        if (!is_numeric($id)) {
            throw new ServiceException("Criteria $criteriaName not found.", 1);
        }

        return $this->criterias[$id]["type"] == static::QUALITATIVE
        ? $this->setCriteriaPairWiseQualitative($criteriaName, $matrix)
        : $this->setCriteriaPairWiseQuantitative($criteriaName, $matrix);
    }

    /**
     * Set batch criteria
     *
     * @param array $matrxix
     * @return self
     */
    public function setBatchCriteriaPairWise(array $matrix): self
    {
        $this->criteriaPairWise = [];
        foreach ($matrix as $key => $value) {
            $this->setCriteriaPairWise($key, $value);
        }

        return $this;
    }

    /**
     * Normalisize Relative Matrix and Count Eigen (λ)
     *
     * @param  array  $matrixes
     * @return array
     */
    private function normalizeRelativeInterestMatrixAndCountEigen(array $matrix): array
    {
        $total = [];
        $eigen = [];
        $count = count($matrix);

        for ($i = 0; $i < $count; $i++) {
            for ($j = 0; $j < $count; $j++) {
                if (!isset($total[$j])) {
                    $total[$j] = 0;
                }
                $total[$j] += $matrix[$i][$j];
            }
        }

        for ($i = 0; $i < $count; $i++) {
            $eigen[$i] = 0;
            for ($j = 0; $j < $count; $j++) {
                $matrix[$i][$j] /= $total[$j];
                $eigen[$i] += $matrix[$i][$j];
            }

            $eigen[$i] /= $count;
        }

        return [
            "matrix" => $matrix,
            "eigen"  => $eigen,
        ];
    }

    /**
     * Set the criteria QUANTITATIVE
     * QUANTITATIVE matrix size 4x1
     *
     * @param string $criteriaName
     * @param array  $matrix
     * @return self
     */
    public function setCriteriaPairWiseQuantitative($criteriaName, array $matrix): self
    {
        $size = count($this->candidates);

        if ($size != count($matrix)) {
            throw new ServiceException('Quantitative Pairwise should have matrix sized ' . $size . 'x1');
        }

        $total = array_sum($matrix);

        foreach ($matrix as $key => $value) {
            if (is_array($value)) {
                throw new ServiceException('Quantitative Pairwise should have matrix sized ' . $size . 'x1');
            } else {
                $matrixEigen[] = $value / $total;
            }
        }

        $this->criteriaPairWise[$criteriaName]["matrix"] = $matrix;
        $this->criteriaPairWise[$criteriaName]["eigen"]  = $matrixEigen;

        return $this;
    }

    /**
     * Set criteria pair wise QUALITATIVE
     * QUALITATIVE matrix size 4x4
     * 
     * @param string $criteriaName
     * @param array  $matrix
     * @return self
     */
    public function setCriteriaPairWiseQualitative($criteriaName, array $matrix): self
    {
        $size = count($this->candidates);

        if ($size != count($matrix)) {
            throw new SeriveException("Matrix size should be $size x $size");
        }

        foreach ($matrix as $i => $m) {
            if ($size != count($m)) {
                throw new SeriveException("Matrix size should be $size x $size");
            }

            for ($j = 0; $j < count($m); $j++) {
                if ($i == $j) {
                    if ($matrix[$i][$j] != 1) {
                        throw new SeriveException("Matrix size should be $size x $size");
                    } else {
                        if ($matrix[$i][$j] != null) {
                            $matrix[$j][$i] = 1 / $matrix[$i][$j];
                        } else {
                            $matrix[$i][$j] = 1 / $matrix[$j][$i];
                        }
                    }
                }
            }
        }

        $this->criteria[$criteriaName]               = $matrix;
        $this->criteriaPairWise[$criteriaName]       = $this->normalizeRelativeInterestMatrixAndCountEigen($matrix);
        $this->criteriaPairWise[$criteriaName]["cr"] = $this->concistencyCheck($matrix, $this->criteriaPairWise[$criteriaName]["eigen"]);

        return $this;
    }

    /**
     * Callculate consistency
     *
     * @param  array  $matrix
     * @param  string $reigen
     * @return float
     */
    private function concistencyCheck(array $matrix, $eigen): float
    {
        $dmax  = 0;
        $count = count($matrix);

        for ($i = 0; $i < $count; $i++) {
            $e = 0;
            for ($j = 0; $j < $count; $j++) {
                $e += $matrix[$j][$i];
            }

            $dmax += $e * $eigen[$i];
        }

        $ci = ($dmax - $count) / ($count - 1);

        $cr = $ci / $this->getIr($count);

        return $cr;
    }
}
