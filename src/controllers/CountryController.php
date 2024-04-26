<?php

namespace App\Controllers;

use App\Models\CountryModel;

class CountryController
{
    /**
     * Country Model
     *
     * @var CountryModel $countryModel
     */
    private CountryModel $countryModel;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->countryModel = new CountryModel();
    }

    /**
     * Search for a country by name, code, or currency.
     *
     * @param $searchTerm
     * @param $sort
     * @return array
     */
    public function search( $searchTerm, $sort ): array
    {
        return $this->countryModel->search( $searchTerm, $sort );
    }

    /**
     * Get the sort name parameter.
     *
     * @param $searchString
     * @param $sort
     * @return string
     */
    public function getSortName( $searchString, $sort ): string
    {
        return $this->countryModel->getSortName( $searchString, $sort );
    }

    /**
     * Get the sort population parameter.
     *
     * @param $searchString
     * @param $sort
     * @return string
     */
    public function getSortPopulation( $searchString, $sort ): string
    {
        return $this->countryModel->getSortPopulation( $searchString, $sort );
    }
}