import { useState, useEffect, useRef, useMemo } from "react";
import { Head, router, usePage } from "@inertiajs/react";

import ButtonSidebar from "@components/ButtonSidebar";
import ButtonHome from "@components/ButtonHome";
import UserSidebar from "@components/UserSidebar";
import UnderwaterEffect from "@components/UnderwaterEffect";
import ShiftTable from "@components/Table";
import BlueModalWrapper from "@components/BlueBox";
import ShiftSuccessModal from "@components/ShiftInfo";

import utama from "@assets/backgrounds/utama.png";
import buttonImg from "@assets/buttons/ButtonRegular.png";

export default function ShiftPage({
    shifts: rawShifts = [],
    hasChosen: initialHasChosen = false,
    chosenShift: initialChosenShift = null,
}) {
    // --- UI & ANIMATION STATES ---
    const backgroundRef = useRef(null);
    const [showImage, setShowImage] = useState(false);
    const [imageLoaded, setImageLoaded] = useState(false);
    const [isZooming, setIsZooming] = useState(true);
    const [inputLocked, setInputLocked] = useState(true);
    const [isLoggingOut, setIsLoggingOut] = useState(false);
    const [isSidebarOpen, setIsSidebarOpen] = useState(false);

    // --- LOGIC STATES ---
    const [showModal, setShowModal] = useState(false);
    const [showSuccess, setShowSuccess] = useState(initialHasChosen);
    const [selectedShift, setSelectedShift] = useState(
        initialChosenShift
            ? {
                  ...initialChosenShift,
                  // normalize field names for ShiftSuccessModal
                  time_start: initialChosenShift.time_start,
                  time_end: initialChosenShift.time_end,
              }
            : null,
    );
    const [hasChosen, setHasChosen] = useState(initialHasChosen);
    const [isSubmitting, setIsSubmitting] = useState(false);

    const { errors } = usePage().props;

    // --- MAP BACKEND DATA TO TABLE FORMAT ---
    const shifts = useMemo(
        () =>
            rawShifts.map((s) => ({
                id: s.id,
                shift: s.shift_no,
                date: s.date,
                timeStart: s.time_start ? s.time_start.substring(0, 5) : "",
                timeEnd: s.time_end ? s.time_end.substring(0, 5) : "",
                quota: s.kuota,
                caasBooked: Array(s.plottingans_count || 0).fill(null),
                type: null,
            })),
        [rawShifts],
    );

    useEffect(() => {
        const showTimer = setTimeout(() => setShowImage(true), 300);
        const zoomTimer = setTimeout(() => {
            setIsZooming(false);
            setInputLocked(false);
        }, 100);

        const skipIntro = () => {
            clearTimeout(showTimer);
            clearTimeout(zoomTimer);
            setShowImage(true);
            setIsZooming(false);
            setInputLocked(false);
            if (!IS_PASSED) {
                setShowGateModal(true);
            }
        };

        window.addEventListener(
            "keydown",
            (e) => e.key === "Escape" && skipIntro(),
        );
        window.addEventListener("click", skipIntro);

        return () => {
            clearTimeout(showTimer);
            clearTimeout(zoomTimer);
            window.removeEventListener("keydown", skipIntro);
            window.removeEventListener("click", skipIntro);
        };
    }, []);

    const handleGateBackHome = () => {
        setIsLoggingOut(true);
        setTimeout(() => router.visit("/user/home"), 500);
    };

    const handleAddClick = (shift) => {
        if (hasChosen) return;
        // Normalize to use time_start/time_end for the modal
        setSelectedShift({
            ...shift,
            time_start: shift.timeStart,
            time_end: shift.timeEnd,
        });
        setShowModal(true);
    };

    const handleConfirmAdd = () => {
        if (isSubmitting) return;
        setIsSubmitting(true);

        router.post(
            "/user/shift",
            {
                shift_id: selectedShift?.id,
            },
            {
                preserveScroll: true,
                onSuccess: () => {
                    setHasChosen(true);
                    setShowModal(false);
                    setTimeout(() => setShowSuccess(true), 300);
                },
                onError: () => {
                    setShowModal(false);
                },
                onFinish: () => {
                    setIsSubmitting(false);
                },
            },
        );
    };

    const handleHomeClick = () => {
        setIsLoggingOut(true);
        setTimeout(() => router.visit("/user/home"), 500);
    };

    const handleLogout = () => {
        setInputLocked(true);
        setIsSidebarOpen(false);
        setTimeout(() => {
            setIsLoggingOut(true);
            setTimeout(() => router.post("/logout"), 1000);
        }, 350);
    };

    const styles = `
        @keyframes subtlePulse { 0%,100% { opacity:1 } 50% { opacity:.9 } }
        .pulse-effect { animation: subtlePulse 4s ease-in-out infinite; }
        .cold-blue-filter { filter: brightness(0.8) contrast(1.1) saturate(1.2); }
    `;

    const isNavigationVisible = !isZooming && !isLoggingOut;
    const isAnyModalOpen = showModal || showSuccess;

    return (
        <>
            <Head title="Choose Shift" />
            <style>{styles}</style>

            <div className="fixed inset-0 w-full h-full text-white font-caudex bg-[#0a2a4a] overflow-y-auto md:overflow-hidden">
                {/* 1. Background Layer */}
                <div className="fixed inset-0 z-0 pointer-events-none">
                    <img
                        ref={backgroundRef}
                        src={utama}
                        alt="bg"
                        onLoad={() => setImageLoaded(true)}
                        className={`w-full h-full object-cover transition-all duration-[1500ms] ease-out 
                            ${showImage && imageLoaded ? "opacity-100" : "opacity-0"} 
                            ${!isZooming ? "pulse-effect" : ""} cold-blue-filter`}
                        style={{
                            transform:
                                showImage && imageLoaded
                                    ? "scale(1.0)"
                                    : "scale(1.2)",
                            transformOrigin: "center",
                        }}
                    />
                </div>

                {/* 2. Effects Layer */}
                <div className="fixed inset-0 z-10 pointer-events-none">
                    <UnderwaterEffect />
                </div>

                {/* 3. Global Navigation: Side-Slide Entry */}
                <div
                    className={`fixed top-4 left-4 md:top-6 md:left-6 z-[80] transition-all duration-700 ease-out 
                    ${!isZooming && !isLoggingOut ? "opacity-100 translate-x-0" : "opacity-0 -translate-x-6 pointer-events-none"}`}
                >
                    <ButtonSidebar
                        onClick={() => setIsSidebarOpen((prev) => !prev)}
                    />
                </div>

                <div
                    className={`fixed top-4 right-4 md:top-6 md:right-6 z-[80] transition-all duration-700 ease-out 
                    ${!isZooming && !isLoggingOut ? "opacity-100 translate-x-0" : "opacity-0 translate-x-6 pointer-events-none"}`}
                >
                    <ButtonHome onClick={handleHomeClick} />
                </div>

                {/* 4. Content Layer: Entry Scale & Opacity */}
                <div
                    className={`relative z-40 flex flex-col items-center justify-start md:justify-center min-h-full w-full px-4 pt-24 pb-12 md:py-0 transition-all duration-1000
                    ${isZooming || isLoggingOut ? "opacity-0 scale-95" : "opacity-100 scale-100"} 
                    ${showModal || showSuccess ? "blur-sm brightness-75" : ""}`}
                >
                    <h1 className="text-3xl sm:text-4xl md:text-5xl mb-6 md:mb-10 font-bold drop-shadow-[0_4px_10px_rgba(0,0,0,0.8)] tracking-wide text-center shrink-0">
                        Choose Your Shift
                    </h1>

                    <div className="w-full max-w-[95%] md:max-w-7xl">
                        <ShiftTable
                            shifts={shifts}
                            onAddShift={handleAddClick}
                            hasChosen={hasChosen}
                        />
                    </div>

                    <div className="mt-10 md:absolute md:bottom-6 w-full text-center text-white text-[10px] md:text-sm tracking-widest opacity-60">
                        @Atlantis.DLOR2026. All Right Served
                    </div>
                </div>

                {/* 5. Sidebars & Modals */}
                <UserSidebar
                    isOpen={isSidebarOpen}
                    onClose={() => setIsSidebarOpen(false)}
                    onLogout={handleLogout}
                />

                <BlueModalWrapper
                    isOpen={showModal}
                    onClose={() => setShowModal(false)}
                >
                    <div className="flex flex-col justify-center items-center text-center h-full w-full space-y-6 px-4">
                        <h1 className="text-[0px] sm:text-sm text-left sm:text-center -2 sm:pl-0 text-white tracking-[0.2em] uppercase font-bold">
                            Let The Deep Uncover Your Purpose
                        </h1>
                        <h1 className="text-xl sm:text-5xl text-white font-bold leading-tight">
                            Are you sure you want <br /> to add this shift?
                        </h1>
                        <div className="flex gap-4 flex-col sm:flex-row md:gap-6">
                            <button
                                onClick={() => setShowModal(false)}
                                className="relative w-40 h-12 px-6 active:scale-95 transition-transform"
                            >
                                <img
                                    src={buttonImg}
                                    alt="btn"
                                    className="absolute inset-0 w-full h-full object-fill"
                                />
                                <span className="relative z-10 text-white text-2xl font-bold">
                                    No
                                </span>
                            </button>
                            <button
                                onClick={handleConfirmAdd}
                                className="relative w-40 h-12 px-6 active:scale-95 transition-transform"
                            >
                                <img
                                    src={buttonImg}
                                    alt="btn"
                                    className="absolute inset-0 w-full h-full object-fill"
                                />
                                <span className="relative z-10 text-white text-2xl font-bold">
                                    Yes
                                </span>
                            </button>
                        </div>
                    </div>
                </BlueModalWrapper>

                <ShiftSuccessModal
                    isOpen={showSuccess}
                    onClose={() => setShowSuccess(false)}
                    shift={selectedShift}
                />

                {/* 6. Exit / Input Lock Layer */}
                <div
                    className={`fixed inset-0 z-[90] pointer-events-none transition-opacity duration-1000 bg-[#0a2a4a] ${isLoggingOut ? "opacity-100" : "opacity-0"}`}
                />
                {inputLocked && (
                    <div className="fixed inset-0 z-[100] cursor-wait" />
                )}
            </div>
        </>
    );
}
