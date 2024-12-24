<?php

namespace Graweb\Apidoc\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;
use ReflectionClass;
use ReflectionMethod;

class GrawebApiDocController extends Controller
{
    /**
     * @title Retorna as rotas/requisições da plataforma
     */
    public function index()
    {
        $getRoutes = Route::getRoutes();
        $routes = [];

        foreach ($getRoutes as $route) {
            $prefix = explode('/', $route->uri)[1] ?? 'default';

            $action = $route->getActionName();

            // Verificar se a ação do route é válida (controller@method)
            if (str_contains($action, '@')) {
                [$controller, $method] = explode('@', $action);

                try {
                    // Usar Reflection para pegar os comentários (docblock)
                    $reflection = new ReflectionClass($controller);

                    if ($reflection->hasMethod($method)) {
                        $reflectionMethod = $reflection->getMethod($method);

                        // Obter o comentário do método
                        $docComment = $reflectionMethod->getDocComment();

                        // Limpar o comentário, removendo os caracteres indesejados
                        $docComment = $this->cleanDocComment($docComment);

                        // Se o docComment for nulo, atribua uma string vazia para evitar erros
                        $docComment = $docComment ?? '';

                        // Obter os comentários dos parâmetros
                        $paramsComments = $this->getParamComments($reflectionMethod);

                        // Verificar se o docComment não é nulo antes de tentar obter o comentário do retorno
                        $returnComment = $docComment ? $this->getReturnComment($docComment) : null;

                        $title = $this->getTitle($reflectionMethod);
                        $error = $this->getError($reflectionMethod);
                        $success = $this->getSuccess($reflectionMethod);
                    } else {
                        $commentDetails = $paramsComments = $returnComment = null;
                    }
                } catch (\ReflectionException $e) {
                    $commentDetails = $paramsComments = $returnComment = null;
                }
            } else {
                $commentDetails = $paramsComments = $returnComment = null;
            }

            $routes[$prefix][] = [
                'title' => $title,
                'erro' => $error,
                'success' => $success,
                'uri' => $route->uri,
                'method' => $route->methods(),
                'name' => $route->getName(),
                'action' => $route->getActionName(),
                'params_comments' => $paramsComments,
                'return_comment' => $returnComment
            ];
        }

        unset($routes['default'], $routes['{endpoint}'], $routes['']);
        return view('apidoc::index', compact('routes'));
    }

    /**
     * Extrai os comentários dos parâmetros do método.
     *
     * @param ReflectionMethod $method
     * @return array
     */
    private function getTitle(ReflectionMethod $method): string
    {
        $title = "Não encontrado";
        $docComment = $method->getDocComment();
        preg_match('/@title\s+([^\n]+)/', $docComment, $matches);
        if (isset($matches[1])) {
            $title = $matches[1];
        }

        return $title;
    }

    /**
     * Extrai os comentários dos parâmetros do método.
     *
     * @param ReflectionMethod $method
     * @return array
     */
    private function getError(ReflectionMethod $method): string
    {
        $error = "Não encontrado";
        $docComment = $method->getDocComment();
        preg_match('/@erro\s+([^\n]+)/', $docComment, $matches);
        if (isset($matches[1])) {
            $error = $matches[1];
        }

        return $error;
    }

    /**
     * Extrai os comentários dos parâmetros do método.
     *
     * @param ReflectionMethod $method
     * @return array
     */
    private function getSuccess(ReflectionMethod $method): string
    {
        $success = "Não encontrado";
        $docComment = $method->getDocComment();
        preg_match('/@success\s+([^\n]+)/', $docComment, $matches);
        if (isset($matches[1])) {
            $success = $matches[1];
        }

        return $success;
    }

    /**
     * Extrai os comentários dos parâmetros do método.
     *
     * @param ReflectionMethod $method
     * @return array
     */
    private function getParamComments(ReflectionMethod $method): array
    {
        $paramsComments = [];

        // Obtém os comentários da função/método
        $docComment = $method->getDocComment();

        // Processa o comentário do DocBlock para capturar todos os parâmetros
        if ($docComment) {
            preg_match_all('/@param\s+\[([^\]]+)]\s+\$(\w+)\s+(.+)/', $docComment, $matches, PREG_SET_ORDER);

            foreach ($matches as $match) {
                // Adiciona os detalhes do parâmetro
                $paramsComments[] = [
                    'name' => $match[2],
                    'type' => $match[1],
                    'description' => trim($match[3]),
                ];
            }
        }

        return $paramsComments;
    }

    /**
     * Extrai o comentário do retorno do método.
     *
     * @param string $docComment
     * @return string|null
     */
    private function getReturnComment(string $docComment): ?string
    {
        preg_match('/@return\s+([^\s]+)/', $docComment, $matches);

        return $matches[1] ?? null;
    }

    /**
     * Limpa o docblock para retornar apenas o texto.
     *
     * @param string|null $docComment
     * @return string|null
     */
    private function cleanDocComment(?string $docComment): ?string
    {
        if (!$docComment) {
            return null;
        }

        // Remove os delimitadores do docblock e os asteriscos
        $lines = explode("\n", $docComment);
        $lines = array_map(function ($line) {
            $line = trim($line);
            return preg_replace('/^[\/\*\s]+|[\*\s]+$/', '', $line); // Remove "/*", "*", "*/" e espaços extras
        }, $lines);

        // Junta as linhas restantes, removendo as vazias
        $cleaned = implode(' ', array_filter($lines));

        return trim($cleaned);
    }
}
