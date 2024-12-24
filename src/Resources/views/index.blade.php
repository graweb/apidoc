<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title inertia>{{ config('app.name', 'Laravel') }} - Documentação da API</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex h-screen w-screen bg-gray-100">
    <!-- Sidebar -->
    <aside id="sidebar"
        class="w-80 bg-blue-900 overflow-x-hidden text-white h-full shadow-lg absolute top-0 lg:static transition-all duration-300 transform -translate-x-full opacity-0 lg:translate-x-0 lg:opacity-100">
        <div class="p-4 sticky top-0 bg-blue-900">
            <header class="w-full flex justify-between items-center">
                <h2 class="text-lg font-bold">Documentação da API</h2>
                <button
                    class="block lg:hidden bg-transparent font-bold text-lg rounded-full outline-none border-none w-fit"
                    onclick="toggleSidebar()">
                    <svg width="18px" height="18px" viewBox="0 -0.5 8 8" version="1.1"
                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">

                        <title>close_mini [#1522]</title>
                        <desc>Created with Sketch.</desc>
                        <defs>

                        </defs>
                        <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g id="Dribbble-Light-Preview" transform="translate(-385.000000, -206.000000)"
                                fill="#FFFFFF">
                                <g id="icons" transform="translate(56.000000, 160.000000)">
                                    <polygon id="close_mini-[#1522]"
                                        points="334.6 49.5 337 51.6 335.4 53 333 50.9 330.6 53 329 51.6 331.4 49.5 329 47.4 330.6 46 333 48.1 335.4 46 337 47.4">
                                    </polygon>
                                </g>
                            </g>
                        </g>
                    </svg>
                </button>
            </header>
            <div class="relative mt-4">
                <input type="text" id="searchInput" placeholder="Buscar..."
                    class="w-full bg-gray-800 text-white placeholder-gray-400 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-gray-500"
                    oninput="filterRoutes()" />
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute top-2 right-3 h-5 w-5 text-gray-400"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35M10 17a7 7 0 100-14 7 7 0 000 14z" />
                </svg>
            </div>
        </div>
        <nav class="overflow-y-auto">
            <ul id="routeList" class="space-y-2 p-4">
                @foreach ($routes as $group => $groupRoutes)
                    <li class="route-item">
                        <a href="#{{ $group }}" class="block text-white hover:bg-blue-700 px-3 py-2 rounded">
                            {{ ucfirst($group) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6 overflow-auto scroll-smooth">
        <header class="w-full flex justify-between items-center">
            <h1 class="text-2xl font-bold">Instruções</h1>
            <button class="block lg:hidden bg-transparent font-bold text-2xl outline-none border-none w-fit"
                onclick="toggleSidebar()">
                <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 18L20 18" stroke="#000000" stroke-width="2" stroke-linecap="round" />
                    <path d="M4 12L20 12" stroke="#000000" stroke-width="2" stroke-linecap="round" />
                    <path d="M4 6L20 6" stroke="#000000" stroke-width="2" stroke-linecap="round" />
                </svg>
            </button>
        </header>
        <div class="space-y-6">
            <section class="route-section p-4 bg-white shadow rounded">
                <p>Aplique o modelo de comentário abaixo em cada função do controller</p>
                <p>Se o modelo abaixo estiver em branco ou fora do padrão, o retorno vai ser <b>"Não encontrado"</b></p>
                <div class="bg-gray-100 border border-gray-300 text-left text-sm font-mono p-2">
                    <p><code>/**</code></p>
                    <p><code> * @title Ação que o código realiza</code></p>
                    <p><code> * @param [string] $email Parâmetro e-mail do usuário (obrigatório)</code></p>
                    <p><code> * @param [string] $password Parâmetro senha do usuário (obrigatório)</code></p>
                    <p><code> * @param ... </code></p>
                    <p><code> * @success 200</code></p>
                    <p><code> * @erro 404</code></p>
                    <p><code> */</code></p>
                </div>
            </section>

            @foreach ($routes as $group => $groupRoutes)
                <section id="{{ $group }}" class="route-section p-4 bg-white shadow rounded">
                    <h2 class="text-xl font-semibold">{{ ucfirst($group) }}</h2>
                    <ul class="mt-4 space-y-2">
                        @foreach ($groupRoutes as $route)
                            <li class="p-4 bg-gray-50 border rounded">
                                <p><strong>Ação: </strong> {{ $route['title'] }}</p>
                                <p><strong>Requisição: </strong> {{ $route['uri'] }}</p>
                                <p>
                                    <strong>Métodos: </strong>
                                    @foreach ($route['method'] as $method)
                                        <span
                                            class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $method == 'GET' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $method == 'POST' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $method == 'PUT' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $method == 'DELETE' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $method == 'HEAD' || $method == 'PATCH' ? 'bg-gray-200 text-gray-800' : '' }}">
                                            {{ $method }}
                                        </span>
                                    @endforeach
                                </p>
                                <p><strong>Arquivo: </strong> {{ $route['action'] }}</p>

                                @if (!empty($route['comment']) && is_string($route['comment']))
                                    <p><strong>Descrição: </strong> {{ $route['comment'] }}</p>
                                @endif

                                @if (!empty($route['params_comments']) && is_array($route['params_comments']))
                                    <p><strong>Parâmetros:</strong></p>
                                    <ul class="pl-4 list-disc">
                                        @foreach ($route['params_comments'] as $param)
                                            <li>
                                                {{ $param['name'] . ' (' . $param['type'] . ') - ' . $param['description'] }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif

                                @if (!empty($route['return_comment']) && is_string($route['return_comment']))
                                    <p><strong>Retorno:</strong> {{ $route['return_comment'] }}</p>
                                @endif

                                <p>
                                    <strong>Sucesso: </strong>
                                    @if ($route['success'] == '201')
                                        <span class="font-bold text-green-800">{{ $route['success'] }}</span>
                                    @else
                                        {{ $route['success'] }}
                                    @endif
                                </p>
                                <p>
                                    <strong>Erro: </strong>
                                    @if ($route['erro'] == '404')
                                        <span class="font-bold text-red-800">{{ $route['erro'] }}</span>
                                    @else
                                        {{ $route['erro'] }}
                                    @endif
                                </p>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endforeach
        </div>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const menuItems = document.querySelectorAll("#routeList .route-item a");

            menuItems.forEach(item => {
                item.addEventListener("click", () => {
                    // Remove a classe ativa de todos os itens
                    menuItems.forEach(i => i.classList.remove("bg-blue-700", "font-bold"));

                    // Adiciona a classe ativa ao item clicado
                    item.classList.add("bg-blue-700", "font-bold");
                });
            });
        });

        function filterRoutes() {
            const input = document.getElementById("searchInput").value.toLowerCase();
            const routeItems = document.querySelectorAll(".route-item");
            const routeSections = document.querySelectorAll(".route-section");

            // Se o campo de busca estiver vazio, rola até o topo da página
            if (input === "") {
                window.scrollTo(0, 0);
            }

            // Filtra as rotas conforme o texto de entrada
            routeItems.forEach((item) => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(input) ? "block" : "none";
            });

            routeSections.forEach((section) => {
                const sectionId = section.id.toLowerCase();
                const matchesInput = sectionId.includes(input);
                section.style.display = matchesInput || input === "" ? "block" : "none";
            });
        }

        function filterRoutes() {
            const input = document.getElementById("searchInput").value.toLowerCase();
            const routeItems = document.querySelectorAll(".route-item");
            const routeSections = document.querySelectorAll(".route-section");

            routeItems.forEach((item) => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(input) ? "block" : "none";
            });

            routeSections.forEach((section) => {
                const sectionId = section.id.toLowerCase();
                const matchesInput = sectionId.includes(input);
                section.style.display = matchesInput || input === "" ? "block" : "none";
            });
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');

            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full', 'opacity-0');
                sidebar.classList.add('translate-x-0', 'opacity-100');
            } else {
                sidebar.classList.remove('translate-x-0', 'opacity-100');
                sidebar.classList.add('-translate-x-full', 'opacity-0');
            }
        }
    </script>
</body>

</html>
