<?php

namespace App\Exceptions;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Class DarwinException
 */
abstract class RoofException extends Exception
{

    /**
     * Message for Log file.
     *
     * @var string
     */
    protected $logMessage = '';


    /**
     * {@inheritDoc}
     */
    public function __construct($message = '', $code = 0, \Throwable $previous = NULL)
    {
        parent::__construct($message, $code, $previous);

        $this->logMessage = $this->generateLogMessage($this, $this->logMessage);

    }//end __construct()


    /**
     * Report or log an exception.
     *
     * @return void
     */
    public function report(): void
    {
        Log::debug($this->logMessage);

    }//end report()


    /**
     * Log Message Getter.
     *
     * @return string
     */
    public function getLogMessage(): string
    {
        return $this->logMessage;

    }//end getLogMessage()


    /**
     * Generates Log Message with all Previous messages attached.
     *
     * @param \Throwable|null $error Previous Error message.
     * @param string $message Previous Message that should be concatenated to.
     *
     * @return string
     */
    private function generateLogMessage(\Throwable $error = NULL, string $message = ''): string
    {
        if ($error === NULL) {
            $datetime = Carbon::now();

            return PHP_EOL .
                '< =================================== ' . $datetime . ' =================================== >' .
                PHP_EOL .
                $message;
        }

        $file = $error->getFile();
        $line = $error->getLine();

        $previousError = $error->getPrevious();

        $prepend = '';
        if ($previousError !== NULL) {
            $prepend = PHP_EOL . '--------------------------------------------------------------------' . PHP_EOL;
        }

        $errorMessage  = $prepend;
        $errorMessage .= PHP_EOL . $file . ' line ' . $line . ':' . PHP_EOL;
        $errorMessage .= PHP_EOL;
        $errorMessage .= PHP_EOL . $error->getMessage() . PHP_EOL;
        $errorMessage .= $message;

        return $this->generateLogMessage($previousError, $errorMessage);

    }//end generateLogMessage()


}//end class
