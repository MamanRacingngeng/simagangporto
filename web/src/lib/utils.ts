import { clsx, type ClassValue } from "clsx";
import { twMerge } from "tailwind-merge";

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs));
}

export function formatDate(date: Date | string | null | undefined) {
  if (!date) return "-";
  const d = typeof date === "string" ? new Date(date) : date;
  return d.toLocaleDateString("id-ID", {
    day: "numeric",
    month: "long",
    year: "numeric",
  });
}

export const PERMOHONAN_STATUS = [
  "Diajukan",
  "Diverifikasi",
  "Revisi",
  "Perlu Revisi",
  "Diterima",
  "Ditolak",
] as const;

export type PermohonanStatus = (typeof PERMOHONAN_STATUS)[number];

export function statusColor(status: string) {
  switch (status) {
    case "Diterima":
      return "bg-emerald-100 text-emerald-800 ring-1 ring-emerald-200";
    case "Ditolak":
      return "bg-red-100 text-red-800 ring-1 ring-red-200";
    case "Revisi":
    case "Perlu Revisi":
      return "bg-amber-100 text-amber-800 ring-1 ring-amber-200";
    case "Diverifikasi":
      return "bg-violet-100 text-violet-800 ring-1 ring-violet-200";
    default:
      return "bg-gray-100 text-gray-700 ring-1 ring-gray-200";
  }
}

export function statusVariant(
  status: string,
): "success" | "error" | "warning" | "info" | "default" {
  switch (status) {
    case "Diterima":
      return "success";
    case "Ditolak":
      return "error";
    case "Revisi":
    case "Perlu Revisi":
      return "warning";
    case "Diverifikasi":
      return "info";
    default:
      return "default";
  }
}
