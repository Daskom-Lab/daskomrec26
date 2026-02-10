import { useRef, useState, useEffect } from "react";
import { Head, router } from "@inertiajs/react";

import UnderwaterEffect from "@components/UnderwaterEffect";
import background from "@assets/backgrounds/Background3.png";
import ButtonSidebar from "@components/ButtonSidebar";
import AdminSidebar from "@components/AdminSidebar";
import AdminDashboard from "@components/AdminDashboard";

export default function HomeAdmin({
    stages,
    currentStage,
    totalUsers,
    passedUsers,
}) {
    const backgroundRef = useRef(null);
    const USER = "Jyothi";

    // Intro states
    const [showImage, setShowImage] = useState(false);
    const [imageLoaded, setImageLoaded] = useState(false);
    const [isZooming, setIsZooming] = useState(true);
    const [inputLocked, setInputLocked] = useState(true);

    // Sidebar
    const [isSidebarOpen, setIsSidebarOpen] = useState(false);

    // Logout
    const [isLoggingOut, setIsLoggingOut] = useState(false);

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
        const showTimer = setTimeout(() => setShowImage(true), 300);
        const zoomTimer = setTimeout(() => {
            setIsZooming(false);
            setInputLocked(false);
        }, 1800);

        const skipIntro = () => {
            clearTimeout(showTimer);
            clearTimeout(zoomTimer);
            setShowImage(true);
            setIsZooming(false);
            setInputLocked(false);
        };

        const handleKeyDown = (e) => {
            if (e.key === "Escape") skipIntro();
        };
        window.addEventListener("keydown", handleKeyDown);
        window.addEventListener("click", skipIntro);

        return () => {
            clearTimeout(showTimer);
            clearTimeout(zoomTimer);
            window.removeEventListener("keydown", handleKeyDown);
            window.removeEventListener("click", skipIntro);
        };
    }, []);

    const styles = `
        @keyframes subtlePulse {
            0%,100% { opacity:1 }
            50% { opacity:.95 }
        }
        .cold-blue-filter {
            filter: brightness(1) contrast(.95) saturate(2.5) hue-rotate(25deg) sepia(.08);
        }
        .pulse-effect {
            animation: subtlePulse 3s ease-in-out infinite;
        }
    `;

    return (
        <>
            <Head title="Home" />
            <style>{styles}</style>

            {/* 1. OUTERMOST WRAPPER: 
                Fixed to viewport. This prevents the 'body' from scrolling 
                and lets us control the scroll area manually.
            */}
            <div className="fixed inset-0 w-full h-full bg-[#0a2a4a] text-white overflow-hidden">
                {/* 2. BACKGROUND LAYER:
                    Absolute and pointer-events-none so it sits behind everything 
                    and doesn't interfere with scrolling.
                */}
                <div className="absolute inset-0 z-0 pointer-events-none">
                    {/* Gradient */}
                    <div
                        className={`absolute inset-0 transition-opacity duration-700 ${showImage ? "opacity-0" : "opacity-100"}`}
                        style={{
                            background:
                                "linear-gradient(to bottom, #0a2a4a, #0c365b)",
                        }}
                    />

                    {/* Image */}
                    <img
                        ref={backgroundRef}
                        src={background}
                        alt="background"
                        onLoad={() => setImageLoaded(true)}
                        className={`
                            absolute inset-0 w-full h-full object-cover transition-all duration-1500 ease-out
                            ${showImage && imageLoaded ? "opacity-100" : "opacity-0"}
                            ${!isZooming ? "pulse-effect" : ""}
                            cold-blue-filter
                        `}
                        style={{
                            transform:
                                showImage && imageLoaded
                                    ? isZooming
                                        ? "scale(1.5)"
                                        : "scale(1.0)"
                                    : "scale(1.3)",
                            transformOrigin: "center",
                        }}
                    />

                    {/* Effects */}
                    <UnderwaterEffect />
                    <div
                        className={`absolute inset-0 bg-linear-to-b from-black/25 via-transparent to-black/30 transition-opacity duration-1000 ${showImage && imageLoaded ? "opacity-100" : "opacity-0"}`}
                    />
                </div>

                {/* 3. SCROLLABLE CONTENT LAYER:
                    This creates the scrollbar. 
                    - absolute inset-0: Fills the screen.
                    - overflow-y-auto: Enables vertical scrolling inside this div.
                    - z-10: Sits above background.
                */}
                <div className="absolute inset-0 z-10 overflow-y-auto overflow-x-hidden">
                    <div
                        className={`
                        w-full min-h-full flex flex-col items-center justify-start 
                        pt-24 pb-32 px-4 md:px-8
                        transition-all duration-1000 
                        ${isZooming ? "opacity-0 scale-95" : "opacity-100 scale-100"}
                    `}
                    >
                        {/* Header Text */}
                        <div className="text-center relative z-10 mb-8 mt-4 md:mt-10">
                            <h1
                                className="text-5xl md:text-7xl lg:text-8xl font-bold mb-4 leading-tight"
                                style={{
                                    fontFamily: "Cormorant Infant, serif",
                                    textShadow: "0 2px 20px rgba(0,0,0,.8)",
                                }}
                            >
                                Welcome back, {USER}
                            </h1>
                            <h1
                                className="text-2xl md:text-3xl lg:text-5xl font-bold leading-tight"
                                style={{
                                    fontFamily: "Cormorant Infant, serif",
                                    textShadow: "0 2px 20px rgba(0,0,0,.8)",
                                }}
                            >
                                Let The Deep Uncover Your Purpose
                            </h1>
                        </div>

                        {/* Dashboard Component */}
                        <AdminDashboard
                            stages={stages}
                            currentStage={currentStage}
                            totalUsers={totalUsers}
                            passedUsers={passedUsers}
                        />

                        {/* Extra spacer at bottom */}
                        <div className="h-20" />
                    </div>
                </div>

                {/* 4. FIXED UI ELEMENTS (Sidebar & Modals):
                    These sit OUTSIDE the scrollable layer so they never move.
                    z-index must be higher than the scroll layer (z-10).
                */}

                {/* Sidebar Button */}
                <div
                    className={`absolute top-6 left-6 z-[60] transition-all duration-700 ease-out ${!isZooming && !isLoggingOut ? "opacity-100 translate-x-0" : "opacity-0 -translate-x-6 pointer-events-none"}`}
                >
                    <ButtonSidebar onClick={toggleSidebar} />
                </div>

                {/* Sidebar Menu */}
                <AdminSidebar
                    isOpen={isSidebarOpen}
                    onClose={() => setIsSidebarOpen(false)}
                    onLogout={handleLogout}
                />

                {/* Logout Fade Overlay */}
                <div
                    className="absolute inset-0 z-[70] pointer-events-none transition-opacity duration-1000 ease-in-out"
                    style={{
                        background:
                            "linear-gradient(to bottom, #0a2a4a, #0c365b)",
                        opacity: isLoggingOut ? 1 : 0,
                    }}
                />

                {/* Input Lock Overlay */}
                {inputLocked && (
                    <div className="absolute inset-0 z-[80] pointer-events-auto" />
                )}
            </div>
        </>
    );
}
