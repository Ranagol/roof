<?php


namespace App\Exceptions;


class DataExtractionException extends RoofException
{


    /**
     * {@inheritDoc}
     *
     * @param string     $directorySearched Which directory was not found.
     * @param \Throwable $previous  The previous throwable used for the exception chaining.
     */
    public function __construct(string $dataExtracted, \Throwable $previous = NULL)
    {
        $this->logMessage = 'Data we tried to extract: ' . $dataExtracted . PHP_EOL;

        parent::__construct($this->logMessage, 500, $previous);

    }


}
