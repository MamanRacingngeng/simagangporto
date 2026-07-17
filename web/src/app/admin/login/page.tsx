import { redirectIfAuthenticated } from "@/lib/auth";
import { AdminLoginForm } from "./admin-login-form";

export default async function AdminLoginPage() {
  await redirectIfAuthenticated();
  return <AdminLoginForm />;
}
