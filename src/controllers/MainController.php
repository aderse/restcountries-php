<?php

namespace App\Controllers;

use App\Views\MainView;

class MainController
{
    /**
     * Home View
     *
     * @var MainView $view
     */
    private MainView $view;

    /**
     * Country Controller.
     *
     * @var CountryController $countryController
     */
    private CountryController $countryController;

    /**
     * Search term variable that will hold the search term.
     *
     * @var string $searchTerm
     */
    private string $searchTerm;

    /**
     * Sort parameter.
     *
     * @var string $sort
     */
    private string $sort;

    /**
     * Class constructor that will set up our page.
     */
    public function __construct()
    {
        $this->view = new MainView();
        $this->countryController = new CountryController();
        $this->searchTerm = $_GET['search'] ?? "";
        $this->sort = $_GET['sort'] ?? "";
    }

    /**
     * This method will render the home page.
     *
     * @return void
     */
    public function index(): void
    {
        // get search results
        $results = [];
        if ( $this->searchTerm !== "" ) {
            $results = $this->countryController->search( $this->searchTerm, $this->sort );
        }

        // get sorting parameters
        $sortName = $this->countryController->getSortName( $this->searchTerm, $this->sort );
        $sortPopulation = $this->countryController->getSortPopulation( $this->searchTerm, $this->sort );

        echo $this->view->render( $results, $this->searchTerm, $sortName, $sortPopulation );
    }

}