# 🐶 Dog Walker

Sistema para agendamento de passeios de cães, permitindo que tutores encontrem passeadores, cadastrem seus cães e realizem avaliações após os passeios.

---

# 📋 Funcionalidades

- Cadastro e login de usuários
- Cadastro de cachorros
- Busca de cachorros
- Listagem de cachorros
- Edição de cachorros
- Exclusão de cachorros
- Listagem de passeadores
- Agendamento de passeios
- Gerenciamento de passeios
- Visualização de tutor
- Avaliação de tutores e passeadores
- Edição do perfil do usuário
- Logout

---

# 📖 Descrição das funcionalidades

## 1. Cadastro e Login de Usuários

O usuário deve criar uma conta para acessar o sistema. Durante o cadastro é necessário escolher o tipo de usuário:

- Tutor
- Passeador

Após o login, cada tipo de usuário possui funcionalidades específicas.

---

## 2. Cadastro de Cachorros

O tutor pode cadastrar um ou mais cachorros informando:

- Nome
- Raça
- Porte
- Idade
- Observações
- Foto

Caso nenhuma foto seja enviada, o sistema utiliza automaticamente uma imagem obtida pela API **The Dog API**.

---

## 3. Busca de Cachorros

No menu **Meus Cachorros**, o tutor pode pesquisar seus cães utilizando:

- Nome
- Raça

---

## 4. Listagem de Cachorros

Os cachorros cadastrados são exibidos em formato de cards logo abaixo da área de pesquisa.

Cada card apresenta as principais informações do animal.

---

## 5. Edição de Cachorros

O tutor pode editar as informações de qualquer cachorro cadastrado por meio do botão **Editar** disponível em cada card.

---

## 6. Exclusão de Cachorros

Também é possível excluir um cachorro utilizando o botão **X** presente no card.

---

## 7. Listagem de Passeadores

Após realizar login como tutor, o Dashboard exibe todos os passeadores disponíveis.

Ao acessar **Ver Perfil**, é possível visualizar:

- Dados do passeador
- Avaliações recebidas
- Média das avaliações

---

## 8. Agendamento de Passeios

O tutor pode solicitar um passeio selecionando:

- Cachorro
- Passeador
- Data
- Horário
- Duração
- Local
- Valor

---

## 9. Gerenciamento de Passeios

### Tutor

O tutor pode:

- Solicitar passeio
- Cancelar passeio enquanto estiver pendente
- Finalizar passeio após sua conclusão

### Passeador

O passeador pode:

- Aceitar passeio
- Recusar passeio

Os status possíveis são:

- Pendente
- Aceito
- Recusado
- Cancelado
- Finalizado

---

## 10. Visualização do Tutor

Antes de aceitar ou recusar um passeio, o passeador pode acessar o perfil do tutor e visualizar:

- Dados do tutor
- Avaliações recebidas
- Média das avaliações

---

## 11. Avaliação de Tutores e Passeadores

Após a finalização do passeio:

- O passeador pode avaliar o tutor.
- O tutor pode avaliar o passeador.

A avaliação possui:

- Nota de 1 a 5
- Comentário (opcional)

---

## 12. Edição do Perfil

Após realizar login, o usuário pode acessar seu perfil e atualizar informações como:

- Nome de usuário
- Nome
- E-mail
- Telefone
- Foto

---

## 13. Logout

Ao clicar na foto de perfil localizada no canto superior direito, o usuário pode sair do sistema.

---

# 🛠 Tecnologias

## Backend

- PHP 8.x
- Laravel 13
- MySQL 8.0.x
- Laravel Sanctum 4.x

## Frontend

- Vue 3.5.x
- TypeScript 5.x
- Vite 8.1.x
- Bootstrap 5.3.x
- Vuetify 4.1.x
- Axios 1.7.x

---

# 🚀 Como executar

## Backend

```bash
cd backend

composer install

cp .env.example .env

php artisan key:generate

php artisan migrate

php artisan serve
```

Servidor:

```
http://localhost:8000
```

---

## Frontend

```bash
cd frontend

npm install

npm run dev
```

Aplicação:

```
http://localhost:5173
```

---

# 🔑 Autenticação

A API utiliza **Laravel Sanctum** para autenticação dos usuários.

---

# 👩‍💻 Desenvolvido por

**Tais Oliveira**