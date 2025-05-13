<?php

namespace Meta\ThirdPartyOrder\Exceptions;

class ThirdPartyRequestException extends \Exception
{
    /**
     * @var array
     */
    private $errorArray = [];

    /**
     * @param \Throwable|null $error
     */
    public function __construct(\Throwable $error)
    {
        if ($error instanceof \GuzzleHttp\Exception\RequestException) {
            $this->errorArray = [
                'success' => false,
                'error' => $error->getResponse()->getBody()->getContents()
            ];
        } else if ($error instanceof \Exception) {
            $this->errorArray = [
                'success' => false,
                'error' => $error->getMessage(),
            ];
        } else {
            $this->errorArray = [
                'success' => false,
                'error' => $error,
            ];
        }

        parent::__construct($error->getMessage(), $error->getCode(), $error);
    }

    /**
     * @return array
     */
    public function getArray(): array
    {
        return $this->errorArray;
    }
}