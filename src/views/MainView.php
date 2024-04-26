<?php

namespace App\Views;

class MainView
{
    /**
     * Output variable that will hold the HTML output.
     * @var string $output
     */
    private string $output;

    /**
     * Search term variable that will hold the search term.
     * @var string $searchTerm
     */
    private string $searchTerm;

    /**
     * Class constructor method that's just setting a variable for output.
     */
    public function __construct()
    {
        $this->output = "";
    }

    /**
     * Render the home page view.
     *
     * @param array $results
     * @param string $searchTerm
     * @param string $sortName
     * @param string $sortPopulation
     * @return string
     */
    public function render(array $results, string $searchTerm = "", string $sortName = "", string $sortPopulation = "" ): string
    {
        $this->searchTerm = $searchTerm;

        $this->output .= $this->renderHeader();
        $this->output .= $this->renderResults( $results, $sortName, $sortPopulation );
        $this->output .= $this->renderFooter();

        return $this->output;
    }

    /**
     * Render the header of the page.
     *
     * @return string
     */
    private function renderHeader(): string
    {
        $o = "<!DOCTYPE html>
                    <html lang='en'>
                    <head>
                        <meta charset='UTF-8'>
                        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                        <title>Home</title>
                        <link rel='stylesheet' href='/css/style.css'>
                    </head>
                    <body>
                        <header>
                            <h1>Extreme Engineering Services - Code Challenge</h1>";
                            $o .= $this->renderSearchBar();
                 $o .= "</header>";
        return $o;
    }

    /**
     * Render the search bar.
     *
     * @return string
     */
    private function renderSearchBar(): string
    {
        return "<form method='GET' action='/'>
                    <input type='text' name='search' placeholder='Search...' value='" . $this->searchTerm . "'>
                    <button type='submit'>Search</button>
                </form>
                <p>Please enter a country name, code, or currency name to search.</p>
                ";
    }

    /**
     * Display the results in a table.
     *
     * @param array $results
     * @param string $sortName
     * @param string $sortPopulation
     * @return string
     */
    private function renderResults( array $results, string $sortName, string $sortPopulation ): string
    {
        $count = count($results);
        $o = "<main>";
        if ( $results !== [] ) {
            $o .= "<table>";

                // table headers
                $o .= "<tr>";
                    if ($count > 1) {
                        // If no sort, default to name asc
                        $o .= "<th><a href='" . $sortName . "'>Name</a></th>";
                        $o .= "<th><a href='" . $sortPopulation ." '>Population</a></th>";
                    } else {
                        $o .= "<th>Name</th>";
                        $o .= "<th>Population</th>";
                    }
                    $o .= "<th>Region</th>";
                    $o .= "<th>Subregion</th>";
                    $o .= "<th>Currency</th>";
                    $o .= "<th>Flag</th>";
                $o .= "</tr>";

                // iterate over results
                foreach ( $results as $result ) {
                    $o .= "<tr>";
                        $o .= "<td>" . $result['name'] . "</td>";
                        $o .= "<td>" . number_format($result['population']) . "</td>";
                        $o .= "<td>" . $result['region'] . "</td>";
                        $o .= "<td>" . $result['subregion'] . "</td>";
                        $o .= "<td>" . $result['currency'] . "</td>";
                        $o .= "<td><img src='" . $result['flag'] . "' alt='Flag of " . $result['name'] . "' width='100'></td>";
                    $o .= "</tr>";
                }
                $o .= "</table>";
            $o .= "</main>";
        } else {
            $o = "<p>No results found.</p>";
        }
        return $o;
    }

    /**
     * Render the footer of the page.
     *
     * @return string
     */
    private function renderFooter(): string
    {
        return "<footer>
                    <p>Andrew Derse &copy; 2024</p>
                </footer>
            </body>
        </html>";
    }

}