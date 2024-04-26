<?php

namespace App\Models;

class CountryModel
{
    /**
     * API URL.
     */
    const API_URL = "https://restcountries.com/v3.1";

    /**
     * API endpoints.
     */

    const ENDPOINT_NAME = "/name/";

    const ENDPOINT_CODE = "/alpha/";

    const ENDPOINT_CURRENCY = "/currency/";

    /**
     * Search for the country.
     *
     * @param string $searchTerm
     * @param string $sort
     * @return array
     */
    public function search( string $searchTerm, string $sort = ""): array
    {
        $searchTerm = $this->sanitizeSearchTerm( $searchTerm );
        $results = [];

        // search by name
        $names = $this->curlMe( self::ENDPOINT_NAME . $searchTerm );
        if ( $names !== false ) {
            foreach( $names as $name ) {
                $results[] = $name;
            }
        }

        // search by code
        $codes = $this->curlMe( self::ENDPOINT_CODE . $searchTerm );
        if ( $codes !== false ) {
            foreach ( $codes as $code ) {
                $results[] = $code;
            }
        }

        // search by currency
        $currencies = $this->curlMe( self::ENDPOINT_CURRENCY . $searchTerm );
        if ( $currencies !== false ) {
            foreach($currencies as $currency) {
                $results[] = $currency;
            }
        }

        $results = array_unique( $results, SORT_REGULAR );
        return $this->parseResults( $results, $sort );
    }

    /**
     * Sanitize the string and get it ready for passing to the API.
     *
     * @param string $searchTerm
     * @return string
     */
    private function sanitizeSearchTerm( string $searchTerm ): string
    {
        $sanitized = trim( $searchTerm );
        $sanitized = str_replace( " ", "%20", $sanitized );
        return htmlspecialchars( $sanitized );
    }

    /**
     * Parse the results into something we can use.
     * Do some sorting if any required.
     *
     * @param array $results
     * @param string $sort
     * @return array
     */
    private function parseResults( array $results, string $sort ): array
    {
        $data = [];
        foreach ( $results as $result ) {
            // get the currencies
            $currencyDisplay = '';
            $currencies = $result['currencies'] ?? [];
            foreach ( $currencies as $currency ) {
                // if only one currency, don't add a comma
                if ( count( $currencies ) === 1 ) {
                    $currencyDisplay .= $currency['name'];
                } else if ( $currency === end( $currencies ) ) {
                    $currencyDisplay .= $currency['name'];
                } else {
                    $currencyDisplay .= $currency['name'] . ", ";
                }
            }

            // set the data in an array we can sort over
            $data[] = array (
                "name" => $result['name']['official'] ?? '',
                "population" => $result['population'] ?? '',
                "region" => $result['region'] ?? '',
                "subregion" => $result['subregion'] ?? '',
                "currency" => $currencyDisplay,
                "flag" => $result['flags']['svg'] ?? ''
            );
        }

        if ( $sort === '' ) {
            // sort data by name asc
            usort( $data, function( $a, $b ) {
                return $a['name'] <=> $b['name'];
            });
        } else if ( $sort === 'name-desc' ) {
            // sort data by name desc
            usort( $data, function( $a, $b ) {
                return $b['name'] <=> $a['name'];
            });
        } else if ( $sort === 'population-asc' ) {
            // sort data by population asc
            usort( $data, function( $a, $b ) {
                return $a['population'] <=> $b['population'];
            });
        } else if ( $sort === 'population-desc' ) {
            // sort data by population desc
            usort( $data, function( $a, $b ) {
                return $b['population'] <=> $a['population'];
            });
        } else {
            // default to name asc
            usort( $data, function( $a, $b ) {
                return $a['name'] <=> $b['name'];
            });
        }
        return $data;
    }

    /**
     * Get sort name parameters.
     *
     * @param $searchString
     * @param $sort
     * @return string
     */
    public function getSortName( $searchString, $sort ): string
    {
        if ($sort === 'name-desc') {
            return '/?search=' . $searchString . '&sort=name-asc';
        } else {
            return '/?search=' . $searchString . '&sort=name-desc';
        }
    }

    /**
     * Get sort population parameters.
     *
     * @param $searchString
     * @param $sort
     * @return string
     */
    public function getSortPopulation( $searchString, $sort ): string
    {
        if ($sort === 'population-asc') {
            return '/?search=' . $searchString . '&sort=population-desc';
        } else {
            return '/?search=' . $searchString . '&sort=population-asc';
        }
    }

    /**
     * Curl the endpoint.
     *
     * @param string $url
     * @return array|bool
     */
    private function curlMe( string $url ): array|bool
    {
        // set up curl
        $curl = curl_init();
        curl_setopt_array( $curl, array(
            CURLOPT_URL => self::API_URL . $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        // get response
        $response = curl_exec( $curl );

        // get response code
        $responseCode = curl_getinfo( $curl, CURLINFO_HTTP_CODE );

        // close curl
        curl_close( $curl );

        // check is response was good
        if ( $responseCode !== 200 ) {
            // return false if not 200
            return false;
        } else {
            // decode the json
            return json_decode( $response, true );
        }
    }
}