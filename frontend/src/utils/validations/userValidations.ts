export interface RegisterForm {
  username: string;
  name: string;
  email: string;
  password: string;
  phone: string;
  type_user: string;
  photo: File | string;
}

export interface RegisterFormErrors {
  usernameError: string;
  nameError: string;
  emailError: string;
  passwordError: string;
  typeuserError: string;
  phoneError: string;
}

const EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

// Exige 8 ou mais caracteres, letras maiúsculas e minúsculas, número e símbolo
const PASSWORD_REGEX =
  /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

// Telefone com DDD Brasileiro
// Aceita celular (11 dígitos, começando com 9)
// Aceita telefone fixo (10 dígitos, começando entre 2 e 5)
const PHONE_REGEX =
  /^(1[1-9]|2[12478]|3[1-8]|4[1-9]|5[1345]|6[1-9]|7[134579]|8[1-9]|9[1-9])(9\d{8}|[2-5]\d{7})$/;

export function validateUsername(username: string): string {
  return !username ? "Insira um username!" : "";
}

export function validateName(name: string): string {
  return !name ? "Insira um nome!" : "";
}

export function validateTypeUser(typeUser: string): string {
  return !typeUser ? "Selecione um tipo de usuário!" : "";
}

export function validateEmail(email: string): string {
  if (!email) return "Insira um e-mail!";
  if (!EMAIL_REGEX.test(email)) return "Insira um e-mail válido!";
  return "";
}

export function validatePassword(password: string): string {
  if (!password) return "Insira uma senha!";
  if (!PASSWORD_REGEX.test(password)) {
    return "A senha deve ter 8+ caracteres, letras (maius./minús.), números e símbolos!";
  }
  return "";
}

export function validatePhone(phone: string): string {
  if (!phone) return "";
  const digitsOnly = phone.replace(/\D/g, "");
  return PHONE_REGEX.test(digitsOnly) ? "" : "Informe um telefone com DDD válido.";
}

export function validateRegisterForm(form: RegisterForm): RegisterFormErrors {
  return {
    usernameError: validateUsername(form.username),
    nameError: validateName(form.name),
    emailError: validateEmail(form.email),
    passwordError: validatePassword(form.password),
    typeuserError: validateTypeUser(form.type_user),
    phoneError: validatePhone(form.phone),
  };
}

export function hasRegisterFormErrors(errors: RegisterFormErrors): boolean {
  return Object.values(errors).some((error) => !!error);
}

export function onlyDigits(value: string | number | null | undefined): string {
  return String(value ?? "").replace(/\D/g, "");
}