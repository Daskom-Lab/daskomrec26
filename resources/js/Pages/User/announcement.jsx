import { useState, useEffect } from "react";
import { Head, router } from "@inertiajs/react";

import ButtonSidebar from "@components/ButtonSidebar";
import ButtonHome from "@components/ButtonHome";
import UserSidebar from "@components/UserSidebar";
import UnderwaterEffect from "@components/UnderwaterEffect";

import Background3 from "@assets/backgrounds/Background3.png";
import Chains1 from "@assets/others/DECORATIONS/Chains/01-Chain.png";
import Chains2 from "@assets/others/DECORATIONS/Chains/01-Chain.png";
import logoImg from "@assets/logo/ORB_DLOR 1.png";
import Mobileboard from "@assets/backgrounds/02-ABoard_Mobile.png";
import buttonShiftImg from "@assets/buttons/ButtonAnchor.png";

export default function AnnouncementPage({
    userStatus = "pending",
    successMessage = "",
    failMessage = "",
    link = "",
    shiftEnabled = false,
    announcementEnabled = false,
    stageName = "",
}) {
    const [isSidebarOpen, setIsSidebarOpen] = useState(false);
    const [inputLocked, setInputLocked] = useState(true);
    const [isLoggingOut, setIsLoggingOut] = useState(false);

    const handleShiftClick = () => {
        router.visit("/user/shift");
    };

    const toggleSidebar = () => {
        if (inputLocked || isLoggingOut) return;
        setIsSidebarOpen((prev) => !prev);
    };

    const handleLogout = () => {
        setInputLocked(true);
        setIsSidebarOpen(false);
        setTimeout(() => {
            setIsLoggingOut(true);
            setTimeout(() => router.post("/logout"), 1000);
        }, 350);
    };

    useEffect(() => {
        const zoomTimer = setTimeout(() => {
            setInputLocked(false);
        }, 500);
    }, []);

    return (
        <>
            <Head title="Announcement" />
            <div className="relative w-full min-h-screen overflow-hidden text-white font-caudex bg-slate-900">
                {/* NAVIGASI & SIDEBAR*/}
                <div
                    className={`absolute top-6 left-6 z-60 transition-all duration-700 ${!inputLocked ? "opacity-100" : "opacity-0 -translate-x-10"}`}
                >
                    <ButtonSidebar onClick={toggleSidebar} />
                </div>

                <div
                    className={`absolute top-6 right-6 z-60 transition-all duration-700 ${!inputLocked ? "opacity-100" : "opacity-0 translate-x-10"}`}
                >
                    <ButtonHome onClick={() => router.visit("/user/home")} />
                </div>

                <UserSidebar
                    isOpen={isSidebarOpen}
                    onClose={() => setIsSidebarOpen(false)}
                    onLogout={handleLogout}
                />

                {/* --- MAIN CONTENT CENTER --- */}
                <div className="relative z-30 w-full min-h-screen flex justify-center items-center py-10 md:py-20">
                    {/* Container Board Utama */}
                    <div className="relative w-[85%] max-w-[360px] md:max-w-[600px] lg:max-w-[650px] h-[480px] sm:h-[550px] md:h-[700px] mt-4 md:mt-10">
                        {/* --- DEKORASI RANTAI --- */}
                        <img
                            src={Chains1}
                            alt="Chain Left"
                            className="absolute -top-[120px] md:-top-[180px] left-[15%] h-[160px] md:h-[250px] object-contain z-10"
                        />
                        <img
                            src={Chains2}
                            alt="Chain Right"
                            className="absolute -top-[120px] md:-top-[180px] right-[15%] h-[160px] md:h-[250px] object-contain z-10"
                        />

                        {/* --- BOARD GAMBAR & KONTEN --- */}
                        <div
                            className="absolute inset-0 z-20 bg-center bg-no-repeat"
                            style={{
                                backgroundImage: `url(${Mobileboard})`,
                                backgroundSize: "100% 100%",
                            }}
                        >
                            {/* Inner Container: Padding membatasi area teks ke dalam "Layar Biru" */}
                            <div className="w-full h-full flex flex-col justify-between items-center text-center px-12 md:px-[12%] pt-[28%] md:pt-[22%] pb-[8%] overflow-hidden">
                                {/* --- BAGIAN ATAS: KONTEN TEKS --- */}
                                <div className="flex flex-col justify-center items-center flex-1 space-y-2 text-white drop-shadow-[0_0_6px_rgba(0,255,255,0.35)] max-w-full">
                                    <h2 className="text-xs sm:text-sm md:text-3xl leading-tight font-bold uppercase tracking-[0.2em] sm-tracking-widest border-b border-blue-400/30 pb-0.5 md:pb-2 max-w-full break-words px-2">
                                        {userStatus === "passed" ? (
                                            "CONGRATULATIONS"
                                        ) : (
                                            <>
                                                WE'RE{" "}
                                                <span className="text-red-500">
                                                    SORRY
                                                </span>{" "}
                                                TO INFORM
                                            </>
                                        )}
                                    </h2>

                                    <div className="text-[9.5px] sm:text-[11px] md:text-base leading-[1.35] md:leading-relaxed opacity-90 max-w-[240px] sm:max-w-sm md:max-w-md break-words">
                                        {userStatus === "passed" ? (
                                            <div
                                                className="prose prose-invert prose-sm max-w-none text-center"
                                                dangerouslySetInnerHTML={{
                                                    __html:
                                                        successMessage ||
                                                        '<p>Selamat! Kamu dinyatakan <span class="text-blue-400 font-semibold">LULUS</span> seleksi tahap ini.</p>',
                                                }}
                                            />
                                        ) : (
                                            <div
                                                className="prose prose-invert prose-sm max-w-none text-center"
                                                dangerouslySetInnerHTML={{
                                                    __html:
                                                        failMessage ||
                                                        '<p>Mohon maaf, kamu <span class="text-red-300 font-semibold">BELUM LULUS</span> pada tahap ini.</p>',
                                                }}
                                            />
                                        )}
                                    </div>
                                </div>

                                {/* --- BAGIAN BAWAH: TOMBOL SHIFT & LOGO --- */}
                                <div className="relative w-full flex justify-center items-end h-16 md:h-20 mb-4 md:mb-6">
                                    {/* BUTTON SHIFT - only show if user passed AND shift is enabled in config */}
                                    {userStatus === "passed" &&
                                        shiftEnabled && (
                                            <button
                                                onClick={handleShiftClick}
                                                className=" relative w-32 h-12 md:w-64 md:h-24 hover:scale-105 transition-transform duration-300 active:scale-95 z-20
                                        "
                                            >
                                                <img
                                                    src={buttonShiftImg}
                                                    alt=""
                                                    className="absolute inset-0 w-full h-full object-contain"
                                                />

                                                <span
                                                    className=" absolute inset-0 flex items-center justify-center font-bold text-xs md:text-2xl tracking-wider drop-shadow-md text-white
                                            "
                                                >
                                                    Shift
                                                </span>
                                            </button>
                                        )}

                                    {/* LINK BUTTON - show if user passed and link is available */}
                                    {userStatus === "passed" &&
                                        link &&
                                        !shiftEnabled && (
                                            <a
                                                href={link}
                                                target="_blank"
                                                rel="noreferrer"
                                                className="relative w-32 h-12 md:w-64 md:h-24 hover:scale-105 transition-transform duration-300 active:scale-95 z-20"
                                            >
                                                <img
                                                    src={buttonShiftImg}
                                                    alt=""
                                                    className="absolute inset-0 w-full h-full object-contain"
                                                />
                                                <span className="absolute inset-0 flex items-center justify-center font-bold text-xs md:text-2xl tracking-wider drop-shadow-md text-white">
                                                    Link
                                                </span>
                                            </a>
                                        )}

                                    {/* LOGO DLOR */}
                                    <div className="absolute right-2 bottom-2 md:right-2 md:bottom-2 w-12 h-12 md:w-22 md:h-22 animate-pulse-slow z-30">
                                        <img
                                            src={logoImg}
                                            alt="Logo DLOR"
                                            className="w-full h-full object-contain drop-shadow-[0_0_15px_rgba(0,200,255,0.5)]"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* --- FOOTER --- */}
                <div className="absolute bottom-2 w-full text-center z-40 text-[10px] md:text-xs text-cyan-100/50">
                    <p>@Atlantis.DLOR2026. All Right Served</p>
                </div>

                {/* --- BACKGROUNDS --- */}
                <div className="absolute inset-0 z-0">
                    <img
                        src={Background3}
                        alt="Background Utama"
                        className="w-full h-full object-cover brightness-[0.6] blur-sm scale-105"
                    />
                    <div className="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/50 to-cyan-900/30" />
                </div>

                <div className="absolute inset-0 z-10 pointer-events-none mix-blend-screen opacity-50">
                    <UnderwaterEffect />
                </div>
            </div>
        </>
    );
}
