import { redirectIfAuthenticated } from "@/lib/auth";
import { RegisterForm } from "./register-form";

export default async function RegisterPage() {
  await redirectIfAuthenticated();
  return <RegisterForm />;
}
