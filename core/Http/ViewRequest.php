<?php

namespace Oneago\Arcturus\Core\Http;

use Exception;
use JetBrains\PhpStorm\Pure;

class ViewRequest
{

    /**
     * Router constructor.
     * @param string $view
     * @param string|null $viewFolder
     * @param array|null $customVars
     * @param string $controllerMethod
     */
    #[Pure] public function __construct(
        protected string $view,
        protected ?string $viewFolder = null,
        protected ?array $customVars = null,
        protected string $controllerMethod = 'index'
    )
    {
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getHTML(): string
    {
        $controller = $this->getController();

        $response = call_user_func([
            new $controller,
            $this->controllerMethod
        ], $this->view, $this->customVars);

        if ($response instanceof ViewResponse) {
            return $response->render();
        }

        if (is_string($response)) {
            return $response;
        }

        throw new Exception("Error processing request, response no is instance of App\Http\ViewResponse or String", 1);
    }

    /**
     * @return string
     */
    #[Pure] private function getController(): string
    {
        $controller = ucfirst($this->view);
        $folder = $this->viewFolder === null ? "" : "\\" . ucfirst($this->viewFolder);
        return "App\Http\Controllers$folder\\{$controller}Controller";
    }
}