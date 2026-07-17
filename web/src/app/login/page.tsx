import { redirectIfAuthenticated } from "@/lib/auth";
import { LoginForm } from "./login-form";

export default async function LoginPage() {
  await redirectIfAuthenticated();
  return <LoginForm />;
}
