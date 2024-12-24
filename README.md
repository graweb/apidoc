## Instalação

### Tente com
- composer require graweb/apidoc

### Se não funcionar, tente com
- composer require graweb/apidoc:*@dev

## Instruções

1 - Coloque o modelo de comentário em cada função do controller
```
    /**
     * @title Faz o login na plataforma
     * @param [string] $email E-mail do usuário (obrigatório)
     * @param [string] $password Senha do usuário (obrigatório)
     * @param ...
     * @success 200
     * @erro 404
    */
```
2 - ```localhost/apidoc```
