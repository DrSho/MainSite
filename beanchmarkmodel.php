<?php

/**
 * Created by PhpStorm.
 * User: Genesis
 * Date: 2/25/2017
 * Time: 9:59 AM
 */
class BenchmarkModel
{
    //Table health_benchmark
    private $health_type_id = "";
    private $health_type = "";
    private $display_result = "";
    private $lowest_value = "";
    private $highest_value = "";

    /**
     * @return string
     */
    public function getHealthTypeId(): string
    {
        return $this->health_type_id;
    }

    /**
     * @param string $health_type_id
     */
    public function setHealthTypeId(string $health_type_id)
    {
        $this->health_type_id = $health_type_id;
    }

    /**
     * @return string
     */
    public function getHealthType(): string
    {
        return $this->health_type;
    }

    /**
     * @param string $health_type
     */
    public function setHealthType(string $health_type)
    {
        $this->health_type = $health_type;
    }

    /**
     * @return string
     */
    public function getDisplayResult(): string
    {
        return $this->display_result;
    }

    /**
     * @param string $display_result
     */
    public function setDisplayResult(string $display_result)
    {
        $this->display_result = $display_result;
    }

    /**
     * @return string
     */
    public function getLowestValue(): string
    {
        return $this->lowest_value;
    }

    /**
     * @param string $lowest_value
     */
    public function setLowestValue(string $lowest_value)
    {
        $this->lowest_value = $lowest_value;
    }

    /**
     * @return string
     */
    public function getHighestValue(): string
    {
        return $this->highest_value;
    }

    /**
     * @param string $highest_value
     */
    public function setHighestValue(string $highest_value)
    {
        $this->highest_value = $highest_value;
    }


}