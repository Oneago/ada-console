<?php

namespace Oneago\Arcturus\Core\Http;

use Exception;
use JetBrains\PhpStorm\Pure;

class ApiRequest
{

    /**
     * Router constructor.
     * @param string $apiFile
     * @param array|null $arrayArgs
     * @param string $apiMethod
     */
    #[Pure] public function __construct(
        protected string $apiFile,
        protected ?array $arrayArgs = null,
        protected string $apiMethod = 'index'
    )
    {
    }

    /**
     * @return string
     * @throws Exception
     */
    public function run(): string
    {
        $api = $this->getApi();

        if (!class_exists($api)) {
            header("Content-Type: application/json");
            http_response_code(404);
            return "{\"error\":\"Resource $this->apiFile not found\"}";
        }

        return call_user_func([
            new $api,
            $this->apiMethod
        ], $this->arrayArgs);

    }

    /**
     * @return string
     */
    #[Pure] private function getApi(): string
    {
        $api = ucfirst($this->apiFile);
        return "App\Http\Apis\\{$api}Api";
    }
}