# 🐶 Dog Walker

Sistema de agendamento de passeios para cães que conecta tutores e passeadores de forma prática e segura. A plataforma permite que os tutores cadastrem seus cães, encontrem passeadores disponíveis e agendem passeios. Ao final de cada passeio, tanto os tutores quanto os passeadores podem realizar avaliações mútuas, promovendo transparência, confiança e a qualidade dos serviços oferecidos.

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

O usuário deve criar uma conta para acessar o sistema na tela de **"Login"** tem a opção de **"Criar conta"**. Durante o preenchimento do cadastro é necessário escolher o tipo de usuário:

- Tutor
- Passeador

Após o login, cada tipo de usuário possui funcionalidades específicas.

---

## 2. Cadastro de Cachorros

O tutor pode cadastrar um ou mais cachorros na opção de menu **"Meus Cachorros"** preenchendo o formulário de cadastro com:

- Nome
- Raça
- Porte
- Idade
- Observações
- Foto

Caso nenhuma foto seja enviada, o sistema utiliza automaticamente uma imagem obtida pela API **The Dog API**.

---

## 3. Busca de Cachorros

Ainda no menu **Meus Cachorros**, o tutor pode pesquisar seus cães utilizando:

- Nome
- Raça

---

## 4. Listagem de Cachorros

Os cachorros cadastrados são exibidos em formato de cards logo abaixo da área de busca de cachorros.

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

O tutor pode solicitar um passeio na opção **"Solicitar passeio"** do menu preenchendo o formulário com informações como:

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

O passeador na opção de menu **"Passeios Disponíveis"** pode:

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

Antes de aceitar ou recusar um passeio, o passeador pode acessar o perfil do tutor em **"Ver Perfil"** que ficam nos cards dos passeios solicitados e visualizar:

- Dados do tutor
- Avaliações recebidas
- Média das avaliações

---

## 11. Avaliação de Tutores e Passeadores

Após a conclusão do passeio, o passeador pode selecionar o passeio aceito e clicar em **"Finalizar Passeio"**. Em seguida, será exibida a tela de avaliação, e o status do passeio será atualizado para permitir que ambas as partes realizem suas avaliações.

- O passeador pode avaliar o tutor;
- O tutor pode avaliar o passeador.

Cada avaliação é composta por:

- Nota de 1 a 5 estrelas;
- Comentário (opcional).

---

## 12. Edição do Perfil

O usuário pode acessar seu perfil por meio do menu superior. Ao clicar na foto de perfil, será exibida a opção **"Perfil"**, onde é possível visualizar e atualizar suas informações cadastrais, incluindo:

- Nome de usuário;
- Nome;
- E-mail;
- Telefone;
- Foto de perfil.

---

## 13. Logout

Ao clicar na foto de perfil localizada no canto superior direito, será exibido a opção **"Sair"** o nde é o usuário pode sair do sistema.

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