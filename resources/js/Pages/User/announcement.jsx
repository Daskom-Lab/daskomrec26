import { useState, useEffect } from 'react';
import { Head, router } from '@inertiajs/react';

import ButtonSidebar from '@components/ButtonSidebar';
import ButtonHome from '@components/ButtonHome';
import UserSidebar from '@components/UserSidebar';
import UnderwaterEffect from '@components/UnderwaterEffect';

import Background3 from '@assets/backgrounds/Background3.png';
import Chains1 from '@assets/others/DECORATIONS/Chains/01-Chain.png';
import Chains2 from '@assets/others/DECORATIONS/Chains/01-Chain.png';
import logoImg from '@assets/logo/ORB_DLOR 1.png';
import Mobileboard from '@assets/backgrounds/02-ABoard_Mobile.png';
import buttonShiftImg from '@assets/buttons/ButtonAnchor.png';

export default function AnnouncementPage({ userStatus = 'passed' }) {
    const [isSidebarOpen, setIsSidebarOpen] = useState(false);
    const [inputLocked, setInputLocked] = useState(true);
    const [isLoggingOut, setIsLoggingOut] = useState(false);

    const handleShiftClick = () => {
        router.visit('/user/shift');
    };

    const toggleSidebar = () => {
        if (inputLocked || isLoggingOut) return;
        setIsSidebarOpen(prev => !prev);
    };

    const handleLogout = () => {
        setInputLocked(true);
        setIsSidebarOpen(false);
        setTimeout(() => {
            setIsLoggingOut(true);
            setTimeout(() => router.visit('/'), 1000);
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

                <div className={`absolute top-6 left-6 z-60 transition-all duration-700 ${!inputLocked ? 'opacity-100' : 'opacity-0 -translate-x-10'}`}>
                    <ButtonSidebar onClick={toggleSidebar} />
                </div>

                <div className={`absolute top-6 right-6 z-60 transition-all duration-700 ${!inputLocked ? 'opacity-100' : 'opacity-0 translate-x-10'}`}>
                    <ButtonHome onClick={() => router.visit('/user/home')} />
                </div>

                <UserSidebar
                    isOpen={isSidebarOpen}
                    onClose={() => setIsSidebarOpen(false)}
                    onLogout={handleLogout}
                />

                <div className="relative z-30 w-full min-h-screen flex justify-center items-center py-10 md:py-10">

                    <div className="relative w-[85%] max-w-[360px] md:max-w-[550px] lg:max-w-[600px] h-[480px] md:h-[750px] lg:h-[800px] mt-4 md:mt-0">

                        <div 
                            className="absolute bottom-[78%] left-[5%] md:left-[5%] w-[35%] md:w-[40%] h-[200vh] z-10 bg-bottom bg-repeat-y bg-contain"
                            style={{ backgroundImage: `url(${Chains1})` }}
                        />
                        <div 
                            className="absolute bottom-[78%] right-[5%] md:right-[5%] w-[35%] md:w-[40%] h-[200vh] z-10 bg-bottom bg-repeat-y bg-contain"
                            style={{ backgroundImage: `url(${Chains2})` }}
                        />

                        <div
                            className="absolute inset-0 z-20 bg-center bg-no-repeat"
                            style={{
                                backgroundImage: `url(${Mobileboard})`,
                                backgroundSize: '100% 100%'
                            }}
                        >
                            <div className="relative w-full h-full flex flex-col justify-between items-center text-center px-12 md:px-24 pt-[28%] md:pt-[24%] pb-[8%] overflow-hidden">

                                <div className="flex flex-col justify-center items-center flex-1 space-y-2 md:space-y-6 text-white drop-shadow-[0_2px_3px_rgba(0,0,30,0.8)] max-w-full">
                                    <h2
                                        className="text-xs sm:text-sm md:text-[28px] leading-tight font-bold uppercase tracking-[0.2em] sm-tracking-widest border-b border-blue-500/40 pb-0.5 md:pb-4 max-w-full break-words px-2"
                                    >
                                        {userStatus === 'passed' ? 'CONGRATULATIONS' : 'ANNOUNCEMENT'}
                                    </h2>

                                    <p className="text-[9.5px] sm:text-[11px] md:text-[20px] leading-[1.35] md:leading-loose opacity-90 max-w-[240px] sm:max-w-sm md:max-w-lg break-words">
                                        {userStatus === 'passed' ? (
                                            <>
                                                Selamat! Kamu dinyatakan{' '}
                                                <span className="text-blue-600 font-bold">LULUS</span>{' '}
                                                seleksi tahap ini.
                                                <br />
                                                Bersiaplah untuk petualangan selanjutnya di kedalaman samudra DLOR.
                                            </>
                                        ) : (
                                            <>
                                                Mohon maaf, kamu{' '}
                                                <span className="text-red-300 font-semibold">BELUM LULUS</span>{' '}
                                                pada tahap ini.
                                                <br />
                                                Jangan berkecil hati, perjalananmu masih panjang.
                                            </>
                                        )}
                                    </p>
                                </div>

                                <div className="relative w-full flex justify-center items-end h-16 md:h-24 mb-6 md:mb-10 z-20">
                                    {userStatus === 'passed' && (
                                        <button
                                            onClick={handleShiftClick}
                                            className="relative w-32 h-12 md:w-64 md:h-24 hover:scale-105 transition-transform duration-300 active:scale-95"
                                        >
                                            <img
                                                src={buttonShiftImg}
                                                alt=""
                                                className="absolute inset-0 w-full h-full object-contain"
                                            />
                                            <span
                                                className="absolute inset-0 flex items-center justify-center font-bold text-xs md:text-3xl tracking-wider drop-shadow-md text-white"
                                            >
                                                Shift
                                            </span>
                                        </button>
                                    )}

                                    <div className="absolute -right-2 bottom-2 md:-right-5 md:bottom-2 w-12 h-12 md:w-20 md:h-20 animate-pulse-slow">
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

                <div className="absolute bottom-8 md:bottom-8 w-full text-center z-40 text-sm md:text-base font-semibold text-cyan-100/80 tracking-wide">
                    <p>@Atlantis.DLOR2026. All Right Served</p>
                </div>

                <div className="absolute inset-0 z-0">
                    <img
                        src={Background3}
                        alt="Background Utama"
                        className="w-full h-full object-cover brightness-[0.6] scale-105"
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