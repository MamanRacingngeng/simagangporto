<section id="alur" class="py-20 bg-gray-50 relative -mt-24 pt-32 rounded-t-3xl shadow-lg">
    <div class="max-w-6xl mx-auto px-6">
        <h2 class="text-3xl font-bold mb-10 text-center animate-fade-in">Alur Pendaftaran</h2>

        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-xl shadow hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 step-card step-1">
                <div class="flex flex-col items-center mb-4">
                    <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mb-3 step-icon-container">
                        <img src="/images/buatakun.png" alt="Buat Akun" class="w-14 h-14 object-contain step-icon" loading="lazy" decoding="async">
                    </div>
                    <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold step-number">
                        1
                    </div>
                </div>
                <h3 class="font-bold text-xl mb-2 text-center">Buat Akun</h3>
                <p class="text-gray-600 mt-2 text-center">Daftar dan lengkapi profil Anda.</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 step-card step-2">
                <div class="flex flex-col items-center mb-4">
                    <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mb-3 step-icon-container">
                        <img src="/images/ajukanlamaran.png" alt="Ajukan Lamaran" class="w-14 h-14 object-contain step-icon" loading="lazy" decoding="async">
                    </div>
                    <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold step-number">
                        2
                    </div>
                </div>
                <h3 class="font-bold text-xl mb-2 text-center">Ajukan Lamaran</h3>
                <p class="text-gray-600 mt-2 text-center">Pilih posisi dan kirim berkas.</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 step-card step-3">
                <div class="flex flex-col items-center mb-4">
                    <div class="w-20 h-20 bg-yellow-50 rounded-full flex items-center justify-center mb-3 step-icon-container">
                        <img src="/images/seleksidanhasil.png" alt="Seleksi & Hasil" class="w-14 h-14 object-contain step-icon" loading="lazy" decoding="async">
                    </div>
                    <div class="w-8 h-8 bg-yellow-500 text-white rounded-full flex items-center justify-center text-sm font-bold step-number">
                        3
                    </div>
                </div>
                <h3 class="font-bold text-xl mb-2 text-center">Seleksi & Hasil</h3>
                <p class="text-gray-600 mt-2 text-center">Tunggu hasil seleksi & onboarding.</p>
            </div>
        </div>
    </div>

    <style>
        /* Animasi fade in dengan delay bertahap untuk setiap step */
        .step-card {
            opacity: 0;
            transform: translateY(30px);
            animation: slideUpFadeIn 0.8s ease-out forwards;
        }

        .step-1 {
            animation-delay: 0.2s;
        }

        .step-2 {
            animation-delay: 0.4s;
        }

        .step-3 {
            animation-delay: 0.6s;
        }

        @keyframes slideUpFadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Animasi untuk icon saat hover */
        .step-card:hover .step-icon {
            animation: iconBounce 0.6s ease-in-out;
        }

        .step-card:hover .step-icon-container {
            transform: scale(1.1);
            transition: transform 0.3s ease;
        }

        @keyframes iconBounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-5px);
            }
        }

        /* Animasi untuk angka di circle saat hover */
        .step-card:hover .step-number {
            animation: pulseScale 0.6s ease-in-out;
        }

        @keyframes pulseScale {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.15);
            }
        }

        /* Animasi untuk card saat hover */
        .step-card:hover {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        }

        /* Transisi smooth untuk icon container */
        .step-icon-container {
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .step-icon {
            transition: transform 0.3s ease;
        }
    </style>
</section>

